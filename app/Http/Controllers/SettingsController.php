<?php

namespace App\Http\Controllers;

use App\Models\UserSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Display the personal settings page.
     * Available to all authenticated users.
     */
    public function personal(): View
    {
        $user = Auth::user();
        $theme = $user->getTheme();

        return view('settings.personal', [
            'user' => $user,
            'theme' => $theme,
        ]);
    }

    /**
     * Update the user's theme preference.
     */
    public function updateTheme(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'theme' => ['required', 'in:light,dark,system'],
        ]);

        $user = Auth::user();

        // Get or create settings record
        $settings = $user->settings;
        if (!$settings) {
            $settings = $user->settings()->create(['theme' => 'system']);
        }

        $settings->update(['theme' => $validated['theme']]);

        return redirect()->route('settings.personal')
            ->with('success', 'Theme preference updated successfully.');
    }

    /**
     * Update the user's profile photo.
     * Rate limited to 5 uploads per 10 minutes via route middleware.
     */
    public function updateProfilePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'profile_photo.required' => 'Please select a photo to upload.',
            'profile_photo.image' => 'The file must be an image.',
            'profile_photo.mimes' => 'Only JPG, PNG, and WebP images are allowed.',
            'profile_photo.max' => 'The photo must not exceed 2MB.',
        ]);

        $user = Auth::user();

        // Delete old photo if exists
        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Store new photo
        $file = $request->file('profile_photo');
        $filename = $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('avatars', $filename, 'public');

        // Update user record
        $user->update(['profile_photo' => $path]);

        return redirect()->route('settings.personal')
            ->with('success', 'Profile photo updated successfully.');
    }

    /**
     * Delete the user's profile photo.
     */
    public function deleteProfilePhoto(): RedirectResponse
    {
        $user = Auth::user();

        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $user->update(['profile_photo' => null]);

        return redirect()->route('settings.personal')
            ->with('success', 'Profile photo removed successfully.');
    }

    /**
     * Display the family settings page.
     * Only accessible by Owner (protected by TenantPolicy).
     */
    public function family(): View
    {
        $user = Auth::user();
        $tenant = $user->tenant;

        // Authorize via policy
        Gate::authorize('view', $tenant);

        return view('settings.family', [
            'user' => $user,
            'tenant' => $tenant,
        ]);
    }

    /**
     * Update the family (tenant) settings.
     * Only accessible by Owner (protected by TenantPolicy).
     */
    public function updateFamily(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $tenant = $user->tenant;

        // Authorize via policy
        Gate::authorize('update', $tenant);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'currency' => ['required', 'string', 'max:10'],
        ]);

        $tenant->update($validated);

        // Refresh tenant to avoid stale data
        $tenant->refresh();

        return redirect()->route('settings.family')
            ->with('success', 'Family settings updated successfully.');
    }
}
