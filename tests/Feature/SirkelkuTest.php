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
    protected function setUp(): void
    {
        parent::setUp();
        if (User::count() === 0) {
            $this->seed(\Database\Seeders\SirkelkuSeeder::class);
        }
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
        try {
            $host = env('DB_HOST', '127.0.0.1');
            $port = env('DB_PORT', '3306');
            $db   = env('DB_DATABASE', 'sirkelku');
            $user = env('DB_USERNAME', 'root');
            $pass = env('DB_PASSWORD', '');
            $pdo  = new \PDO("mysql:host={$host};port={$port};dbname={$db}", $user, $pass);
            $pdo->exec('SET FOREIGN_KEY_CHECKS = 0;');
            $tables = [
                'app_notifications', 'messages', 'match_requests',
                'thread_comments', 'threads', 'post_comments',
                'post_likes', 'posts', 'community_members',
                'communities', 'user_hobbies', 'users'
            ];
            foreach ($tables as $table) {
                $pdo->exec("TRUNCATE TABLE `{$table}`;");
            }
            $pdo->exec('DELETE FROM `hobbies` WHERE `id` > 19;');
            $pdo->exec('DELETE FROM `schools` WHERE `id` > 10;');
            $pdo->exec('SET FOREIGN_KEY_CHECKS = 1;');
        } catch (\Throwable $e) {
            // Silently ignore if connection cannot be made statically
        }
    }

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
        $response->assertSee('togglePasswordBtn');
        $response->assertSee('eyeOpenIcon');
        $response->assertSee('eyeClosedIcon');
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
        $response->assertSee($user->name);
    }

    public function test_user_can_create_post_like_and_comment(): void
    {
        $user = User::where('username', 'dika_gaming')->first();

        // 1. Create Post
        $content = 'Halo kawan-kawan Sirkelku, ini postingan uji coba ' . uniqid() . '!';
        $response = $this->actingAs($user)->post('/posts', [
            'content' => $content,
        ]);
        $response->assertSessionHas('success');

        $post = \App\Models\Post::where('content', $content)->latest('id')->first();
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

    public function test_user_can_create_post_with_image(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');
        $user = User::where('username', 'dika_gaming')->first();

        $file = \Illuminate\Http\UploadedFile::fake()->image('gambar_mabar.jpg', 800, 600);

        $response = $this->actingAs($user)->post('/posts', [
            'content' => 'Mabar santai sore dengan screenshot game!',
            'image' => $file,
        ]);

        $response->assertSessionHas('success');

        $post = \App\Models\Post::where('content', 'Mabar santai sore dengan screenshot game!')->latest('id')->first();
        $this->assertNotNull($post);
        $this->assertNotNull($post->image_path);

        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($post->image_path);
        $this->assertNotNull($post->image_url);

        // Also test posting photo without caption
        $file2 = \Illuminate\Http\UploadedFile::fake()->image('foto_saja.jpg', 600, 600);
        $response2 = $this->actingAs($user)->post('/posts', [
            'image' => $file2,
        ]);
        $response2->assertSessionHas('success');
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

        // Clear prior state
        \App\Models\MatchRequest::where('sender_id', $sender->id)
            ->where('receiver_id', $receiver->id)
            ->delete();

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

    public function test_realtime_chat_messaging_notifications_and_sync(): void
    {
        $sender = User::where('username', 'dika_gaming')->first();
        $receiver = User::where('username', 'clara_melodi')->first();

        // 1. Send chat message via JSON/AJAX
        $response = $this->actingAs($sender)->postJson('/messages/' . $receiver->username, [
            'content' => 'Halo Clara, ayo mabar Valorant nanti malam!',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'id',
            'content',
            'sender_id',
            'created_at_iso',
        ]);

        $messageId = $response->json('id');

        // Verify AppNotification was automatically created for Clara
        $this->assertDatabaseHas('app_notifications', [
            'user_id' => $receiver->id,
            'actor_id' => $sender->id,
            'type' => 'message',
            'is_read' => false,
        ]);

        // 2. Clara receives incoming message in checkIncoming poller
        $checkResponse = $this->actingAs($receiver)->getJson('/messages/check-incoming');
        $checkResponse->assertStatus(200);
        $this->assertTrue($checkResponse->json('unread_messages_count') >= 1);
        $this->assertTrue($checkResponse->json('unread_notifications_count') >= 1);

        // 3. Clara opens or syncs the chat room
        $syncResponse = $this->actingAs($receiver)->getJson('/messages/' . $sender->username . '/sync?after_id=0');
        $syncResponse->assertStatus(200);
        $syncResponse->assertJsonFragment([
            'id' => $messageId,
            'content' => 'Halo Clara, ayo mabar Valorant nanti malam!',
        ]);

        // Verify message is now marked as read
        $this->assertNotNull(\App\Models\Message::find($messageId)->read_at);
    }
}

