<?php

namespace App\Http\Controllers;

use App\Models\Hobby;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->isOnboarded()) {
            return redirect()->route('feed.index');
        }

        $schools = School::orderBy('school_name')->get();
        $hobbiesByCategory = Hobby::all()->groupBy('category');

        return view('onboarding.index', compact('schools', 'hobbiesByCategory', 'user'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'school_type' => ['required', 'in:existing,custom'],
            'school_id' => ['required_if:school_type,existing', 'nullable', 'exists:schools,id'],
            'new_school_name' => ['required_if:school_type,custom', 'nullable', 'string', 'max:255'],
            'new_school_city' => ['required_if:school_type,custom', 'nullable', 'string', 'max:255'],
            'hobby_ids' => ['required', 'array', 'min:1'],
            'hobby_ids.*' => ['exists:hobbies,id'],
            'bio' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'hobby_ids.required' => 'Pilih minimal 1 hobi yang kamu sukai.',
            'hobby_ids.min' => 'Pilih minimal 1 hobi yang kamu sukai.',
            'school_id.required_if' => 'Silakan pilih sekolahmu.',
            'new_school_name.required_if' => 'Masukkan nama sekolahmu.',
            'new_school_city.required_if' => 'Masukkan kota asal sekolahmu.',
            'avatar.max' => 'Ukuran avatar maksimal 2 MB.',
        ]);

        // School assignment
        if ($validated['school_type'] === 'custom') {
            $school = School::firstOrCreate([
                'school_name' => trim($validated['new_school_name']),
                'city' => trim($validated['new_school_city']),
            ]);
            $user->school_id = $school->id;
        } else {
            $user->school_id = $validated['school_id'];
        }

        if (!empty($validated['bio'])) {
            $user->bio = $validated['bio'];
        }

        // Avatar upload
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar_path = $path;
        }

        $user->save();

        // Sync hobbies
        $user->hobbies()->sync($validated['hobby_ids']);

        return redirect()->route('feed.index')->with('success', 'Profilmu sudah siap! Selamat nongkrong di Sirkelku!');
    }
}
