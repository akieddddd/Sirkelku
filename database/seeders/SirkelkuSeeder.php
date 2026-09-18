<?php

namespace Database\Seeders;

use App\Models\AppNotification;
use App\Models\Community;
use App\Models\CommunityMember;
use App\Models\Hobby;
use App\Models\MatchRequest;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostLike;
use App\Models\School;
use App\Models\Thread;
use App\Models\ThreadComment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SirkelkuSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Schools
        $schoolsData = [
            ['school_name' => 'SMA Negeri 1 Jakarta', 'city' => 'Jakarta'],
            ['school_name' => 'SMA Negeri 3 Bandung', 'city' => 'Bandung'],
            ['school_name' => 'SMA Negeri 5 Surabaya', 'city' => 'Surabaya'],
            ['school_name' => 'SMK Negeri 1 Cimahi', 'city' => 'Cimahi'],
            ['school_name' => 'SMA Kolese Gonzaga', 'city' => 'Jakarta'],
            ['school_name' => 'SMA Taruna Nusantara', 'city' => 'Magelang'],
            ['school_name' => 'SMA Labschool Kebayoran', 'city' => 'Jakarta'],
            ['school_name' => 'SMK Negeri 2 Yogyakarta', 'city' => 'Yogyakarta'],
            ['school_name' => 'SMA Negeri 8 Jakarta', 'city' => 'Jakarta'],
            ['school_name' => 'SMA Negeri 1 Denpasar', 'city' => 'Denpasar'],
        ];

        $schools = [];
        foreach ($schoolsData as $data) {
            $schools[$data['school_name']] = School::create($data);
        }

        // 2. Seed Hobbies
        $hobbiesData = [
            // Gaming
            ['name' => 'Valorant', 'category' => 'Gaming'],
            ['name' => 'Mobile Legends', 'category' => 'Gaming'],
            ['name' => 'Genshin Impact', 'category' => 'Gaming'],
            ['name' => 'Roblox', 'category' => 'Gaming'],
            // Musik
            ['name' => 'Gitar Akustik', 'category' => 'Musik'],
            ['name' => 'Drum & Perkusi', 'category' => 'Musik'],
            ['name' => 'Vocal & Menyanyi', 'category' => 'Musik'],
            ['name' => 'K-Pop Dance', 'category' => 'Musik'],
            // Visual Art
            ['name' => 'Digital Drawing', 'category' => 'Visual Art'],
            ['name' => 'Fotografi', 'category' => 'Visual Art'],
            ['name' => 'Videografi', 'category' => 'Visual Art'],
            ['name' => 'Cosplay & Anime', 'category' => 'Visual Art'],
            // Olahraga
            ['name' => 'Futsal', 'category' => 'Olahraga'],
            ['name' => 'Bola Basket', 'category' => 'Olahraga'],
            ['name' => 'Badminton', 'category' => 'Olahraga'],
            ['name' => 'Skateboard', 'category' => 'Olahraga'],
            // Teknologi
            ['name' => 'Web Development', 'category' => 'Teknologi'],
            ['name' => 'Game Development', 'category' => 'Teknologi'],
            ['name' => 'UI/UX Design', 'category' => 'Teknologi'],
        ];

        $hobbies = [];
        foreach ($hobbiesData as $data) {
            $hobbies[$data['name']] = Hobby::create($data);
        }

        // 3. Seed Users
        $defaultPassword = Hash::make('password123');

        $userDika = User::create([
            'name' => 'Andhika Pratama',
            'username' => 'dika_gaming',
            'email' => 'dika@sirkelku.id',
            'password' => $defaultPassword,
            'school_id' => $schools['SMA Negeri 1 Jakarta']->id,
            'bio' => 'Siswa SMA 1 Jakarta yang hobi push rank Valorant & nge-code santai. Open for mabar!',
            'avatar_path' => 'https://api.dicebear.com/7.x/bottts/svg?seed=dika_gaming',
        ]);
        $userDika->hobbies()->attach([
            $hobbies['Valorant']->id,
            $hobbies['Web Development']->id,
            $hobbies['Futsal']->id,
        ]);

        $userClara = User::create([
            'name' => 'Clara Salsabila',
            'username' => 'clara_melodi',
            'email' => 'clara@sirkelku.id',
            'password' => $defaultPassword,
            'school_id' => $schools['SMA Negeri 3 Bandung']->id,
            'bio' => 'Vocalist band sekolah & penikmat senja Bandung. Suka jamming akustik bareng teman-teman.',
            'avatar_path' => 'https://api.dicebear.com/7.x/bottts/svg?seed=clara_melodi',
        ]);
        $userClara->hobbies()->attach([
            $hobbies['Gitar Akustik']->id,
            $hobbies['Vocal & Menyanyi']->id,
            $hobbies['Fotografi']->id,
        ]);

        $userBima = User::create([
            'name' => 'Bima Wicaksono',
            'username' => 'bima_futsal',
            'email' => 'bima@sirkelku.id',
            'password' => $defaultPassword,
            'school_id' => $schools['SMK Negeri 1 Cimahi']->id,
            'bio' => 'Kapten tim futsal SMKN 1 Cimahi. Selalu siap sparing akhir pekan!',
            'avatar_path' => 'https://api.dicebear.com/7.x/bottts/svg?seed=bima_futsal',
        ]);
        $userBima->hobbies()->attach([
            $hobbies['Futsal']->id,
            $hobbies['Mobile Legends']->id,
            $hobbies['Badminton']->id,
        ]);

        $userKeiko = User::create([
            'name' => 'Keiko Amanda',
            'username' => 'keiko_art',
            'email' => 'keiko@sirkelku.id',
            'password' => $defaultPassword,
            'school_id' => $schools['SMA Labschool Kebayoran']->id,
            'bio' => 'Digital illustrator & casual gamer. Menggambar manga dan karakter orisinal.',
            'avatar_path' => 'https://api.dicebear.com/7.x/bottts/svg?seed=keiko_art',
        ]);
        $userKeiko->hobbies()->attach([
            $hobbies['Digital Drawing']->id,
            $hobbies['Cosplay & Anime']->id,
            $hobbies['Genshin Impact']->id,
        ]);

        $userRaihan = User::create([
            'name' => 'Raihan Saputra',
            'username' => 'raihan_dev',
            'email' => 'raihan@sirkelku.id',
            'password' => $defaultPassword,
            'school_id' => $schools['SMA Kolese Gonzaga']->id,
            'bio' => 'Tech explorer & web enthusiast. Lagi rajin ngulik Laravel 12 dan Tailwind CSS.',
            'avatar_path' => 'https://api.dicebear.com/7.x/bottts/svg?seed=raihan_dev',
        ]);
        $userRaihan->hobbies()->attach([
            $hobbies['Web Development']->id,
            $hobbies['UI/UX Design']->id,
            $hobbies['Valorant']->id,
        ]);

        $userNatasha = User::create([
            'name' => 'Natasha Aurelia',
            'username' => 'natasha_dance',
            'email' => 'natasha@sirkelku.id',
            'password' => $defaultPassword,
            'school_id' => $schools['SMA Negeri 5 Surabaya']->id,
            'bio' => 'K-Pop cover dancer Surabaya & penikmat boba. Let\'s connect and share choreo!',
            'avatar_path' => 'https://api.dicebear.com/7.x/bottts/svg?seed=natasha_dance',
        ]);
        $userNatasha->hobbies()->attach([
            $hobbies['K-Pop Dance']->id,
            $hobbies['Genshin Impact']->id,
            $hobbies['Videografi']->id,
        ]);

        // 4. Seed Communities (Satu Sirkel)
        $commValorant = Community::create([
            'name' => 'Sirkel Valorant Pelajar Jakarta',
            'slug' => 'valorant-pelajar-jkt',
            'description' => 'Komunitas mabar dan turnamen persahabatan Valorant untuk pelajar SMA/SMK se-Jabodetabek. No toxic, all rank welcome!',
            'hobby_id' => $hobbies['Valorant']->id,
            'school_id' => $schools['SMA Negeri 1 Jakarta']->id,
            'banner_path' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=1200&auto=format&fit=crop&q=80',
            'avatar_path' => 'https://api.dicebear.com/7.x/identicon/svg?seed=valorant-pelajar-jkt',
            'created_by' => $userDika->id,
        ]);
        CommunityMember::create(['community_id' => $commValorant->id, 'user_id' => $userDika->id, 'role' => 'admin', 'joined_at' => now()]);
        CommunityMember::create(['community_id' => $commValorant->id, 'user_id' => $userRaihan->id, 'role' => 'member', 'joined_at' => now()]);
        CommunityMember::create(['community_id' => $commValorant->id, 'user_id' => $userBima->id, 'role' => 'member', 'joined_at' => now()]);

        $commIndie = Community::create([
            'name' => 'Bandung Indie Acoustic Club',
            'slug' => 'bandung-indie-acoustic',
            'description' => 'Wadah kumpul dan jamming bareng buat anak-anak SMA Bandung yang suka musik indie, folk, dan fingerstyle akustik.',
            'hobby_id' => $hobbies['Gitar Akustik']->id,
            'school_id' => $schools['SMA Negeri 3 Bandung']->id,
            'banner_path' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=1200&auto=format&fit=crop&q=80',
            'avatar_path' => 'https://api.dicebear.com/7.x/identicon/svg?seed=bandung-indie-acoustic',
            'created_by' => $userClara->id,
        ]);
        CommunityMember::create(['community_id' => $commIndie->id, 'user_id' => $userClara->id, 'role' => 'admin', 'joined_at' => now()]);
        CommunityMember::create(['community_id' => $commIndie->id, 'user_id' => $userNatasha->id, 'role' => 'member', 'joined_at' => now()]);

        $commDevs = Community::create([
            'name' => 'Young Devs Indonesia',
            'slug' => 'young-devs-indonesia',
            'description' => 'Sirkel kolaborasi belajar programming, web development, dan sharing proyek teknologi antar pelajar se-Indonesia.',
            'hobby_id' => $hobbies['Web Development']->id,
            'school_id' => $schools['SMA Kolese Gonzaga']->id,
            'banner_path' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=1200&auto=format&fit=crop&q=80',
            'avatar_path' => 'https://api.dicebear.com/7.x/identicon/svg?seed=young-devs-indonesia',
            'created_by' => $userRaihan->id,
        ]);
        CommunityMember::create(['community_id' => $commDevs->id, 'user_id' => $userRaihan->id, 'role' => 'admin', 'joined_at' => now()]);
        CommunityMember::create(['community_id' => $commDevs->id, 'user_id' => $userDika->id, 'role' => 'member', 'joined_at' => now()]);

        $commArt = Community::create([
            'name' => 'Cosplay & Digital Art Corner',
            'slug' => 'cosplay-art-corner',
            'description' => 'Tempat memamerkan karya ilustrasi digital, tips cosplay low-budget, dan diskusi anime favorit.',
            'hobby_id' => $hobbies['Digital Drawing']->id,
            'school_id' => $schools['SMA Labschool Kebayoran']->id,
            'banner_path' => 'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=1200&auto=format&fit=crop&q=80',
            'avatar_path' => 'https://api.dicebear.com/7.x/identicon/svg?seed=cosplay-art-corner',
            'created_by' => $userKeiko->id,
        ]);
        CommunityMember::create(['community_id' => $commArt->id, 'user_id' => $userKeiko->id, 'role' => 'admin', 'joined_at' => now()]);
        CommunityMember::create(['community_id' => $commArt->id, 'user_id' => $userNatasha->id, 'role' => 'member', 'joined_at' => now()]);

        // 5. Seed Posts (Nongkrong Yuk)
        $post1 = Post::create([
            'user_id' => $userDika->id,
            'community_id' => $commValorant->id,
            'hobby_id' => $hobbies['Valorant']->id,
            'content' => 'Akhirnya nyentuh rank Diamond 2 setelah seminggu grind! Ada yang mau mabar unrated atau competitive malam ini? Gas reply ya!',
            'image_path' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=800&auto=format&fit=crop&q=80',
        ]);
        PostLike::create(['post_id' => $post1->id, 'user_id' => $userRaihan->id]);
        PostLike::create(['post_id' => $post1->id, 'user_id' => $userBima->id]);
        PostComment::create([
            'post_id' => $post1->id,
            'user_id' => $userRaihan->id,
            'comment_text' => 'Gass bro nanti jam 8 malam ane join discord!',
        ]);
        PostComment::create([
            'post_id' => $post1->id,
            'user_id' => $userBima->id,
            'comment_text' => 'Boleh ajak gua gak? Rank Platinum 3 nih siap support.',
        ]);

        $post2 = Post::create([
            'user_id' => $userClara->id,
            'community_id' => $commIndie->id,
            'hobby_id' => $hobbies['Gitar Akustik']->id,
            'content' => 'Lagi iseng cover lagu Fourtwnty pakai petikan fingerstyle santai di taman sekolah. Siapa di sini yang suka musik akustik juga?',
            'image_path' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800&auto=format&fit=crop&q=80',
        ]);
        PostLike::create(['post_id' => $post2->id, 'user_id' => $userNatasha->id]);
        PostLike::create(['post_id' => $post2->id, 'user_id' => $userKeiko->id]);
        PostComment::create([
            'post_id' => $post2->id,
            'user_id' => $userNatasha->id,
            'comment_text' => 'Suaranya adem banget kak Clara! Kapan-kapan collab ya!',
        ]);

        $post3 = Post::create([
            'user_id' => $userKeiko->id,
            'community_id' => $commArt->id,
            'hobby_id' => $hobbies['Digital Drawing']->id,
            'content' => 'Fanart karakter kesukaan selesai digambar 5 jam nonstop pakai Clip Studio Paint. Gimana menurut kalian shading warnanya?',
            'image_path' => 'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=800&auto=format&fit=crop&q=80',
        ]);
        PostLike::create(['post_id' => $post3->id, 'user_id' => $userDika->id]);
        PostLike::create(['post_id' => $post3->id, 'user_id' => $userClara->id]);

        $post4 = Post::create([
            'user_id' => $userBima->id,
            'community_id' => null,
            'hobby_id' => $hobbies['Futsal']->id,
            'content' => 'Ada tim futsal SMA/SMK daerah Cimahi atau Bandung yang mau sparing sabtu sore besok di Lapangan Champion? Slot masih ada 1!',
            'image_path' => null,
        ]);
        PostLike::create(['post_id' => $post4->id, 'user_id' => $userDika->id]);

        // 6. Seed Threads & Nested Comments (Tongkrongan.id)
        $thread1 = Thread::create([
            'community_id' => $commValorant->id,
            'user_id' => $userDika->id,
            'hobby_id' => $hobbies['Valorant']->id,
            'title' => 'Tips Setup Crosshair & Aim Routine Valorant buat Pemula Biar Cepat Naik Rank',
            'body' => "Halo teman-teman semua! Banyak yang nanya di DM gimana cara melatih konsistensi aim pas solo queue. Di utas ini aku mau bagiin tips mulai dari penentuan eDPI yang ideal (biasanya 200-300), warm up routine 15 menit di The Range, sampai positioning crosshair sejajar kepala lawan. Yuk sharing settingan mouse & routine kalian juga di bawah!",
            'is_pinned' => true,
        ]);

        $t1Comment1 = ThreadComment::create([
            'thread_id' => $thread1->id,
            'user_id' => $userRaihan->id,
            'parent_id' => null,
            'comment_text' => 'Bener banget tips eDPI-nya! Dulu sensitivitasku ketinggian sampai 800, pas diturunin ke 240 aim langsung stabil.',
        ]);

        // Nested reply
        ThreadComment::create([
            'thread_id' => $thread1->id,
            'user_id' => $userDika->id,
            'parent_id' => $t1Comment1->id,
            'comment_text' => 'Mantap Raihan! Awalnya emang kerasa pegel di lengan, tapi setelah 3 hari udah terbiasa kan?',
        ]);

        $thread2 = Thread::create([
            'community_id' => $commDevs->id,
            'user_id' => $userRaihan->id,
            'hobby_id' => $hobbies['Web Development']->id,
            'title' => 'Tech Stack Terbaik untuk Pelajar SMA yang Baru Mulai Belajar Web Programming',
            'body' => "Buat kalian yang masih sekolah dan tertarik bikin website, jangan langsung bingung sama ratusan framework. Rekomendasiku: kuasai dasar HTML, CSS, dan Javascript dulu. Kalau sudah nyaman, coba eksplor Tailwind CSS dan backend seperti Laravel atau Node.js. Ada yang lagi bikin project portofolio juga di sini?",
            'is_pinned' => false,
        ]);

        $t2Comment1 = ThreadComment::create([
            'thread_id' => $thread2->id,
            'user_id' => $userDika->id,
            'parent_id' => null,
            'comment_text' => 'Setuju! Menurutku Laravel pas banget buat pemula karena dokumentasinya super rapi dan syntax-nya gampang dipahami.',
        ]);

        $thread3 = Thread::create([
            'community_id' => $commIndie->id,
            'user_id' => $userClara->id,
            'hobby_id' => $hobbies['Gitar Akustik']->id,
            'title' => 'Rekomendasi Senar Gitar Akustik yang Enak buat Fingerstyle tapi Ramah Kantong Pelajar?',
            'body' => "Halo teman-teman Sirkel Musik! Aku mau ganti senar gitar akustik kesayangan. Pengen yang nadanya warm tapi tetep empuk di jari buat petikan fingerstyle. Ada rekomendasi merk dan ukuran senar yang cocok under 100k?",
            'is_pinned' => false,
        ]);

        // 7. Seed Match Requests (Teman Main)
        // Incoming request for Dika from Natasha
        MatchRequest::create([
            'sender_id' => $userNatasha->id,
            'receiver_id' => $userDika->id,
            'hobby_id' => $hobbies['Valorant']->id,
            'note' => 'Halo Kak Dika! Suka main Valorant juga ya? Yuk mabar sesama pelajar!',
            'status' => 'pending',
        ]);

        // Incoming request for Dika from Bima
        MatchRequest::create([
            'sender_id' => $userBima->id,
            'receiver_id' => $userDika->id,
            'hobby_id' => $hobbies['Futsal']->id,
            'note' => 'Bro, kapan-kapan main futsal bareng atau sharing info turney ya!',
            'status' => 'pending',
        ]);

        // Accepted connection between Dika and Raihan
        MatchRequest::create([
            'sender_id' => $userDika->id,
            'receiver_id' => $userRaihan->id,
            'hobby_id' => $hobbies['Web Development']->id,
            'note' => 'Ayo ngoding bareng dan tukar ide project!',
            'status' => 'accepted',
        ]);

        // 8. Seed Notifications for Dika
        AppNotification::create([
            'user_id' => $userDika->id,
            'actor_id' => $userNatasha->id,
            'type' => 'match_request',
            'title' => 'Ajakan Main Baru!',
            'message' => 'Natasha Aurelia mengirimkan sinyal Ajak Main ke kamu untuk hobi Valorant.',
            'link_url' => '/teman-main?tab=incoming',
            'is_read' => false,
        ]);

        AppNotification::create([
            'user_id' => $userDika->id,
            'actor_id' => $userRaihan->id,
            'type' => 'comment',
            'title' => 'Komentar baru pada postinganmu',
            'message' => 'Raihan Saputra mengomentari postinganmu di Nongkrong Yuk.',
            'link_url' => '/feed#post-' . $post1->id,
            'is_read' => false,
        ]);
    }
}
