<?php

namespace Tests\Feature;

use App\Models\Community;
use App\Models\Hobby;
use App\Models\School;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SirkelkuTest extends TestCase
{
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function test_login_page_renders(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Sirkelku');
    }

    public function test_user_can_login_and_access_feed(): void
    {
        $user = User::where('username', 'dika_gaming')->first();
        $this->assertNotNull($user);

        $response = $this->actingAs($user)->get('/feed');
        $response->assertStatus(200);
        $response->assertSee('Nongkrong Yuk');
        $response->assertSee('Sirkel Valorant Pelajar Jakarta');
    }

    public function test_user_can_view_community_directory_and_detail(): void
    {
        $user = User::where('username', 'dika_gaming')->first();
        $response = $this->actingAs($user)->get('/sirkel');
        $response->assertStatus(200);
        $response->assertSee('Direktori Satu Sirkel');

        $community = Community::where('slug', 'valorant-pelajar-jkt')->first();
        $this->assertNotNull($community);

        $response = $this->actingAs($user)->get('/sirkel/' . $community->slug);
        $response->assertStatus(200);
        $response->assertSee($community->name);
        $response->assertSee('Ketua Sirkel');
    }

    public function test_user_can_view_forum_and_threads(): void
    {
        $user = User::where('username', 'dika_gaming')->first();
        $response = $this->actingAs($user)->get('/forum');
        $response->assertStatus(200);
        $response->assertSee('Tongkrongan.id');

        $thread = Thread::first();
        $this->assertNotNull($thread);

        $response = $this->actingAs($user)->get('/forum/' . $thread->id);
        $response->assertStatus(200);
        $response->assertSee($thread->title);
    }

    public function test_user_can_view_matchmaking(): void
    {
        $user = User::where('username', 'dika_gaming')->first();
        $response = $this->actingAs($user)->get('/teman-main');
        $response->assertStatus(200);
        $response->assertSee('Teman Main');
        $response->assertSee('Ajakan Masuk');
    }

    public function test_user_can_view_profile(): void
    {
        $user = User::where('username', 'dika_gaming')->first();
        $response = $this->actingAs($user)->get('/profile/dika_gaming');
        $response->assertStatus(200);
        $response->assertSee('Andhika Pratama');
    }

    public function test_user_can_create_post_like_and_comment(): void
    {
        $user = User::where('username', 'dika_gaming')->first();

        // 1. Create Post
        $response = $this->actingAs($user)->post('/posts', [
            'content' => 'Halo kawan-kawan Sirkelku, ini postingan uji coba!',
        ]);
        $response->assertSessionHas('success');

        $post = \App\Models\Post::where('content', 'Halo kawan-kawan Sirkelku, ini postingan uji coba!')->first();
        $this->assertNotNull($post);

        // 2. Like Post
        $response = $this->actingAs($user)->postJson('/posts/' . $post->id . '/like');
        $response->assertStatus(200);
        $response->assertJson(['status' => 'success', 'isLiked' => true]);

        // 3. Comment Post
        $response = $this->actingAs($user)->post('/posts/' . $post->id . '/comment', [
            'comment_text' => 'Komentar pengujian interaktif!',
        ]);
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('post_comments', [
            'post_id' => $post->id,
            'comment_text' => 'Komentar pengujian interaktif!',
        ]);
    }

    public function test_user_can_create_thread_and_nested_reply(): void
    {
        $user = User::where('username', 'dika_gaming')->first();
        $hobby = Hobby::first();

        // 1. Create Thread
        $response = $this->actingAs($user)->post('/forum', [
            'title' => 'Topik Uji Coba Forum Tongkrongan',
            'body' => 'Bahas hal seru yuk seputar hobi!',
            'hobby_id' => $hobby->id,
        ]);
        $response->assertSessionHas('success');

        $thread = Thread::where('title', 'Topik Uji Coba Forum Tongkrongan')->first();
        $this->assertNotNull($thread);

        // 2. Root Comment
        $response = $this->actingAs($user)->post('/forum/' . $thread->id . '/comment', [
            'comment_text' => 'Ini tanggapan level satu.',
        ]);
        $response->assertSessionHas('success');

        $rootComment = \App\Models\ThreadComment::where('thread_id', $thread->id)->first();
        $this->assertNotNull($rootComment);

        // 3. Nested Reply
        $response = $this->actingAs($user)->post('/forum/' . $thread->id . '/comment', [
            'comment_text' => 'Ini balasan bersarang (nested reply).',
            'parent_id' => $rootComment->id,
        ]);
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('thread_comments', [
            'parent_id' => $rootComment->id,
            'comment_text' => 'Ini balasan bersarang (nested reply).',
        ]);
    }

    public function test_user_can_send_and_accept_match_request(): void
    {
        $sender = User::where('username', 'clara_melodi')->first();
        $receiver = User::where('username', 'keiko_art')->first();

        // 1. Send Request
        $response = $this->actingAs($sender)->post('/teman-main/request', [
            'receiver_id' => $receiver->id,
            'note' => 'Ajak jamming bareng yuk!',
        ]);
        $response->assertSessionHas('success');

        $req = \App\Models\MatchRequest::where('sender_id', $sender->id)
            ->where('receiver_id', $receiver->id)
            ->first();
        $this->assertNotNull($req);
        $this->assertEquals('pending', $req->status);

        // 2. Accept Request
        $response = $this->actingAs($receiver)->post('/teman-main/requests/' . $req->id . '/respond', [
            'action' => 'accept',
        ]);
        $response->assertSessionHas('success');
        $this->assertEquals('accepted', $req->fresh()->status);
    }

    public function test_user_can_view_and_mark_notifications(): void
    {
        $user = User::where('username', 'dika_gaming')->first();

        $response = $this->actingAs($user)->get('/notifications');
        $response->assertStatus(200);
        $response->assertSee('Pusat Notifikasi');

        $response = $this->actingAs($user)->post('/notifications/mark-read');
        $response->assertSessionHas('success');
    }
}
