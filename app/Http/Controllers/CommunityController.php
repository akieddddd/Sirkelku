<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\Community;
use App\Models\CommunityMember;
use App\Models\Hobby;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CommunityController extends Controller
{
    public function index(Request $request)
    {
        $query = Community::with(['creator', 'school', 'hobby'])
            ->withCount('members');

        // Search query
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by Hobby Category / Hobby ID
        if ($request->filled('hobby')) {
            $query->where('hobby_id', $request->hobby);
        }

        // Filter by School
        if ($request->filled('school')) {
            $query->where('school_id', $request->school);
        }

        // Filter by City
        if ($request->filled('city')) {
            $query->whereHas('school', function ($q) use ($request) {
                $q->where('city', $request->city);
            });
        }

        $communities = $query->latest()->paginate(12)->withQueryString();

        $hobbies = Hobby::orderBy('name')->get();
        $schools = School::orderBy('school_name')->get();
        $cities = School::select('city')->distinct()->whereNotNull('city')->pluck('city');

        return view('communities.index', compact('communities', 'hobbies', 'schools', 'cities'));
    }

    public function create()
    {
        $hobbies = Hobby::orderBy('name')->get();
        $schools = School::orderBy('school_name')->get();

        return view('communities.create', compact('hobbies', 'schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:communities,name'],
            'description' => ['required', 'string', 'max:1000'],
            'hobby_id' => ['nullable', 'exists:hobbies,id'],
            'school_id' => ['nullable', 'exists:schools,id'],
            'banner' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'name.required' => 'Nama sirkel wajib diisi.',
            'name.unique' => 'Nama sirkel ini sudah terdaftar.',
            'description.required' => 'Tuliskan deskripsi singkat mengenai sirkelmu.',
        ]);

        $slug = Str::slug($validated['name']);
        // Ensure slug uniqueness
        $baseSlug = $slug;
        $count = 1;
        while (Community::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$count}";
            $count++;
        }

        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('communities/banners', 'public');
        }

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('communities/avatars', 'public');
        }

        $community = Community::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
            'hobby_id' => $validated['hobby_id'] ?? null,
            'school_id' => $validated['school_id'] ?? null,
            'banner_path' => $bannerPath,
            'avatar_path' => $avatarPath,
            'created_by' => Auth::id(),
        ]);

        // Auto assign creator as admin member
        CommunityMember::create([
            'community_id' => $community->id,
            'user_id' => Auth::id(),
            'role' => 'admin',
            'joined_at' => now(),
        ]);

        return redirect()->route('communities.show', $community->slug)->with('success', 'Sirkel berhasil dibuat! Kamu adalah Ketua Sirkel.');
    }

    public function show(Request $request, $slug)
    {
        $community = Community::where('slug', $slug)
            ->with(['creator', 'school', 'hobby', 'members.school'])
            ->withCount('members')
            ->firstOrFail();

        $activeTab = $request->get('tab', 'feed'); // feed, forum, members

        $posts = $community->posts()
            ->with(['user.school', 'likes', 'comments.user'])
            ->withCount(['likes', 'comments'])
            ->paginate(10);

        $threads = $community->threads()
            ->with(['user.school', 'hobby'])
            ->withCount('comments')
            ->latest()
            ->paginate(10);

        $members = $community->members()
            ->with('school')
            ->paginate(20);

        $isMember = Auth::check() ? $community->members()->where('users.id', Auth::id())->exists() : false;
        $isAdmin = Auth::check() ? $community->members()->where('users.id', Auth::id())->wherePivot('role', 'admin')->exists() : false;

        return view('communities.show', compact('community', 'activeTab', 'posts', 'threads', 'members', 'isMember', 'isAdmin'));
    }

    public function join(Community $community)
    {
        $userId = Auth::id();

        if ($community->members()->where('users.id', $userId)->exists()) {
            return redirect()->back()->with('info', 'Kamu sudah menjadi anggota sirkel ini.');
        }

        CommunityMember::create([
            'community_id' => $community->id,
            'user_id' => $userId,
            'role' => 'member',
            'joined_at' => now(),
        ]);

        // Notify creator
        if ($community->created_by !== $userId) {
            AppNotification::create([
                'user_id' => $community->created_by,
                'actor_id' => $userId,
                'type' => 'community_joined',
                'title' => 'Anggota baru di ' . $community->name,
                'message' => Auth::user()->name . ' baru saja bergabung ke sirkel ' . $community->name . '!',
                'link_url' => route('communities.show', $community->slug),
            ]);
        }

        return redirect()->back()->with('success', 'Selamat! Kamu resmi bergabung di ' . $community->name . '.');
    }

    public function leave(Community $community)
    {
        $userId = Auth::id();

        $membership = CommunityMember::where('community_id', $community->id)->where('user_id', $userId)->first();
        if (!$membership) {
            return redirect()->back()->with('info', 'Kamu bukan anggota sirkel ini.');
        }

        if ($membership->role === 'admin' && CommunityMember::where('community_id', $community->id)->where('role', 'admin')->count() <= 1) {
            return redirect()->back()->with('error', 'Sebagai satu-satunya admin, kamu tidak bisa keluar sebelum menunjuk admin pengganti.');
        }

        $membership->delete();

        return redirect()->back()->with('info', 'Kamu telah keluar dari ' . $community->name . '.');
    }
}
