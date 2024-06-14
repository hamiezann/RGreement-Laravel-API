<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class UserAccessController extends Controller
{
    public function register(Request $request)
    {
        $registerUserData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:renter,landlord',
        ]);

        try {
            $user = User::create([
                'name' => $registerUserData['name'],
                'email' => $registerUserData['email'],
                'password' => Hash::make($registerUserData['password']),
                'role' => $registerUserData['role'],
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error creating user',
            ], 500);
        }

        return response()->json([
            'message' => 'User Created',
        ]);
    }

    public function login(Request $request)
    {
        $loginUserData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|min:8',
        ]);

        $user = User::where('email', $loginUserData['email'])->first();
        if (!$user || !Hash::check($loginUserData['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401);
        }

        $token = $user->createToken($user->name . '-AuthToken', ['*'], Carbon::now()->addHour(1))->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'role' => $user->role,
            'userId' => $user->id,
            'message' => 'You are logged in',
        ]);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            "message" => "Logged out"
        ]);
    }

    public function updateProfile(Request $request, $userId)
    {
        // Fetch the user by ID
        $user = User::find($userId);

        // Check if user exists
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $updateUserData = $request->validate([
            'name' => 'nullable|string',
            'biography' => 'nullable|string',
            'career' => 'nullable|string',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string',
            // 'verified_member' => 'nullable|boolean',
            'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'age' => 'nullable|integer|min:0',
            'income' => 'nullable|numeric|min:0',
        ]);

        if ($request->hasFile('profile_pic')) {
            try {
                $file = $request->file('profile_pic');
                $path = $file->store('profile_pics', 'public');
                $updateUserData['profile_pic'] = $path;
            } catch (\Exception $e) {
                Log::error('Error uploading profile picture: ' . $e->getMessage());
                return response()->json([
                    'message' => 'Error uploading profile picture',
                ], 500);
            }
        }

        foreach ($updateUserData as $key => $value) {
            $user->{$key} = $value;
        }

        // Check if all required fields are filled
        if (
            !empty($user->name) &&
            !empty($user->email) &&
            !empty($user->biography) &&
            !empty($user->career) &&
            !empty($user->address) &&
            !empty($user->age) &&
            !empty($user->income)
        ) {
            $user->verified_member = true;
        } else {
            $user->verified_member = false;
        }

        try {
            $user->save();
        } catch (\Exception $e) {
            Log::error('Error updating user profile: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error updating user profile',
            ], 500);
        }

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
        ]);
    }

    public function getProfile($userId)
    {
        // Fetch the user by ID
        $user = User::find($userId);

        // Check if user exists
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        // Prepare the user data to return
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'biography' => $user->biography,
            'career' => $user->career,
            'address' => $user->address,
            'phone_number' => $user->phone_number,
            'verified_member' => $user->verified_member,
            // 'profile_pic' => $user->profile_pic, 
            'profile_pic' => $user->profile_pic ? asset('storage/' . $user->profile_pic) : null,
            'age' => $user->age,
            'income' => $user->income,
        ];

        // Return the user profile data
        return response()->json([
            'message' => 'User profile retrieved successfully',
            'user' => $userData,
        ]);
    }

    public function changePassword(Request $request, $userId)
    {
        // $user = auth()->user();
        $user = User::find($userId);

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect',
            ], 400);
        }

        try {
            $user->password = Hash::make($request->new_password);
            $user->save();
        } catch (\Exception $e) {
            Log::error('Error changing password: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error changing password',
            ], 500);
        }

        return response()->json([
            'message' => 'Password changed successfully',
        ]);
    }
}
