<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::paginate($request->get('per_page', 15));
        
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
        
        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('mobile_no', 'like', "%{$query}%")
            ->paginate($request->get('per_page', 15));
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

        // Get users with matrimonial profiles and filter by gender
        $users = User::whereHas('profile', function ($query) use ($gender) {
                $query->where('gender', 'LIKE', '%'.$gender.'%');
            })
            ->whereHas('matrimonialProfiles', function ($query) {
                $query->where('is_active', true);
            })
            ->with(['profile', 'matrimonialProfiles'])
            ->paginate($request->get('per_page', 15));

        return new UserCollection($users);
    }
}