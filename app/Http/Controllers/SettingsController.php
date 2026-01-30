<?php

namespace App\Http\Controllers;

use App\Models\UserSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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

        return redirect()->route('settings.family')
            ->with('success', 'Family settings updated successfully.');
    }
}

