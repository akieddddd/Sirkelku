<?php

namespace Tests\Feature;

use App\Models\MatchRequest;
use App\Models\Post;
use App\Models\User;
use Tests\TestCase;

class InstagramPrivacySystemTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        if (User::count() === 0) {
            $this->seed(\Database\Seeders\SirkelkuSeeder::class);
        }
    }

    public function test_privacy_toggle_persists_in_database_and_restores_on_edit_page(): void
    {
        $user = User::factory()->create([
            'username' => 'priv_toggle_' . uniqid(),
            'password' => 'password123',
            'school_id' => 1,
            'is_private' => false,
        ]);
        $user->hobbies()->attach([1]);

        $this->actingAs($user);

        // Verify initial state on profile.edit page (unchecked)
        $res = $this->get(route('profile.edit'));
        $res->assertStatus(200);
        $res->assertSee('Publik');

        // 1. Enable private mode
        $response = $this->put(route('profile.credentials.update'), [
            'username' => $user->username,
            'email' => $user->email,
            'is_private' => '1',
        ]);
        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue($user->is_private);

        // 2. Reload edit page and verify switch is checked with 'Privat'
        $editRes = $this->get(route('profile.edit'));
        $editRes->assertStatus(200);
        $editRes->assertSee('checked', false);
        $editRes->assertSee('Privat');

        // 3. Toggle back to public (with is_private = '0' from hidden input)
        $response = $this->put(route('profile.credentials.update'), [
            'username' => $user->username,
            'email' => $user->email,
            'is_private' => '0',
        ]);
        $response->assertRedirect(route('profile.edit'));

        $user->refresh();
        $this->assertFalse($user->is_private);

        // Clean up
        $user->delete();
    }

    public function test_guest_cannot_see_private_posts_in_feed_or_search(): void
    {
        $publicUser = User::factory()->create([
            'username' => 'pub_user_' . uniqid(),
            'school_id' => 1,
            'is_private' => false,
        ]);
        $publicUser->hobbies()->attach([1]);

        $privateUser = User::factory()->create([
            'username' => 'priv_user_' . uniqid(),
            'school_id' => 1,
            'is_private' => true,
        ]);
        $privateUser->hobbies()->attach([1]);

        $publicPost = Post::create([
            'user_id' => $publicUser->id,
            'content' => 'Postingan Publik Terbuka ' . uniqid(),
        ]);

        $privatePost = Post::create([
            'user_id' => $privateUser->id,
            'content' => 'Postingan Rahasia Privat ' . uniqid(),
        ]);

        // Guest visits main timeline (GET /)
        $feedRes = $this->get('/');
        $feedRes->assertStatus(200);
        $feedRes->assertSee($publicPost->content);
        $feedRes->assertDontSee($privatePost->content);

        // Guest searches for private post content
        $searchRes = $this->get('/?q=' . urlencode('Rahasia'));
        $searchRes->assertStatus(200);
        $searchRes->assertDontSee($privatePost->content);

        // Clean up
        $publicPost->delete();
        $privatePost->delete();
        $publicUser->delete();
        $privateUser->delete();
    }

    public function test_logged_in_user_only_sees_private_posts_if_friends_or_own_post(): void
    {
        $privateOwner = User::factory()->create([
            'username' => 'priv_owner_' . uniqid(),
            'school_id' => 1,
            'is_private' => true,
        ]);
        $privateOwner->hobbies()->attach([1]);

        $viewer = User::factory()->create([
            'username' => 'viewer_' . uniqid(),
            'school_id' => 1,
            'is_private' => false,
        ]);
        $viewer->hobbies()->attach([1]);

        $ownerPost = Post::create([
            'user_id' => $privateOwner->id,
            'content' => 'Kisah Privat Khusus Teman ' . uniqid(),
        ]);

        $viewerPost = Post::create([
            'user_id' => $viewer->id,
            'content' => 'Kisah Pribadi Milik Viewer ' . uniqid(),
        ]);

        // 1. Viewer is logged in, NOT friends with privateOwner
        $this->actingAs($viewer);
        $feedRes = $this->get('/feed');
        $feedRes->assertStatus(200);
        $feedRes->assertSee($viewerPost->content);
        $feedRes->assertDontSee($ownerPost->content);

        // Search by non-friend viewer
        $searchRes = $this->get('/feed?q=' . urlencode('Kisah Privat Khusus Teman'));
        $searchRes->assertDontSee($ownerPost->content);

        // 2. Private owner can see their own post
        $this->actingAs($privateOwner);
        $ownerFeed = $this->get('/feed');
        $ownerFeed->assertSee($ownerPost->content);

        // 3. Make viewer and privateOwner friends
        MatchRequest::create([
            'sender_id' => $viewer->id,
            'receiver_id' => $privateOwner->id,
            'status' => 'accepted',
        ]);

        // Now viewer CAN see privateOwner's post!
        $this->actingAs($viewer);
        $friendFeed = $this->get('/feed');
        $friendFeed->assertSee($ownerPost->content);

        $friendSearch = $this->get('/feed?q=' . urlencode('Kisah Privat Khusus Teman'));
        $friendSearch->assertSee($ownerPost->content);

        // Clean up
        MatchRequest::where('sender_id', $viewer->id)->where('receiver_id', $privateOwner->id)->delete();
        $ownerPost->delete();
        $viewerPost->delete();
        $privateOwner->delete();
        $viewer->delete();
    }

    public function test_locked_profile_view_for_guest_and_non_friends(): void
    {
        $privateUser = User::factory()->create([
            'name' => 'Budi Privat',
            'username' => 'budiprivat_' . uniqid(),
            'school_id' => 1,
            'bio' => 'Ini bio privat saya',
            'is_private' => true,
        ]);
        $privateUser->hobbies()->attach([1]);

        $post = Post::create([
            'user_id' => $privateUser->id,
            'content' => 'Postingan tersembunyi budi ' . uniqid(),
        ]);

        // A. Guest visits private profile
        $guestRes = $this->get(route('profile.show', $privateUser->username));
        $guestRes->assertStatus(200);
        // Header info is visible
        $guestRes->assertSee('Budi Privat');
        $guestRes->assertSee($privateUser->username);
        $guestRes->assertSee('Ini bio privat saya');
        // Posts are hidden and locked container is shown
        $guestRes->assertDontSee($post->content);
        $guestRes->assertSee('Akun Ini Bersifat Privat');
        $guestRes->assertSee('Bertemanlah dengan pengguna ini untuk melihat cerita dan kirimannya di Sirkelku.');
        // Connect button links guest to login
        $guestRes->assertSee(route('login'));

        // B. Non-friend logged-in user visits private profile
        $stranger = User::factory()->create([
            'username' => 'stranger_' . uniqid(),
            'school_id' => 1,
            'is_private' => false,
        ]);
        $stranger->hobbies()->attach([1]);

        $this->actingAs($stranger);
        $strangerRes = $this->get(route('profile.show', $privateUser->username));
        $strangerRes->assertStatus(200);
        $strangerRes->assertSee('Budi Privat');
        $strangerRes->assertDontSee($post->content);
        $strangerRes->assertSee('Akun Ini Bersifat Privat');
        $strangerRes->assertSee('+ Berteman');
        $strangerRes->assertDontSee('Ajak Main');
        $strangerRes->assertDontSee('Kirim Pesan');

        // Also test public user profile for stranger (should have + Berteman, but NO Kirim Pesan & NO Ajak Main until friends)
        $publicUser = User::factory()->create([
            'name' => 'Siti Publik',
            'username' => 'sitipublik_' . uniqid(),
            'school_id' => 1,
            'is_private' => false,
        ]);
        $publicUser->hobbies()->attach([1]);

        $pubRes = $this->get(route('profile.show', $publicUser->username));
        $pubRes->assertStatus(200);
        $pubRes->assertSee('+ Berteman');
        $pubRes->assertDontSee('Kirim Pesan');
        $pubRes->assertDontSee('Ajak Main');

        // C. Become friends: stranger accepted as friend with privateUser
        MatchRequest::create([
            'sender_id' => $privateUser->id,
            'receiver_id' => $stranger->id,
            'status' => 'accepted',
        ]);

        $friendRes = $this->get(route('profile.show', $privateUser->username));
        $friendRes->assertStatus(200);
        $friendRes->assertDontSee('Akun Ini Bersifat Privat');
        $friendRes->assertSee($post->content);
        $friendRes->assertSee('Kirim Pesan');
        $friendRes->assertSee('Ajak Main');
        $friendRes->assertDontSee('+ Berteman');

        // D. Owner viewing their own profile
        $this->actingAs($privateUser);
        $ownerRes = $this->get(route('profile.show', $privateUser->username));
        $ownerRes->assertStatus(200);
        $ownerRes->assertDontSee('Akun Ini Bersifat Privat');
        $ownerRes->assertSee($post->content);
        $ownerRes->assertSee('Edit Profil');

        // Clean up
        $publicUser->delete();

        // Clean up
        MatchRequest::where('sender_id', $privateUser->id)->where('receiver_id', $stranger->id)->delete();
        $post->delete();
        $privateUser->delete();
        $stranger->delete();
    }

    public function test_direct_post_access_protection_for_private_posts(): void
    {
        $privateUser = User::factory()->create([
            'username' => 'author_priv_' . uniqid(),
            'school_id' => 1,
            'is_private' => true,
        ]);
        $privateUser->hobbies()->attach([1]);

        $post = Post::create([
            'user_id' => $privateUser->id,
            'content' => 'Postingan Direct Secret ' . uniqid(),
        ]);

        $otherUser = User::factory()->create([
            'username' => 'other_user_' . uniqid(),
            'school_id' => 1,
            'is_private' => false,
        ]);
        $otherUser->hobbies()->attach([1]);

        // 1. Guest visits direct post URL -> redirected to feed with error
        $guestRes = $this->get(route('posts.show', $post->id));
        $guestRes->assertRedirect(route('feed.index'));
        $guestRes->assertSessionHas('error');

        // 2. Non-friend visits direct post URL -> redirected to feed with error
        $this->actingAs($otherUser);
        $userRes = $this->get(route('posts.show', $post->id));
        $userRes->assertRedirect(route('feed.index'));
        $userRes->assertSessionHas('error');

        // 3. Non-friend attempts to like -> 403 Forbidden
        $likeRes = $this->postJson(route('posts.like', $post->id));
        $likeRes->assertStatus(403);

        // 4. Non-friend attempts to comment -> 403 Forbidden
        $commentRes = $this->post(route('posts.comment', $post->id), [
            'comment_text' => 'Komentar intip rahasia',
        ]);
        $commentRes->assertStatus(403);

        // 5. Author can access their own post
        $this->actingAs($privateUser);
        $authorRes = $this->get(route('posts.show', $post->id));
        $authorRes->assertRedirect(route('feed.index') . '#post-' . $post->id);

        // 6. Friend can access the post
        MatchRequest::create([
            'sender_id' => $otherUser->id,
            'receiver_id' => $privateUser->id,
            'status' => 'accepted',
        ]);

        $this->actingAs($otherUser);
        $friendRes = $this->get(route('posts.show', $post->id));
        $friendRes->assertRedirect(route('feed.index') . '#post-' . $post->id);

        // Clean up
        MatchRequest::where('sender_id', $otherUser->id)->where('receiver_id', $privateUser->id)->delete();
        $post->delete();
        $privateUser->delete();
        $otherUser->delete();
    }

    public function test_friend_request_flow_creates_proper_pertemanan_notifications_and_unlocks_profile(): void
    {
        $userA = User::factory()->create([
            'username' => 'user_a_' . uniqid(),
            'school_id' => 1,
            'is_private' => false,
        ]);
        $userA->hobbies()->attach([1]);

        $userB = User::factory()->create([
            'username' => 'user_b_' . uniqid(),
            'school_id' => 1,
            'is_private' => true,
        ]);
        $userB->hobbies()->attach([1]);

        // 1. User A views User B's profile: sees + Berteman (not friends yet)
        $this->actingAs($userA);
        $res = $this->get(route('profile.show', $userB->username));
        $res->assertStatus(200);
        $res->assertSee('+ Berteman');
        $res->assertDontSee('Kirim Pesan');

        // 2. User A clicks + Berteman
        $reqRes = $this->post(route('matchmaking.request'), [
            'receiver_id' => $userB->id,
            'type' => 'friend',
        ]);
        $reqRes->assertSessionHas('success');

        // Assert friend_request notification for User B
        $notif = \App\Models\AppNotification::where('user_id', $userB->id)
            ->where('actor_id', $userA->id)
            ->where('type', 'friend_request')
            ->first();
        $this->assertNotNull($notif);
        $this->assertEquals('Permintaan Pertemanan Baru', $notif->title);
        $this->assertStringContainsString('mengirimkan permintaan pertemanan kepadamu', $notif->message);

        // 3. User B visits incoming requests / notifications and accepts
        $matchReq = MatchRequest::where('sender_id', $userA->id)->where('receiver_id', $userB->id)->first();
        $this->assertNotNull($matchReq);
        $this->assertEquals('pending', $matchReq->status);

        $this->actingAs($userB);
        $acceptRes = $this->post(route('matchmaking.respond', $matchReq->id), [
            'action' => 'accept',
        ]);
        $acceptRes->assertSessionHas('success');

        // Assert friend_accepted notification for User A
        $acceptNotif = \App\Models\AppNotification::where('user_id', $userA->id)
            ->where('actor_id', $userB->id)
            ->where('type', 'friend_accepted')
            ->first();
        $this->assertNotNull($acceptNotif);
        $this->assertEquals('Permintaan Pertemanan Diterima', $acceptNotif->title);

        // 4. They are now friends
        $this->assertTrue($userA->isFriendsWith($userB->id));
        $this->assertTrue($userB->isFriendsWith($userA->id));

        // 5. User A visits User B profile now: Kirim Pesan and Ajak Main are available
        $this->actingAs($userA);
        $friendProfileRes = $this->get(route('profile.show', $userB->username));
        $friendProfileRes->assertStatus(200);
        $friendProfileRes->assertSee('Kirim Pesan');
        $friendProfileRes->assertSee('Ajak Main');
        $friendProfileRes->assertDontSee('+ Berteman');

        // Clean up
        $matchReq->delete();
        $userA->delete();
        $userB->delete();
    }
}

