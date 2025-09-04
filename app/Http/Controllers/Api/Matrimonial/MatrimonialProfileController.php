<?php

namespace App\Http\Controllers\Api\Matrimonial;

use App\Http\Controllers\Controller;
use App\Http\Resources\Matrimonial\MatrimonialProfileCollection;
use App\Http\Resources\Matrimonial\MatrimonialProfileResource;
use App\Models\MatrimonialProfile;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class MatrimonialProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MatrimonialProfile::with(['user', 'familyMember', 'media']);
        // info(print_r($request->all(), true));
        // Apply filters if provided
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

        $profiles = $query->paginate($request->get('per_page', 15));
        info(print_r(new MatrimonialProfileCollection($profiles), true));
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
            ->join('users', 'matrimonial_profiles.user_id', '=', 'users.id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('profiles.gender', 'LIKE', '%' . $gender . '%')
            ->where('matrimonial_profiles.is_active', true)
            ->select('matrimonial_profiles.*');

        // Apply additional filters if provided
        if ($request->has('marital_status')) {
            $query->where('matrimonial_profiles.marital_status', $request->marital_status);
        }

        if ($request->has('religion')) {
            $query->where('matrimonial_profiles.religion', 'LIKE', '%' . $request->religion . '%');
        }

        if ($request->has('caste')) {
            $query->where('matrimonial_profiles.caste', 'LIKE', '%' . $request->caste . '%');
        }

        if ($request->has('min_age') || $request->has('max_age')) {
            if ($request->has('min_age')) {
                $query->where('matrimonial_profiles.age', '>=', $request->min_age);
            }
            if ($request->has('max_age')) {
                $query->where('matrimonial_profiles.age', '<=', $request->max_age);
            }
        }

        if ($request->has('country')) {
            $query->where('matrimonial_profiles.country', 'LIKE', '%' . $request->country . '%');
        }

        if ($request->has('state')) {
            $query->where('matrimonial_profiles.state', 'LIKE', '%' . $request->state . '%');
        }

        if ($request->has('city')) {
            $query->where('matrimonial_profiles.city', 'LIKE', '%' . $request->city . '%');
        }

        $profiles = $query->paginate($request->get('per_page', 15));

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
        return response()->json(null, 204);
    }
}