<?php

namespace App\Http\Controllers;

use App\Models\Hobby;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request, $username = null)
    {
        $user = $username ? User::where('username', $username)->firstOrFail() : Auth::user();
        $user->load(['school', 'hobbies', 'communities']);

        $tab = $request->get('tab', 'posts'); // posts, circles, threads

        $posts = $user->posts()
            ->with(['community', 'hobby', 'likes', 'comments.user'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate(10);

        $communities = $user->communities()
            ->with(['school', 'hobby'])
            ->withCount('members')
            ->paginate(12);

        $threads = $user->threads()
            ->with(['hobby', 'community'])
            ->withCount('comments')
            ->latest()
            ->paginate(10);

        $isOwnProfile = Auth::check() && Auth::id() === $user->id;

        return view('profile.show', compact('user', 'tab', 'posts', 'communities', 'threads', 'isOwnProfile'));
    }

    public function edit()
    {
        $user = Auth::user();
        $user->load(['school', 'hobbies']);

        $schools = School::orderBy('school_name')->get();
        $hobbiesByCategory = Hobby::all()->groupBy('category');

        return view('profile.edit', compact('user', 'schools', 'hobbiesByCategory'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:500'],
            'school_type' => ['required', 'in:existing,custom'],
            'school_id' => ['required_if:school_type,existing', 'nullable', 'exists:schools,id'],
            'new_school_name' => ['required_if:school_type,custom', 'nullable', 'string', 'max:255'],
            'new_school_city' => ['required_if:school_type,custom', 'nullable', 'string', 'max:255'],
            'hobby_ids' => ['required', 'array', 'min:1'],
            'hobby_ids.*' => ['exists:hobbies,id'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'hobby_ids.required' => 'Pilih minimal 1 hobi.',
        ]);

        $user->name = $validated['name'];
        $user->bio = $validated['bio'];

        // School
        if ($validated['school_type'] === 'custom') {
            $school = School::firstOrCreate([
                'school_name' => trim($validated['new_school_name']),
                'city' => trim($validated['new_school_city']),
            ]);
            $user->school_id = $school->id;
        } else {
            $user->school_id = $validated['school_id'];
        }

        // Avatar
        if ($request->hasFile('avatar')) {
            if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $user->avatar_path = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save();

        // Hobbies
        $user->hobbies()->sync($validated['hobby_ids']);

        return redirect()->route('profile.show', $user->username)->with('success', 'Profilmu berhasil diperbarui!');
    }
}
