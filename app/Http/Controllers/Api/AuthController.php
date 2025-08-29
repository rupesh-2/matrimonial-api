<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'age' => 'nullable|integer|min:18|max:100',
            'gender' => 'nullable|in:male,female,other',
            'religion' => 'nullable|string|max:255',
            'caste' => 'nullable|string|max:255',
            'income' => 'nullable|integer|min:0',
            'education' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'age' => $request->age,
            'gender' => $request->gender,
            'religion' => $request->religion,
            'caste' => $request->caste,
            'income' => $request->income,
            'education' => $request->education,
            'location' => $request->location,
            'occupation' => $request->occupation,
            'bio' => $request->bio,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_login' => 'boolean',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        
        // Check if user account is active
        if (!$user->isActive()) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => ['Your account is not active. Please contact support.'],
            ]);
        }

        // Update last login information
        $user->updateLastLogin($request);
        
        // Update remember login preference
        if ($request->has('remember_login')) {
            $user->update(['remember_login' => $request->remember_login]);
        }

        // Create token with expiration based on remember login
        $tokenName = $request->remember_login ? 'remember_token' : 'auth_token';
        $token = $user->createToken($tokenName, [], $request->remember_login ? now()->addYear() : now()->addDay());

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token->plainTextToken,
            'expires_at' => $token->accessToken->expires_at,
            'remember_login' => $user->remember_login,
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Get authenticated user
     */
    public function user(Request $request)
    {
        return response()->json([
            'user' => $request->user()->load('preferences'),
        ]);
    }

    /**
     * Delete user account
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
            'reason' => 'nullable|string|max:500',
        ]);

        $user = $request->user();

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided password is incorrect.'],
            ]);
        }

        // Soft delete the account
        $user->softDeleteAccount($request->reason);

        return response()->json([
            'message' => 'Account deleted successfully. You can restore it within 30 days by contacting support.',
        ]);
    }

    /**
     * Update remember login preference
     */
    public function updateRememberLogin(Request $request)
    {
        $request->validate([
            'remember_login' => 'required|boolean',
        ]);

        $user = $request->user();
        $user->update(['remember_login' => $request->remember_login]);

        return response()->json([
            'message' => 'Remember login preference updated successfully',
            'remember_login' => $user->remember_login,
        ]);
    }

    /**
     * Get login history
     */
    public function getLoginHistory(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'last_login_at' => $user->last_login_at,
            'last_login_ip' => $user->last_login_ip,
            'last_login_user_agent' => $user->last_login_user_agent,
            'remember_login' => $user->remember_login,
        ]);
    }
} 