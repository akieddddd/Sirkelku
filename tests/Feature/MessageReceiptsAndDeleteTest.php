<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageReceiptsAndDeleteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (User::count() === 0) {
            $this->seed();
        }
    }

    public function test_read_receipts_single_and_double_check_flow(): void
    {
        $sender = User::where('username', 'dika_gaming')->first();
        $receiver = User::where('username', 'clara_melodi')->first();

        // Sender sends a message
        $response = $this->actingAs($sender)->postJson('/messages/' . $receiver->username, [
            'content' => 'Halo tes centang',
        ]);
        $response->assertStatus(200);
        $msgId = $response->json('id');

        $message = Message::find($msgId);
        $this->assertNull($message->read_at);

        // Sender views chat room: message is unread, so read_at is still null (single check)
        $viewSender = $this->actingAs($sender)->get('/messages/' . $receiver->username);
        $viewSender->assertStatus(200);
        $viewSender->assertSee('Terkirim'); // title attribute for single checkmark

        // Receiver syncs or opens the chat room
        $syncReceiver = $this->actingAs($receiver)->getJson('/messages/' . $sender->username . '/sync?after_id=0');
        $syncReceiver->assertStatus(200);

        // Verify message is now read
        $message->refresh();
        $this->assertNotNull($message->read_at);

        // Sender syncs: returns msgId in read_ids, but NOT in deleted_for_everyone_ids
        $syncSender = $this->actingAs($sender)->getJson('/messages/' . $receiver->username . '/sync?after_id=0');
        $syncSender->assertStatus(200);
        $this->assertContains($msgId, $syncSender->json('read_ids'));
        $this->assertNotContains($msgId, $syncSender->json('deleted_for_everyone_ids'));

        // Sender views chat room: now sees double check (Dibaca)
        $viewSenderAfter = $this->actingAs($sender)->get('/messages/' . $receiver->username);
        $viewSenderAfter->assertStatus(200);
        $viewSenderAfter->assertSee('Dibaca'); // title attribute for double checkmark
    }

    public function test_delete_message_for_me(): void
    {
        $sender = User::where('username', 'dika_gaming')->first();
        $receiver = User::where('username', 'keiko_art')->first();

        $message = Message::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'content' => 'Pesan rahasia mau dihapus untuk saya',
        ]);

        // Sender deletes for me
        $response = $this->actingAs($sender)->deleteJson('/messages/' . $message->id, [
            'delete_type' => 'for_me',
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'delete_type' => 'for_me',
        ]);

        $message->refresh();
        $this->assertTrue($message->deleted_by_sender);
        $this->assertFalse($message->deleted_by_receiver);

        // Sender show view does NOT show this message
        $senderView = $this->actingAs($sender)->get('/messages/' . $receiver->username);
        $senderView->assertDontSee('Pesan rahasia mau dihapus untuk saya');

        // Receiver CAN still see the message
        $receiverView = $this->actingAs($receiver)->get('/messages/' . $sender->username);
        $receiverView->assertSee('Pesan rahasia mau dihapus untuk saya');
    }

    public function test_delete_message_for_everyone(): void
    {
        $sender = User::where('username', 'dika_gaming')->first();
        $receiver = User::where('username', 'keiko_art')->first();

        $message = Message::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'content' => 'Pesan salah ketik mau ditarik untuk semua',
        ]);

        // Receiver tries to delete for everyone (should fail 403)
        $forbiddenResponse = $this->actingAs($receiver)->deleteJson('/messages/' . $message->id, [
            'delete_type' => 'for_everyone',
        ]);
        $forbiddenResponse->assertStatus(403);

        // Sender deletes for everyone
        $response = $this->actingAs($sender)->deleteJson('/messages/' . $message->id, [
            'delete_type' => 'for_everyone',
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'delete_type' => 'for_everyone',
        ]);

        $message->refresh();
        $this->assertTrue($message->deleted_for_everyone);
        $this->assertEquals('Pesan ini telah dihapus', $message->content);

        // Both sender and receiver views show "Pesan ini telah dihapus"
        $senderView = $this->actingAs($sender)->get('/messages/' . $receiver->username);
        $senderView->assertSee('Pesan ini telah dihapus');
        $senderView->assertDontSee('Pesan salah ketik mau ditarik untuk semua');

        $receiverView = $this->actingAs($receiver)->get('/messages/' . $sender->username);
        $receiverView->assertSee('Pesan ini telah dihapus');
        $receiverView->assertDontSee('Pesan salah ketik mau ditarik untuk semua');

        // Sync for receiver returns the deleted message id in deleted_for_everyone_ids
        $syncReceiver = $this->actingAs($receiver)->getJson('/messages/' . $sender->username . '/sync?after_id=0');
        $syncReceiver->assertStatus(200);
        $this->assertContains($message->id, $syncReceiver->json('deleted_for_everyone_ids'));
    }
}
