<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileSettingsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        if (User::count() === 0) {
            $this->seed(\Database\Seeders\SirkelkuSeeder::class);
        }
    }

    public function test_user_can_update_credentials(): void
    {
        $user = User::factory()->create([
            'username' => 'testcred_' . uniqid(),
            'password' => 'password123',
            'school_id' => 1,
        ]);
        $user->hobbies()->attach([1]);

        $this->actingAs($user);

        $newUsername = 'testuser_' . uniqid();
        $newEmail = 'testuser_' . uniqid() . '@example.com';

        $response = $this->put(route('profile.credentials.update'), [
            'username' => $newUsername,
            'email' => $newEmail,
        ]);

        $response->assertSessionHas('success');
        $response->assertRedirect(route('profile.edit'));

        $user->refresh();
        $this->assertEquals($newUsername, $user->username);
        $this->assertEquals($newEmail, $user->email);

        // Clean up
        $user->delete();
    }

    public function test_user_cannot_update_credentials_with_duplicate(): void
    {
        $users = User::take(2)->get();
        $user1 = $users[0];
        $user2 = $users[1];

        $this->actingAs($user1);

        $response = $this->put(route('profile.credentials.update'), [
            'username' => $user2->username,
            'email' => $user2->email,
        ]);

        $response->assertSessionHasErrors(['username', 'email']);
    }

    public function test_user_can_update_password(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser_' . uniqid(),
            'password' => 'password123',
            'school_id' => 1,
        ]);
        $user->hobbies()->attach([1]);

        $this->actingAs($user);

        $response = $this->put(route('profile.password.update'), [
            'current_password' => 'password123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHas('success');
        $response->assertRedirect(route('profile.edit'));

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));

        // Clean up created user
        $user->delete();
    }

    public function test_user_cannot_update_password_with_wrong_current_password(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser_' . uniqid(),
            'password' => 'password123',
            'school_id' => 1,
        ]);
        $user->hobbies()->attach([1]);

        $this->actingAs($user);

        $response = $this->put(route('profile.password.update'), [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors(['current_password']);

        // Clean up created user
        $user->delete();
    }

    public function test_user_cannot_delete_account_with_wrong_password(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser_' . uniqid(),
            'password' => 'password123',
            'school_id' => 1,
        ]);
        $user->hobbies()->attach([1]);

        $this->actingAs($user);

        $response = $this->delete(route('profile.destroy'), [
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('password_delete', null, 'userDeletion');
        $this->assertDatabaseHas('users', ['id' => $user->id]);

        // Clean up
        $user->delete();
    }

    public function test_user_can_delete_account_with_correct_password(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser_' . uniqid(),
            'password' => 'password123',
            'school_id' => 1,
        ]);
        $user->hobbies()->attach([1]);

        $this->actingAs($user);

        $response = $this->delete(route('profile.destroy'), [
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('info');
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
