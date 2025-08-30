<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Preference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Get user profile
     */
    public function profile(Request $request)
    {
        $user = $request->user()->load('preferences');
        
        return response()->json([
            'user' => $user,
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'age' => 'sometimes|integer|min:18|max:100',
            'gender' => 'sometimes|in:male,female,other',
            'religion' => 'sometimes|string|max:255',
            'caste' => 'sometimes|string|max:255',
            'income' => 'sometimes|integer|min:0',
            'education' => 'sometimes|string|max:255',
            'location' => 'sometimes|string|max:255',
            'occupation' => 'sometimes|string|max:255',
            'bio' => 'sometimes|string|max:1000',
            'profile_picture' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ]);

        $user = $request->user();
        $updateData = $request->only([
            'name', 'age', 'gender', 'religion', 'caste', 'income',
            'education', 'location', 'occupation', 'bio'
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Store new profile picture
            $file = $request->file('profile_picture');
            $fileName = 'profile_pictures/' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            
            $path = $file->storeAs('profile_pictures', $fileName, 'public');
            $updateData['profile_picture'] = $path;
        }

        $user->update($updateData);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user->fresh()->load('preferences'),
        ]);
    }

    /**
     * Update user preferences
     */
    public function updatePreferences(Request $request)
    {
        $request->validate([
            'preferred_age_min' => 'sometimes|integer|min:18|max:100',
            'preferred_age_max' => 'sometimes|integer|min:18|max:100',
            'preferred_gender' => 'sometimes|in:male,female,other',
            'preferred_religion' => 'sometimes|string|max:255',
            'preferred_caste' => 'sometimes|string|max:255',
            'preferred_income_min' => 'sometimes|integer|min:0',
            'preferred_income_max' => 'sometimes|integer|min:0',
            'preferred_education' => 'sometimes|string|max:255',
            'preferred_location' => 'sometimes|string|max:255',
            'preferred_occupation' => 'sometimes|string|max:255',
            'age_weight' => 'sometimes|numeric|min:0|max:10',
            'gender_weight' => 'sometimes|numeric|min:0|max:10',
            'religion_weight' => 'sometimes|numeric|min:0|max:10',
            'caste_weight' => 'sometimes|numeric|min:0|max:10',
            'income_weight' => 'sometimes|numeric|min:0|max:10',
            'education_weight' => 'sometimes|numeric|min:0|max:10',
            'location_weight' => 'sometimes|numeric|min:0|max:10',
            'occupation_weight' => 'sometimes|numeric|min:0|max:10',
        ]);

        $user = $request->user();
        
        // Create or update preferences
        $preferences = $user->preferences()->updateOrCreate(
            ['user_id' => $user->id],
            array_merge(
                $request->only([
                    'preferred_age_min', 'preferred_age_max', 'preferred_gender',
                    'preferred_religion', 'preferred_caste', 'preferred_income_min',
                    'preferred_income_max', 'preferred_education', 'preferred_location',
                    'preferred_occupation'
                ]),
                $request->only([
                    'age_weight', 'gender_weight', 'religion_weight', 'caste_weight',
                    'income_weight', 'education_weight', 'location_weight', 'occupation_weight'
                ]) ?: Preference::getDefaultWeights()
            )
        );

        return response()->json([
            'message' => 'Preferences updated successfully',
            'preferences' => $preferences,
        ]);
    }

    /**
     * Upload profile picture only
     */
    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ]);

        $user = $request->user();

        // Delete old profile picture if exists
        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Store new profile picture
        $file = $request->file('profile_picture');
        $fileName = 'profile_pictures/' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        
        $path = $file->storeAs('profile_pictures', $fileName, 'public');
        
        $user->update(['profile_picture' => $path]);

        return response()->json([
            'message' => 'Profile picture uploaded successfully',
            'profile_picture' => $path,
            'profile_picture_url' => Storage::disk('public')->url($path),
        ]);
    }

    /**
     * Delete profile picture
     */
    public function deleteProfilePicture(Request $request)
    {
        $user = $request->user();

        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $user->update(['profile_picture' => null]);

        return response()->json([
            'message' => 'Profile picture deleted successfully',
        ]);
    }

    /**
     * Get profile picture URL
     */
    public function getProfilePicture(Request $request)
    {
        $user = $request->user();

        if (!$user->profile_picture) {
            return response()->json([
                'message' => 'No profile picture found',
                'profile_picture_url' => null,
            ]);
        }

        return response()->json([
            'profile_picture_url' => Storage::disk('public')->url($user->profile_picture),
        ]);
    }
} 