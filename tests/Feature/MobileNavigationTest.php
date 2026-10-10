<?php

namespace Tests\Feature;

use App\Models\Hobby;
use App\Models\School;
use App\Models\User;
use Tests\TestCase;

class MobileNavigationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        if (User::count() === 0) {
            $this->seed(\Database\Seeders\SirkelkuSeeder::class);
        }
    }

    public function test_mobile_header_and_drawer_render_correctly(): void
    {
        $school = School::first() ?? School::create(['school_name' => 'SMA Test', 'city' => 'Jakarta']);
        $hobby = Hobby::first() ?? Hobby::create(['name' => 'Gaming', 'category' => 'Gaming']);

        $user = User::first();
        $user->school_id = $school->id;
        $user->save();
        if (!$user->hobbies()->exists()) {
            $user->hobbies()->attach($hobby->id);
        }

        $response = $this->actingAs($user)->get('/feed');
        $response->assertStatus(200);

        $content = $response->getContent();

        // 1. Mobile Top Header checks
        $this->assertStringContainsString('md:hidden sticky top-0 z-30 h-14 bg-white border-b border-slate-200', $content);
        $this->assertStringContainsString('aria-label="Buka menu navigasi"', $content);
        $this->assertStringContainsString('sirkelku', $content);
        $this->assertStringContainsString('aria-label="Cari sirkel atau topik"', $content);
        $this->assertStringContainsString('aria-label="Profil Saya"', $content);
        $this->assertStringContainsString($user->avatar_url, $content);
        $this->assertStringContainsString('Cari sirkel, hobi, topik...', $content);

        // 2. Navigation Drawer checks
        $this->assertStringContainsString('role="dialog"', $content);
        $this->assertStringContainsString('aria-label="Menu Navigasi Mobile"', $content);
        $this->assertStringContainsString('aria-label="Tutup menu navigasi"', $content);
        $this->assertStringContainsString($user->name, $content);
        $this->assertStringContainsString('@' . $user->username, $content);
        $this->assertStringContainsString($school->school_name, $content);
        $this->assertStringContainsString('Cari sirkel atau topik...', $content);

        // 3. Navigation items in Drawer
        $this->assertStringContainsString('Nongkrong Yuk', $content);
        $this->assertStringContainsString('Satu Sirkel', $content);
        $this->assertStringContainsString('Tongkrongan.id', $content);
        $this->assertStringContainsString('Teman Main', $content);
        $this->assertStringContainsString('Pesan', $content);
        $this->assertStringContainsString('Notifikasi', $content);
        $this->assertStringContainsString('Profil Saya', $content);

        // 4. Logout button in Drawer
        $this->assertStringContainsString('Keluar', $content);
        $this->assertStringContainsString(route('logout'), $content);

        // 5. Timeline sticky header checks
        $this->assertStringContainsString('sticky top-14 md:top-0 z-20', $content);

        // 6. Layer order checks: bottom nav z-40 and drawer/overlay z-50
        $this->assertStringContainsString('z-40 bg-white/95', $content);
        $this->assertStringContainsString('fixed inset-0 z-50 bg-black/40', $content);
        $this->assertStringContainsString('fixed inset-y-0 left-0 z-50', $content);
    }
}
