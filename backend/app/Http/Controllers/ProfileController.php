<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Get the authenticated user's profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        $user = Auth::user();

        // Check if email change is allowed
        $canChangeEmail = true;
        if ($user->email_change_allowed_after && $user->email_change_allowed_after->gt(now())) {
            $canChangeEmail = false;
        }
        
        // Default notification preferences if not set
        if (!$user->notification_preferences) {
            $user->notification_preferences = [
                'email_notifications' => true,
                'push_notifications' => true,
                'item_updates' => true
            ];
        }
        
        // Default privacy settings if not set
        if (!$user->privacy_settings) {
            $user->privacy_settings = [
                'show_email' => false,
                'show_phone' => false,
                'show_address' => false
            ];
        }
        
        return response()->json([
            'user' => $user,
            'can_change_email' => $canChangeEmail,
            'can_change_email_after' => $user->email_change_allowed_after,
            'profile_last_updated' => $user->profile_updated_at,
            'profile_photo_last_changed' => $user->last_profile_pic_change
        ]);
    }
    
    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'firstname' => 'sometimes|string|max:255',
            'lastname' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'sometimes|nullable|string|max:20',
            'address' => 'sometimes|nullable|string|max:255',
            'bio' => 'sometimes|nullable|string|max:1000',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        // Handle email change restrictions
        if ($request->has('email') && $request->email !== $user->email) {
            // Check if email change is allowed
            if ($user->email_change_allowed_after && $user->email_change_allowed_after->gt(now())) {
                return response()->json([
                    'message' => 'You cannot change your email until ' . $user->email_change_allowed_after->format('M d, Y'),
                    'can_change_after' => $user->email_change_allowed_after
                ], 403);
            }
            
            // Set restriction for future email changes (30 days)
            $user->email_change_allowed_after = now()->addDays(30);
        }
        
        // Update basic profile fields
        if ($request->has('firstname')) $user->firstname = $request->firstname;
        if ($request->has('lastname')) $user->lastname = $request->lastname;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('phone')) $user->phone = $request->phone;
        if ($request->has('address')) $user->address = $request->address;
        if ($request->has('bio')) $user->bio = $request->bio;
        
        // Track profile update time
        $user->profile_updated_at = now();
        $user->save();
        
        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }
    
    /**
     * Update the user's profile photo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePhoto(Request $request)
    {
        $user = Auth::user();
        
        // Check if photo change is allowed (limit to once per week)
        if ($user->last_profile_pic_change && $user->last_profile_pic_change->gt(now()->subWeek())) {
            return response()->json([
                'message' => 'You can only change your profile photo once per week',
                'can_change_after' => $user->last_profile_pic_change->addWeek()->format('M d, Y')
            ], 403);
        }
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }
        
        $path = $request->file('photo')->store('profile-photos', 'public');
        $user->profile_photo = $path;
        $user->last_profile_pic_change = now();
        $user->save();
        
        return response()->json([
            'message' => 'Profile photo updated successfully',
            'photo_path' => Storage::url($path)
        ]);
    }
    
    /**
     * Update the user's notification preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateNotificationPreferences(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'email_notifications' => 'required|boolean',
            'push_notifications' => 'required|boolean',
            'item_updates' => 'required|boolean',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        // Update notification preferences
        $user->notification_preferences = [
            'email_notifications' => $request->email_notifications,
            'push_notifications' => $request->push_notifications,
            'item_updates' => $request->item_updates
        ];
        
        $user->save();
        
        return response()->json([
            'message' => 'Notification preferences updated successfully',
            'notification_preferences' => $user->notification_preferences
        ]);
    }
    
    /**
     * Update the user's privacy settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePrivacySettings(Request $request)
    {
        $user = Auth::user();
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'show_email' => 'required|boolean',
            'show_phone' => 'required|boolean',
            'show_address' => 'required|boolean',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        // Update privacy settings
        $user->privacy_settings = [
            'show_email' => $request->show_email,
            'show_phone' => $request->show_phone,
            'show_address' => $request->show_address
        ];
        
        $user->save();
        
        return response()->json([
            'message' => 'Privacy settings updated successfully',
            'privacy_settings' => $user->privacy_settings
        ]);
    }
}
