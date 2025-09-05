<?php

namespace App\Http\Controllers\Api\Matrimonial;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\MatrimonialProfile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\Matrimonial\MatrimonialProfileResource;
use App\Http\Resources\Matrimonial\MatrimonialProfileCollection;

class MatrimonialProfileController extends Controller
{
    /**
     * Generate a cache key based on request parameters
     */
    protected function generateCacheKey(Request $request, string $prefix = 'matrimonial_profiles'): string
    {
        $params = $request->only([
            'gender', 'marital_status', 'religion', 'caste', 
            'min_age', 'max_age', 'country', 'state', 'city', 'per_page', 'page'
        ]);

        // Sort the parameters to ensure consistent key generation
        ksort($params);

        // Create a unique key based on the parameters
        $keyString = $prefix . ':' . md5(serialize($params));

        return $keyString;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MatrimonialProfile::with(['user', 'familyMember', 'media']);
        
        // Apply filters if provided
        if ($request->has('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->has('marital_status')) {
            $query->where('marital_status', $request->marital_status);
        }

        if ($request->has('religion')) {
            $query->where('religion', 'LIKE', '%' . $request->religion . '%');
        }

        if ($request->has('caste')) {
            $query->where('caste', 'LIKE', '%' . $request->caste . '%');
        }

        if ($request->has('min_age') || $request->has('max_age')) {
            if ($request->has('min_age')) {
                $query->where('age', '>=', $request->min_age);
            }
            if ($request->has('max_age')) {
                $query->where('age', '<=', $request->max_age);
            }
        }

        if ($request->has('country')) {
            $query->where('country', 'LIKE', '%' . $request->country . '%');
        }

        if ($request->has('state')) {
            $query->where('state', 'LIKE', '%' . $request->state . '%');
        }

        if ($request->has('city')) {
            $query->where('city', 'LIKE', '%' . $request->city . '%');
        }

        // Only show active profiles
        $query->where('is_active', true);
        
        // Generate dynamic cache key
        $cacheKey = $this->generateCacheKey($request);
        
        // Use cache tags if supported by the cache driver
        if (method_exists(Cache::getStore(), 'tags')) {
            $profiles = Cache::tags(['matrimonial', 'profiles'])->remember($cacheKey, 60, function () use ($query, $request) {
                return $query->paginate($request->get('per_page', 15));
            });
        } else {
            $profiles = Cache::remember($cacheKey, 60, function () use ($query, $request) {
                return $query->paginate($request->get('per_page', 15));
            });
        }
        
        return new MatrimonialProfileCollection($profiles);
    }

    /**
     * Display a listing of profiles by gender.
     */
    public function byGender(Request $request, $gender)
    {
        // Validate gender parameter
        if (!in_array(strtolower($gender), ['male', 'female', 'other'])) {
            return response()->json(['message' => 'Invalid gender parameter'], 400);
        }

        $query = MatrimonialProfile::with(['user', 'familyMember', 'media'])
            ->where('gender', strtolower($gender))
            ->where('is_active', true);

        // Apply additional filters if provided
        if ($request->has('marital_status')) {
            $query->where('marital_status', $request->marital_status);
        }

        if ($request->has('religion')) {
            $query->where('religion', 'LIKE', '%' . $request->religion . '%');
        }

        if ($request->has('caste')) {
            $query->where('caste', 'LIKE', '%' . $request->caste . '%');
        }

        if ($request->has('min_age') || $request->has('max_age')) {
            if ($request->has('min_age')) {
                $query->where('age', '>=', $request->min_age);
            }
            if ($request->has('max_age')) {
                $query->where('age', '<=', $request->max_age);
            }
        }

        if ($request->has('country')) {
            $query->where('country', 'LIKE', '%' . $request->country . '%');
        }

        if ($request->has('state')) {
            $query->where('state', 'LIKE', '%' . $request->state . '%');
        }

        if ($request->has('city')) {
            $query->where('city', 'LIKE', '%' . $request->city . '%');
        }

        // Generate dynamic cache key
        $cacheKey = $this->generateCacheKey($request, 'matrimonial_profiles_' . strtolower($gender));
        
        // Use cache tags if supported by the cache driver
        if (method_exists(Cache::getStore(), 'tags')) {
            $profiles = Cache::tags(['matrimonial', 'profiles', 'gender:' . strtolower($gender)])->remember($cacheKey, 60, function () use ($query, $request) {
                return $query->paginate($request->get('per_page', 15));
            });
        } else {
            $profiles = Cache::remember($cacheKey, 60, function () use ($query, $request) {
                return $query->paginate($request->get('per_page', 15));
            });
        }

        return new MatrimonialProfileCollection($profiles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'family_member_id' => 'nullable|exists:family_members,id',
            'gender' => 'nullable|string|in:male,female,other',
            'age' => 'required|integer|min:18|max:100',
            'height' => 'nullable|integer|min:100|max:250',
            'weight' => 'nullable|integer|min:30|max:200',
            'marital_status' => 'required|in:single,divorced,widowed,separated',
            'education' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'annual_income' => 'nullable|numeric',
            'currency' => 'nullable|string|max:3',
            'religion' => 'nullable|string|max:255',
            'caste' => 'nullable|string|max:255',
            'sub_caste' => 'nullable|string|max:255',
            'mother_tongue' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'about_me' => 'nullable|string|max:1000',
            'partner_preferences' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $profile = MatrimonialProfile::create($validated);

        // Invalidate cache
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['matrimonial', 'profiles'])->flush();
        }

        // Load relationships for the resource
        $profile->load(['user', 'familyMember', 'media']);

        return response()->json(new MatrimonialProfileResource($profile), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MatrimonialProfile $matrimonialProfile): JsonResponse
    {
        $matrimonialProfile->load(['user', 'familyMember', 'media']);
        return response()->json(new MatrimonialProfileResource($matrimonialProfile));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MatrimonialProfile $matrimonialProfile): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'family_member_id' => 'nullable|exists:family_members,id',
            'gender' => 'nullable|string|in:male,female,other',
            'age' => 'sometimes|required|integer|min:18|max:100',
            'height' => 'nullable|integer|min:100|max:250',
            'weight' => 'nullable|integer|min:30|max:200',
            'marital_status' => 'sometimes|required|in:single,divorced,widowed,separated',
            'education' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'annual_income' => 'nullable|numeric',
            'currency' => 'nullable|string|max:3',
            'religion' => 'nullable|string|max:255',
            'caste' => 'nullable|string|max:255',
            'sub_caste' => 'nullable|string|max:255',
            'mother_tongue' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'about_me' => 'nullable|string|max:1000',
            'partner_preferences' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $matrimonialProfile->update($validated);

        // Invalidate cache
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['matrimonial', 'profiles'])->flush();
        }

        // Load relationships for the resource
        $matrimonialProfile->load(['user', 'familyMember', 'media']);

        return response()->json(new MatrimonialProfileResource($matrimonialProfile));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MatrimonialProfile $matrimonialProfile): JsonResponse
    {
        $matrimonialProfile->delete();

        // Invalidate cache
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['matrimonial', 'profiles'])->flush();
        }

        return response()->json(null, 204);
    }
}