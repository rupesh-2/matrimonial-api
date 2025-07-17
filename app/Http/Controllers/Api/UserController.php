<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Preference;
use Illuminate\Http\Request;

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
            'profile_picture' => 'sometimes|string|max:255',
        ]);

        $user = $request->user();
        $user->update($request->only([
            'name', 'age', 'gender', 'religion', 'caste', 'income',
            'education', 'location', 'occupation', 'bio', 'profile_picture'
        ]));

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
} 