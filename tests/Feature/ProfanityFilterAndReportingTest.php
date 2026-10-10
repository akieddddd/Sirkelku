<?php

namespace Tests\Feature;

use App\Models\Community;
use App\Models\Hobby;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostReport;
use App\Models\Thread;
use App\Models\ThreadComment;
use App\Models\User;
use App\Services\ProfanityFilter;
use Tests\TestCase;

class ProfanityFilterAndReportingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        if (User::count() === 0) {
            $this->seed(\Database\Seeders\SirkelkuSeeder::class);
        }
    }

    /**
     * Test Unit ProfanityFilter service.
     */
    public function test_profanity_filter_censors_bad_words_accurately(): void
    {
        $this->assertEquals('Dasar a****g kau!', ProfanityFilter::censor('Dasar anjing kau!'));
        $this->assertEquals('b**i dan b*****t', ProfanityFilter::censor('babi dan bangsat'));
        $this->assertEquals('k****l dan m***k', ProfanityFilter::censor('kontol dan memek'));
        $this->assertEquals('t***l dan g****k', ProfanityFilter::censor('tolol dan goblok'));
        $this->assertEquals('A****G', ProfanityFilter::censor('ANJING'));
        
        // Null and empty string handling
        $this->assertNull(ProfanityFilter::censor(null));
        $this->assertSame('', ProfanityFilter::censor(''));

        // Harmless words must NOT be censored
        $this->assertEquals('liburan di pantai makan cokelat', ProfanityFilter::censor('liburan di pantai makan cokelat'));
    }

    /**
     * Test FeedController stores censored post.
     */
    public function test_post_creation_is_censored(): void
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->post(route('posts.store'), [
            'content' => 'Halo kawan anjing lu semua bangsat',
        ]);

        $response->assertSessionHasNoErrors();

        $post = Post::where('user_id', $user->id)->latest('id')->first();
        $this->assertNotNull($post);
        $this->assertStringContainsString('a****g', $post->content);
        $this->assertStringContainsString('b*****t', $post->content);
        $this->assertStringNotContainsString('anjing', strtolower($post->content));
        $this->assertStringNotContainsString('bangsat', strtolower($post->content));
    }

    /**
     * Test post comment is censored.
     */
    public function test_post_comment_is_censored(): void
    {
        $user = User::first();
        $post = Post::first();
        $this->actingAs($user);

        $response = $this->post(route('posts.comment', $post->id), [
            'comment_text' => 'Komentar tolol banget kamu goblok',
        ]);

        $response->assertSessionHasNoErrors();

        $comment = PostComment::where('post_id', $post->id)->where('user_id', $user->id)->latest('id')->first();
        $this->assertNotNull($comment);
        $this->assertStringContainsString('t***l', $comment->comment_text);
        $this->assertStringContainsString('g****k', $comment->comment_text);
    }

    /**
     * Test ThreadController stores censored thread and comment.
     */
    public function test_thread_and_thread_comment_are_censored(): void
    {
        $user = User::first();
        $hobby = Hobby::first();
        $this->actingAs($user);

        $response = $this->post(route('threads.store'), [
            'title' => 'Topik anjing ini sangat babi',
            'body' => 'Isi thread dasar bajingan kontol',
            'hobby_id' => $hobby->id,
        ]);

        $response->assertSessionHasNoErrors();

        $thread = Thread::where('user_id', $user->id)->latest('id')->first();
        $this->assertNotNull($thread);
        $this->assertStringContainsString('a****g', $thread->title);
        $this->assertStringContainsString('b**i', $thread->title);
        $this->assertStringContainsString('b******n', $thread->body);
        $this->assertStringContainsString('k****l', $thread->body);

        // Comment
        $commentResponse = $this->post(route('threads.comment', $thread->id), [
            'comment_text' => 'Balasan memek ngentot kamu',
        ]);
        $commentResponse->assertSessionHasNoErrors();

        $comment = ThreadComment::where('thread_id', $thread->id)->latest('id')->first();
        $this->assertNotNull($comment);
        $this->assertStringContainsString('m***k', $comment->comment_text);
        $this->assertStringContainsString('n*****t', $comment->comment_text);
    }

    /**
     * Test MessageController censors DM content.
     */
    public function test_message_dm_is_censored(): void
    {
        $users = User::take(2)->get();
        $sender = $users[0];
        $receiver = $users[1];

        $this->actingAs($sender);

        $response = $this->post(route('messages.store', $receiver->username), [
            'content' => 'Pesan rahasia anjing kau tolol',
        ]);

        $response->assertSessionHasNoErrors();

        $msg = \App\Models\Message::where('sender_id', $sender->id)
            ->where('receiver_id', $receiver->id)
            ->latest('id')
            ->first();

        $this->assertNotNull($msg);
        $this->assertStringContainsString('a****g', $msg->content);
        $this->assertStringContainsString('t***l', $msg->content);
    }

    /**
     * Test reporting post and auto-flagging threshold.
     */
    public function test_post_reporting_and_auto_flagging_mechanism(): void
    {
        $school = \App\Models\School::first() ?? \App\Models\School::create(['school_name' => 'SMA Test', 'city' => 'Jakarta']);
        $hobby = \App\Models\Hobby::first() ?? \App\Models\Hobby::create(['name' => 'Gaming', 'category' => 'Gaming']);

        $author = User::first();
        $author->school_id = $school->id;
        $author->save();
        if (!$author->hobbies()->exists()) {
            $author->hobbies()->attach($hobby->id);
        }

        PostReport::query()->delete();

        $post = Post::create([
            'user_id' => $author->id,
            'content' => 'Postingan uji pelaporan komunitas',
            'is_flagged' => false,
            'reports_count' => 0,
        ]);

        $reporters = User::where('id', '!=', $author->id)->take(2)->get();
        foreach ($reporters as $reporter) {
            $reporter->school_id = $school->id;
            $reporter->save();
            if (!$reporter->hobbies()->exists()) {
                $reporter->hobbies()->attach($hobby->id);
            }
        }
        $this->assertCount(2, $reporters);

        // Reporter 1 reports
        $this->actingAs($reporters[0]);
        $resp1 = $this->from('/feed')->post(route('posts.report', $post->id), [
            'reason' => 'Kata-kata kasar atau penghinaan',
        ]);
        $resp1->assertSessionHas('success');

        $post->refresh();
        $this->assertEquals(1, $post->reports_count);
        $this->assertFalse((bool) $post->is_flagged);

        // Reporter 1 tries to spam report again -> must fail
        $respDuplicate = $this->from('/feed')->post(route('posts.report', $post->id), [
            'reason' => 'Spam berulang',
        ]);
        $respDuplicate->assertSessionHas('error');
        $this->assertEquals(1, $post->fresh()->reports_count);

        // Reporter 2 reports -> threshold 2 reached -> auto flag = true
        $this->actingAs($reporters[1]);
        $resp2 = $this->from('/feed')->post(route('posts.report', $post->id), [
            'reason' => 'Konten tidak pantas / SARA / Dewasa',
        ]);
        $resp2->assertSessionHas('success');

        $post->refresh();
        $this->assertEquals(2, $post->reports_count);
        $this->assertTrue((bool) $post->is_flagged);

        // Clean up
        $post->reports()->delete();
        $post->delete();
    }
}
