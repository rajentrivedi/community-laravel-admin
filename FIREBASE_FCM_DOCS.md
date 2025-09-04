# Firebase Cloud Messaging (FCM) Integration

This document explains how to use the Firebase Cloud Messaging (FCM) integration in the Community App.

## Setup

1. Ensure you have the `kreait/laravel-firebase` package installed:
   ```
   composer require kreait/laravel-firebase
   ```

2. Place your `google-services.json` file in `storage/app/` directory.

3. Run the following command to seed the FCM settings:
   ```
   php artisan fcm:seed-settings
   ```

4. Set up your environment variables in `.env`:
   ```
   FIREBASE_CREDENTIALS=/path/to/service-account.json
   FIREBASE_PROJECT=app
   ```

## Usage

### Sending Notifications

You can send notifications using the `FirebaseMessagingService`:

```php
use App\Services\FirebaseMessagingService;

$firebaseMessaging = new FirebaseMessagingService();

// Send to a specific device
$firebaseMessaging->sendToDevice($deviceToken, 'Hello', 'World', ['key' => 'value']);

// Send to a specific user (all their registered devices)
$firebaseMessaging->sendToUser($user, 'Hello', 'World', ['key' => 'value']);

// Send to a topic
$firebaseMessaging->sendToTopic('news', 'New Article', 'Check out our latest article', ['url' => 'https://example.com']);

// Send to multiple devices
$firebaseMessaging->sendToDevices([$token1, $token2], 'Hello', 'World', ['key' => 'value']);
```

### API Endpoints

The following API endpoints are available:

#### FCM Messaging Endpoints
- `POST /api/firebase/send-to-device` - Send a notification to a specific device
- `POST /api/firebase/send-to-topic` - Send a notification to a topic
- `POST /api/firebase/subscribe-to-topic` - Subscribe a device to a topic
- `POST /api/firebase/unsubscribe-from-topic` - Unsubscribe a device from a topic

#### FCM Token Management Endpoints
- `POST /api/fcm-tokens/register` - Register a new FCM token for the authenticated user
- `POST /api/fcm-tokens/remove` - Remove an FCM token for the authenticated user
- `GET /api/fcm-tokens/user` - Get all FCM tokens for the authenticated user

### Managing Settings

Firebase settings are stored in the `settings` table and can be managed through the SettingsService:

```php
use App\Services\SettingsService;

$settingsService = new SettingsService();

// Get Firebase settings
$firebaseSettings = $settingsService->getFirebaseSettings();

// Set Firebase settings
$settingsService->setFirebaseSettings([
    'project_id' => 'your-project-id',
    'api_key' => 'your-api-key',
    'messaging_sender_id' => 'your-sender-id',
    'app_id' => 'your-app-id',
    'storage_bucket' => 'your-storage-bucket',
]);
```

## Configuration

The Firebase configuration is stored in `config/firebase.php`. You can publish the config file using:

```
php artisan vendor:publish --provider="Kreait\Laravel\Firebase\ServiceProvider" --tag=config
```

## Environment Variables

The following environment variables can be set in your `.env` file:

- `FIREBASE_CREDENTIALS` - Path to your service account JSON file
- `FIREBASE_PROJECT` - Firebase project name (default: app)
- `FIREBASE_DATABASE_URL` - Firebase Realtime Database URL
- `FIREBASE_STORAGE_DEFAULT_BUCKET` - Default storage bucket
- `FIREBASE_PROJECT_ID` - Firebase project ID
- `FIREBASE_API_KEY` - Firebase API key
- `FIREBASE_MESSAGING_SENDER_ID` - Firebase messaging sender ID
- `FIREBASE_APP_ID` - Firebase app ID
- `FIREBASE_STORAGE_BUCKET` - Firebase storage bucket

## FCM Token Registration (For Mobile Apps)

Mobile apps (Flutter, Android, iOS) can register FCM tokens using the `/api/fcm-tokens/register` endpoint.

### Request
```
POST /api/fcm-tokens/register
Authorization: Bearer YOUR_AUTH_TOKEN
Content-Type: application/json

{
  "device_token": "YOUR_FCM_TOKEN_HERE",
  "device_type": "android", // Optional: android, ios, web
  "device_name": "User's Device Name" // Optional
}
```

### Response
```json
{
  "success": true,
  "message": "FCM token registered successfully",
  "data": {
    "id": 1,
    "user_id": 1,
    "device_token": "YOUR_FCM_TOKEN_HERE",
    "device_type": "android",
    "device_name": "User's Device Name",
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-01T00:00:00.000000Z"
  }
}
```

### Removing FCM Tokens

To remove an FCM token when a user logs out or uninstalls the app:

```
POST /api/fcm-tokens/remove
Authorization: Bearer YOUR_AUTH_TOKEN
Content-Type: application/json

{
  "device_token": "YOUR_FCM_TOKEN_HERE"
}
```