# Caching Strategies for Flutter Applications

This document outlines recommended caching strategies for Flutter applications consuming the Community App API, with a focus on optimizing performance and reducing network requests.

## Table of Contents
1. [Overview](#overview)
2. [Cache Types](#cache-types)
3. [Implementation Strategies](#implementation-strategies)
4. [API-Specific Caching](#api-specific-caching)
5. [Cache Invalidation](#cache-invalidation)
6. [Best Practices](#best-practices)

## Overview

Caching is essential for creating responsive Flutter applications. This guide provides strategies for efficiently caching API responses, particularly for the event, user, publication, and matrimonial modules that implement server-side caching.

## Cache Types

### 1. In-Memory Caching
Store frequently accessed data in memory for quick retrieval.

**Use Cases:**
- User session data
- Recently viewed events
- Configuration settings

**Implementation:**
```dart
class InMemoryCache {
  final Map<String, CacheItem> _cache = {};
  
  void put(String key, dynamic value, {Duration ttl = const Duration(minutes: 5)}) {
    _cache[key] = CacheItem(value, DateTime.now().add(ttl));
  }
  
  dynamic get(String key) {
    final item = _cache[key];
    if (item != null && item.expiry.isAfter(DateTime.now())) {
      return item.value;
    }
    _cache.remove(key);
    return null;
  }
}

class CacheItem {
  final dynamic value;
  final DateTime expiry;
  
  CacheItem(this.value, this.expiry);
}
```

### 2. Persistent Caching
Store data locally on the device for offline access and persistence across app restarts.

**Use Cases:**
- User profiles
- Event listings
- News articles

**Implementation:**
```dart
import 'package:shared_preferences/shared_preferences.dart';

class PersistentCache {
  static Future<void> save(String key, String value) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(key, value);
  }
  
  static Future<String?> load(String key) async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString(key);
  }
}
```

### 3. HTTP Response Caching
Leverage HTTP caching headers for automatic caching.

**Use Cases:**
- API responses with built-in caching
- Static content
- Infrequently changing data

## Implementation Strategies

### 1. Cache-Aside Pattern
Check cache first, then fetch from API if not found.

```dart
class EventService {
  final InMemoryCache _cache = InMemoryCache();
  final ApiClient _apiClient = ApiClient();
  
  Future<List<Event>> getEvents({int page = 1, int perPage = 15}) async {
    final cacheKey = 'events_page_$page';
    final cached = _cache.get(cacheKey);
    
    if (cached != null) {
      return cached as List<Event>;
    }
    
    final events = await _apiClient.getEvents(page: page, perPage: perPage);
    _cache.put(cacheKey, events);
    return events;
  }
}
```

### 2. Cache-Then-Network Pattern
Display cached data immediately, then update with fresh data from API.

```dart
class EventRepository {
  final InMemoryCache _cache = InMemoryCache();
  final ApiClient _apiClient = ApiClient();
  
  Stream<List<Event>> getEventsStream({int page = 1}) async* {
    // Return cached data first if available
    final cacheKey = 'events_page_$page';
    final cached = _cache.get(cacheKey);
    if (cached != null) {
      yield cached as List<Event>;
    }
    
    // Fetch fresh data from API
    final events = await _apiClient.getEvents(page: page);
    _cache.put(cacheKey, events);
    yield events;
  }
}
```

### 3. Write-Through Pattern
Update cache and API simultaneously.

```dart
class EventService {
  final InMemoryCache _cache = InMemoryCache();
  final ApiClient _apiClient = ApiClient();
  
  Future<Event> createEvent(CreateEventRequest request) async {
    final event = await _apiClient.createEvent(request);
    
    // Update relevant cache entries
    _cache.put('event_${event.id}', event);
    _cache.removeWhere((key, value) => key.startsWith('events_page_'));
    
    return event;
  }
}
```

## API-Specific Caching

### Events Module
The events module implements server-side caching with the following endpoints:

1. **List Events**: `GET /api/events`
   - Supports pagination parameters: `page`, `per_page`
   - Cache key: `events_page_{page}_per_{per_page}`

2. **Search Events**: `GET /api/events/search?query={term}`
   - Cache key: `events_search_{query}_page_{page}_per_{per_page}`

3. **Get Event**: `GET /api/events/{id}`
   - Cache key: `event_{id}`

### Users Module
The users module also implements server-side caching:

1. **List Users**: `GET /api/users`
   - Supports pagination parameters: `page`, `per_page`
   - Cache key: `users_page_{page}_per_{per_page}`

2. **Search Users**: `GET /api/users/search?query={term}`
   - Cache key: `users_search_{query}_page_{page}_per_{per_page}`

3. **Get User**: `GET /api/users/{id}`
   - Cache key: `user_{id}`

### Publications Module
The publications module implements server-side caching with the following endpoints:

1. **List Publications**: `GET /api/publications`
   - Supports pagination parameters: `page`, `per_page`
   - Supports filter parameters: `community_id`, `author_id`
   - Cache key: `publications_page_{page}_per_{per_page}_community_{community_id}_author_{author_id}`

2. **Search Publications**: `GET /api/publications/search?query={term}`
   - Cache key: `publications_search_{query}_page_{page}_per_{per_page}`

3. **Get Publication**: `GET /api/publications/{id}`
   - Cache key: `publication_{id}`

### Matrimonial Profiles Module
The matrimonial profiles module implements server-side caching with the following endpoints:

1. **List Matrimonial Profiles**: `GET /api/matrimonial-profiles`
   - Supports pagination parameters: `page`, `per_page`
   - Supports filter parameters: `gender`, `marital_status`, `religion`, `caste`, `min_age`, `max_age`, `country`, `state`, `city`
   - Cache key: `matrimonial_profiles_page_{page}_per_{per_page}_gender_{gender}_marital_status_{marital_status}_religion_{religion}_caste_{caste}_min_age_{min_age}_max_age_{max_age}_country_{country}_state_{state}_city_{city}`

2. **List Matrimonial Profiles by Gender**: `GET /api/matrimonial-profiles/by-gender/{gender}`
   - Supports pagination parameters: `page`, `per_page`
   - Supports filter parameters: `marital_status`, `religion`, `caste`, `min_age`, `max_age`, `country`, `state`, `city`
   - Cache key: `matrimonial_profiles_{gender}_page_{page}_per_{per_page}_marital_status_{marital_status}_religion_{religion}_caste_{caste}_min_age_{min_age}_max_age_{max_age}_country_{country}_state_{state}_city_{city}`

3. **Get Matrimonial Profile**: `GET /api/matrimonial-profiles/{id}`
   - Cache key: `matrimonial_profile_{id}`

## Cache Invalidation

### Time-Based Invalidation
Most cache entries should have a time-to-live (TTL):

```dart
// Default TTL of 5 minutes for most data
final defaultTTL = Duration(minutes: 5);

// Longer TTL for relatively static data
final staticTTL = Duration(hours: 1);

// Shorter TTL for frequently changing data
final dynamicTTL = Duration(minutes: 1);
```

### Event-Driven Invalidation
Invalidate cache entries based on data changes:

```dart
class CacheManager {
  final InMemoryCache _cache = InMemoryCache();
  
  void invalidateEvents() {
    _cache.removeWhere((key, value) => 
      key.startsWith('events') || key.startsWith('event_'));
  }
  
  void invalidateUsers() {
    _cache.removeWhere((key, value) => 
      key.startsWith('users') || key.startsWith('user_'));
  }
  
  void invalidatePublications() {
    _cache.removeWhere((key, value) => 
      key.startsWith('publications') || key.startsWith('publication_'));
  }
  
  void invalidateMatrimonialProfiles() {
    _cache.removeWhere((key, value) => 
      key.startsWith('matrimonial') && (key.contains('profiles') || key.contains('profile')));
  }
  
  void invalidateAll() {
    _cache.clear();
  }
}
```

## Best Practices

### 1. Cache Key Design
Create consistent, descriptive cache keys:
```dart
// Good
'events_page_1_per_15_status_published'
'users_search_john_page_1'
'publications_page_1_per_15_community_5'
'matrimonial_profiles_page_1_per_15_gender_male'

// Avoid
'cache1', 'data2'
```

### 2. Memory Management
Monitor cache size and implement eviction policies:
```dart
class InMemoryCache {
  final Map<String, CacheItem> _cache = {};
  static const int MAX_SIZE = 100;
  
  void put(String key, dynamic value, {Duration ttl = const Duration(minutes: 5)}) {
    // Remove oldest entries if cache is full
    if (_cache.length >= MAX_SIZE) {
      final oldestKey = _cache.keys.first;
      _cache.remove(oldestKey);
    }
    
    _cache[key] = CacheItem(value, DateTime.now().add(ttl));
  }
}
```

### 3. Error Handling
Handle cache failures gracefully:
```dart
Future<List<Event>> getEventsSafely({int page = 1}) async {
  try {
    return await getEvents(page: page);
  } catch (e) {
    // Log error and return empty list or fallback data
    print('Cache error: $e');
    return [];
  }
}
```

### 4. Cache Warming
Preload frequently accessed data:
```dart
class AppInitializer {
  static Future<void> warmupCache() async {
    // Preload first page of events
    await EventService().getEvents();
    
    // Preload user profile
    await UserService().getCurrentUser();
    
    // Preload first page of publications
    await PublicationService().getPublications();
    
    // Preload first page of matrimonial profiles
    await MatrimonialService().getProfiles();
  }
}
```

### 5. Monitoring and Analytics
Track cache performance:
```dart
class CacheMetrics {
  static int hits = 0;
  static int misses = 0;
  
  static double get hitRate => 
    (hits + misses) > 0 ? hits / (hits + misses) : 0;
  
  static void recordHit() => hits++;
  static void recordMiss() => misses++;
}
```

## Implementation Example

Here's a complete example of implementing caching for all modules:

```dart
class ApiRepository {
  final InMemoryCache _cache = InMemoryCache();
  final ApiClient _apiClient = ApiClient();
  
  // Events Module
  Future<List<Event>> getEvents({
    int page = 1, 
    int perPage = 15,
    String? status,
    int? communityId
  }) async {
    // Generate cache key
    final params = {
      'page': page,
      'per_page': perPage,
      if (status != null) 'status': status,
      if (communityId != null) 'community_id': communityId,
    };
    
    final sortedParams = Map.fromEntries(
      params.entries.toList()..sort((a, b) => a.key.compareTo(b.key))
    );
    
    final cacheKey = 'events_' + sortedParams.entries
      .map((e) => '${e.key}_${e.value}')
      .join('_');
    
    // Check cache first
    final cached = _cache.get(cacheKey);
    if (cached != null) {
      CacheMetrics.recordHit();
      return cached as List<Event>;
    }
    
    CacheMetrics.recordMiss();
    
    // Fetch from API
    final events = await _apiClient.getEvents(
      page: page, 
      perPage: perPage,
      status: status,
      communityId: communityId
    );
    
    // Cache the result
    _cache.put(cacheKey, events);
    
    return events;
  }
  
  Future<List<Event>> searchEvents({
    required String query,
    int page = 1,
    int perPage = 15
  }) async {
    // Generate cache key for search
    final cacheKey = 'events_search_${query}_page_${page}_per_${perPage}';
    
    // Check cache first
    final cached = _cache.get(cacheKey);
    if (cached != null) {
      CacheMetrics.recordHit();
      return cached as List<Event>;
    }
    
    CacheMetrics.recordMiss();
    
    // Fetch from API
    final events = await _apiClient.searchEvents(
      query: query,
      page: page,
      perPage: perPage
    );
    
    // Cache the result
    _cache.put(cacheKey, events);
    
    return events;
  }
  
  Future<Event> getEvent(int id) async {
    final cacheKey = 'event_$id';
    
    final cached = _cache.get(cacheKey);
    if (cached != null) {
      CacheMetrics.recordHit();
      return cached as Event;
    }
    
    CacheMetrics.recordMiss();
    
    final event = await _apiClient.getEvent(id);
    _cache.put(cacheKey, event);
    
    return event;
  }
  
  Future<Event> createEvent(CreateEventRequest request) async {
    final event = await _apiClient.createEvent(request);
    
    // Invalidate related cache entries
    _cache.removeWhere((key, value) => key.startsWith('events'));
    
    return event;
  }
  
  Future<Event> updateEvent(int id, UpdateEventRequest request) async {
    final event = await _apiClient.updateEvent(id, request);
    
    // Update specific event cache
    _cache.put('event_$id', event);
    
    // Invalidate list caches
    _cache.removeWhere((key, value) => key.startsWith('events'));
    
    return event;
  }
  
  Future<void> deleteEvent(int id) async {
    await _apiClient.deleteEvent(id);
    
    // Remove from cache
    _cache.remove('event_$id');
    
    // Invalidate list caches
    _cache.removeWhere((key, value) => key.startsWith('events'));
  }
  
  // Publications Module
  Future<List<Publication>> getPublications({
    int page = 1,
    int perPage = 15,
    int? communityId,
    int? authorId
  }) async {
    // Generate cache key
    final params = {
      'page': page,
      'per_page': perPage,
      if (communityId != null) 'community_id': communityId,
      if (authorId != null) 'author_id': authorId,
    };
    
    final sortedParams = Map.fromEntries(
      params.entries.toList()..sort((a, b) => a.key.compareTo(b.key))
    );
    
    final cacheKey = 'publications_' + sortedParams.entries
      .map((e) => '${e.key}_${e.value}')
      .join('_');
    
    // Check cache first
    final cached = _cache.get(cacheKey);
    if (cached != null) {
      CacheMetrics.recordHit();
      return cached as List<Publication>;
    }
    
    CacheMetrics.recordMiss();
    
    // Fetch from API
    final publications = await _apiClient.getPublications(
      page: page,
      perPage: perPage,
      communityId: communityId,
      authorId: authorId
    );
    
    // Cache the result
    _cache.put(cacheKey, publications);
    
    return publications;
  }
  
  Future<List<Publication>> searchPublications({
    required String query,
    int page = 1,
    int perPage = 15
  }) async {
    // Generate cache key for search
    final cacheKey = 'publications_search_${query}_page_${page}_per_${perPage}';
    
    // Check cache first
    final cached = _cache.get(cacheKey);
    if (cached != null) {
      CacheMetrics.recordHit();
      return cached as List<Publication>;
    }
    
    CacheMetrics.recordMiss();
    
    // Fetch from API
    final publications = await _apiClient.searchPublications(
      query: query,
      page: page,
      perPage: perPage
    );
    
    // Cache the result
    _cache.put(cacheKey, publications);
    
    return publications;
  }
  
  Future<Publication> getPublication(int id) async {
    final cacheKey = 'publication_$id';
    
    final cached = _cache.get(cacheKey);
    if (cached != null) {
      CacheMetrics.recordHit();
      return cached as Publication;
    }
    
    CacheMetrics.recordMiss();
    
    final publication = await _apiClient.getPublication(id);
    _cache.put(cacheKey, publication);
    
    return publication;
  }
  
  Future<Publication> createPublication(CreatePublicationRequest request) async {
    final publication = await _apiClient.createPublication(request);
    
    // Invalidate related cache entries
    _cache.removeWhere((key, value) => key.startsWith('publications'));
    
    return publication;
  }
  
  Future<Publication> updatePublication(int id, UpdatePublicationRequest request) async {
    final publication = await _apiClient.updatePublication(id, request);
    
    // Update specific publication cache
    _cache.put('publication_$id', publication);
    
    // Invalidate list caches
    _cache.removeWhere((key, value) => key.startsWith('publications'));
    
    return publication;
  }
  
  Future<void> deletePublication(int id) async {
    await _apiClient.deletePublication(id);
    
    // Remove from cache
    _cache.remove('publication_$id');
    
    // Invalidate list caches
    _cache.removeWhere((key, value) => key.startsWith('publications'));
  }
  
  // Matrimonial Profiles Module
  Future<List<MatrimonialProfile>> getMatrimonialProfiles({
    int page = 1,
    int perPage = 15,
    String? gender,
    String? maritalStatus,
    String? religion,
    String? caste,
    int? minAge,
    int? maxAge,
    String? country,
    String? state,
    String? city
  }) async {
    // Generate cache key
    final params = {
      'page': page,
      'per_page': perPage,
      if (gender != null) 'gender': gender,
      if (maritalStatus != null) 'marital_status': maritalStatus,
      if (religion != null) 'religion': religion,
      if (caste != null) 'caste': caste,
      if (minAge != null) 'min_age': minAge,
      if (maxAge != null) 'max_age': maxAge,
      if (country != null) 'country': country,
      if (state != null) 'state': state,
      if (city != null) 'city': city,
    };
    
    final sortedParams = Map.fromEntries(
      params.entries.toList()..sort((a, b) => a.key.compareTo(b.key))
    );
    
    final cacheKey = 'matrimonial_profiles_' + sortedParams.entries
      .map((e) => '${e.key}_${e.value}')
      .join('_');
    
    // Check cache first
    final cached = _cache.get(cacheKey);
    if (cached != null) {
      CacheMetrics.recordHit();
      return cached as List<MatrimonialProfile>;
    }
    
    CacheMetrics.recordMiss();
    
    // Fetch from API
    final profiles = await _apiClient.getMatrimonialProfiles(
      page: page,
      perPage: perPage,
      gender: gender,
      maritalStatus: maritalStatus,
      religion: religion,
      caste: caste,
      minAge: minAge,
      maxAge: maxAge,
      country: country,
      state: state,
      city: city
    );
    
    // Cache the result
    _cache.put(cacheKey, profiles);
    
    return profiles;
  }
  
  Future<List<MatrimonialProfile>> getMatrimonialProfilesByGender({
    required String gender,
    int page = 1,
    int perPage = 15,
    String? maritalStatus,
    String? religion,
    String? caste,
    int? minAge,
    int? maxAge,
    String? country,
    String? state,
    String? city
  }) async {
    // Generate cache key
    final params = {
      'page': page,
      'per_page': perPage,
      if (maritalStatus != null) 'marital_status': maritalStatus,
      if (religion != null) 'religion': religion,
      if (caste != null) 'caste': caste,
      if (minAge != null) 'min_age': minAge,
      if (maxAge != null) 'max_age': maxAge,
      if (country != null) 'country': country,
      if (state != null) 'state': state,
      if (city != null) 'city': city,
    };
    
    final sortedParams = Map.fromEntries(
      params.entries.toList()..sort((a, b) => a.key.compareTo(b.key))
    );
    
    final cacheKey = 'matrimonial_profiles_${gender}_' + sortedParams.entries
      .map((e) => '${e.key}_${e.value}')
      .join('_');
    
    // Check cache first
    final cached = _cache.get(cacheKey);
    if (cached != null) {
      CacheMetrics.recordHit();
      return cached as List<MatrimonialProfile>;
    }
    
    CacheMetrics.recordMiss();
    
    // Fetch from API
    final profiles = await _apiClient.getMatrimonialProfilesByGender(
      gender: gender,
      page: page,
      perPage: perPage,
      maritalStatus: maritalStatus,
      religion: religion,
      caste: caste,
      minAge: minAge,
      maxAge: maxAge,
      country: country,
      state: state,
      city: city
    );
    
    // Cache the result
    _cache.put(cacheKey, profiles);
    
    return profiles;
  }
  
  Future<MatrimonialProfile> getMatrimonialProfile(int id) async {
    final cacheKey = 'matrimonial_profile_$id';
    
    final cached = _cache.get(cacheKey);
    if (cached != null) {
      CacheMetrics.recordHit();
      return cached as MatrimonialProfile;
    }
    
    CacheMetrics.recordMiss();
    
    final profile = await _apiClient.getMatrimonialProfile(id);
    _cache.put(cacheKey, profile);
    
    return profile;
  }
  
  Future<MatrimonialProfile> createMatrimonialProfile(CreateMatrimonialProfileRequest request) async {
    final profile = await _apiClient.createMatrimonialProfile(request);
    
    // Invalidate related cache entries
    _cache.removeWhere((key, value) => 
      key.startsWith('matrimonial') && key.contains('profiles'));
    
    return profile;
  }
  
  Future<MatrimonialProfile> updateMatrimonialProfile(int id, UpdateMatrimonialProfileRequest request) async {
    final profile = await _apiClient.updateMatrimonialProfile(id, request);
    
    // Update specific profile cache
    _cache.put('matrimonial_profile_$id', profile);
    
    // Invalidate list caches
    _cache.removeWhere((key, value) => 
      key.startsWith('matrimonial') && key.contains('profiles'));
    
    return profile;
  }
  
  Future<void> deleteMatrimonialProfile(int id) async {
    await _apiClient.deleteMatrimonialProfile(id);
    
    // Remove from cache
    _cache.remove('matrimonial_profile_$id');
    
    // Invalidate list caches
    _cache.removeWhere((key, value) => 
      key.startsWith('matrimonial') && key.contains('profiles'));
  }
}
```

This caching strategy ensures optimal performance for Flutter applications while maintaining data consistency with the backend API.