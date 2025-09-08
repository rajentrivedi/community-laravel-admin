<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    /**
     * Generate a cache key based on request parameters
     */
    protected function generateCacheKey(Request $request, string $prefix = 'users'): string
    {
        $params = $request->only(['per_page', 'page', 'query', 'gender']);

        // Sort the parameters to ensure consistent key generation
        ksort($params);

        // Create a unique key based on the parameters
        $keyString = $prefix . ':' . md5(serialize($params));

        return $keyString;
    }

    /**
     * Display a listing of the users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Generate dynamic cache key
        $cacheKey = $this->generateCacheKey($request, 'users');
        
        // Use cache tags if supported by the cache driver
        if (method_exists(Cache::getStore(), 'tags')) {
            $users = Cache::tags(['users'])->remember($cacheKey, 60, function () use ($request) {
                return User::paginate($request->get('per_page', 15));
            });
        } else {
            $users = Cache::remember($cacheKey, 60, function () use ($request) {
                return User::paginate($request->get('per_page', 15));
            });
        }
        
        return new UserCollection($users);
    }

    /**
     * Search users by name, email, or mobile number.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        info('searching users');
        $query = $request->get('query');
        
        if (!$query) {
            
            return response()->json(['message' => 'Query parameter is required'], 400);
        }
        
        // Generate dynamic cache key
        $cacheKey = $this->generateCacheKey($request, 'users_search');
        
        // Use cache tags if supported by the cache driver
        if (method_exists(Cache::getStore(), 'tags')) {
            $users = Cache::tags(['users'])->remember($cacheKey, 60, function () use ($query, $request) {
                return User::where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('mobile_no', 'like', "%{$query}%")
                    ->paginate($request->get('per_page', 15));
            });
        } else {
            $users = Cache::remember($cacheKey, 60, function () use ($query, $request) {
                return User::where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('mobile_no', 'like', "%{$query}%")
                    ->paginate($request->get('per_page', 15));
            });
        }
        
        info(print_r($users, true));
        return new UserCollection($users);
    }

    /**
     * Display the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Display a listing of matrimonial candidates by gender.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $gender
     * @return \Illuminate\Http\Response
     */
    public function matrimonialCandidates(Request $request, $gender)
    {
        // Validate gender parameter
        if (!in_array(strtolower($gender), ['male', 'female', 'other'])) {
            return response()->json(['message' => 'Invalid gender parameter'], 400);
        }

        // Generate dynamic cache key
        $cacheKey = $this->generateCacheKey($request, 'matrimonial_candidates_' . strtolower($gender));
        
        // Use cache tags if supported by the cache driver
        if (method_exists(Cache::getStore(), 'tags')) {
            $users = Cache::tags(['users', 'matrimonial'])->remember($cacheKey, 60, function () use ($request, $gender) {
                // Get users with matrimonial profiles and filter by gender
                return User::whereHas('profile', function ($query) use ($gender) {
                        $query->where('gender', 'LIKE', '%'.$gender.'%');
                    })
                    ->whereHas('matrimonialProfiles', function ($query) {
                        $query->where('is_active', true);
                    })
                    ->with(['profile', 'matrimonialProfiles'])
                    ->paginate($request->get('per_page', 15));
            });
        } else {
            $users = Cache::remember($cacheKey, 60, function () use ($request, $gender) {
                // Get users with matrimonial profiles and filter by gender
                return User::whereHas('profile', function ($query) use ($gender) {
                        $query->where('gender', 'LIKE', '%'.$gender.'%');
                    })
                    ->whereHas('matrimonialProfiles', function ($query) {
                        $query->where('is_active', true);
                    })
                    ->with(['profile', 'matrimonialProfiles'])
                    ->paginate($request->get('per_page', 15));
            });
        }

        return new UserCollection($users);
    }
}