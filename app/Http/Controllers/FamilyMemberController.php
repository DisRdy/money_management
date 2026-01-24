<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class FamilyMemberController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get all family members (including Owner)
        $members = User::where('tenant_id', $user->tenant_id)
            ->orderBy('role')
            ->orderBy('created_at')
            ->get();

        return view('family.index', compact('members'));
    }

    public function create()
    {
        return view('family.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Validate input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['wife', 'child'])],
        ]);

        // Create new family member
        $newMember = User::create([
            'tenant_id' => $user->tenant_id, // Same family as Owner
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        // Success message
        return redirect()
            ->route('family.index')
            ->with('success', "Member '{$newMember->name}' added successfully! They can now login with email: {$newMember->email}");
    }

    public function show(User $user)
    {
        $ownerUser = Auth::user();

        // Ensure member belongs to same tenant
        if ($user->tenant_id !== $ownerUser->tenant_id) {
            abort(403, 'Unauthorized access.');
        }

        return view('family.show', compact('user'));
    }

    public function edit(User $user)
    {
        $ownerUser = Auth::user();

        // Ensure member belongs to same tenant
        if ($user->tenant_id !== $ownerUser->tenant_id) {
            abort(403, 'Unauthorized access.');
        }

        // Cannot edit Owner account
        if ($user->isOwner()) {
            return redirect()
                ->route('family.index')
                ->with('error', 'Cannot edit Owner account.');
        }

        return view('family.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $ownerUser = Auth::user();

        // Ensure member belongs to same tenant
        if ($user->tenant_id !== $ownerUser->tenant_id) {
            abort(403, 'Unauthorized access.');
        }

        // Cannot edit Owner account
        if ($user->isOwner()) {
            return redirect()
                ->route('family.index')
                ->with('error', 'Cannot edit Owner account.');
        }

        // Validate input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['wife', 'child'])],
        ]);

        // Update basic info
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        // Update password if provided
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('family.show', $user)
            ->with('success', "Member '{$user->name}' updated successfully!");
    }
    public function destroy(User $user)
    {
        $ownerUser = Auth::user();

        // Ensure member belongs to same tenant
        if ($user->tenant_id !== $ownerUser->tenant_id) {
            abort(403, 'Unauthorized access.');
        }

        // Cannot delete Owner account
        if ($user->isOwner()) {
            return redirect()
                ->route('family.index')
                ->with('error', 'Cannot delete Owner account.');
        }

        // Cannot delete yourself
        if ($user->id === $ownerUser->id) {
            return redirect()
                ->route('family.index')
                ->with('error', 'Cannot delete your own account.');
        }

        $memberName = $user->name;
        $user->delete();

        return redirect()
            ->route('family.index')
            ->with('success', "Member '{$memberName}' has been removed from the family.");
    }
}
