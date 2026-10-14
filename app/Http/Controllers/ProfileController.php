<?php

namespace App\Http\Controllers;

use App\Models\Hobby;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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

        return redirect()->route('profile.show', $user->username)->with('success', 'Profil berhasil diperbarui.');
    }

    public function updateCredentials(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique('users')->ignore($user->id),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, tanda strip, dan garis bawah.',
            'username.unique' => 'Username ini sudah digunakan.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar.',
        ]);

        $user->update([
            'username' => $validated['username'],
            'email' => $validated['email'],
        ]);

        return redirect()->route('profile.edit')->with('success', 'Kredensial akun (username & email) berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'current_password.current_password' => 'Kata sandi saat ini tidak cocok.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile.edit')->with('success', 'Kata sandi berhasil diperbarui.');
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'password' => ['required', 'string'],
        ], [
            'password.required' => 'Kata sandi konfirmasi wajib diisi untuk menghapus akun.',
        ]);

        if (! Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password_delete' => 'Kata sandi yang kamu masukkan salah.'], 'userDeletion');
        }

        DB::transaction(function () use ($user) {
            // Bersihkan file avatar pengguna
            if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
                Storage::disk('public')->delete($user->avatar_path);
            }

            // Bersihkan file gambar postingan pengguna
            foreach ($user->posts as $post) {
                if ($post->image_path && Storage::disk('public')->exists($post->image_path)) {
                    Storage::disk('public')->delete($post->image_path);
                }
            }

            // Bersihkan relasi many-to-many hobi
            $user->hobbies()->detach();

            // Logout terlebih dahulu sebelum delete agar SessionGuard tidak memperbarui remember_token pada model yang sudah terhapus
            Auth::logout();

            // Hapus user (relasi lain cascade sesuai skema database)
            $user->delete();
        });

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Akun kamu telah berhasil dihapus secara permanen.');
    }
}
