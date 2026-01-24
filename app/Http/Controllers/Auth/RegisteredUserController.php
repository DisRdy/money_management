<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'family_name' => ['required', 'string', 'max:255'], // Family/Tenant name
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        /**
         * Multi-Tenant Registration Process
         * ==================================
         * 1. Create a new Tenant (Family)
         * 2. Create User as Owner and link to Tenant
         * 3. This demonstrates automatic tenant creation for UAS
         */

        // Step 1: Create Tenant (Family)
        $tenant = Tenant::create([
            'name' => $request->family_name,
        ]);

        // Step 2: Create User as Owner and link to Tenant
        $user = User::create([
            'tenant_id' => $tenant->id, // Link user to tenant (Many-to-One relationship)
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'owner', // First registration is always Owner
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
