<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Untuk SETIAP kategori yang ada di tabel `categories`, buat 10 akun freelancer
 * sungguhan (baris di tabel `users`, bisa dipakai login). Tiap akun punya:
 *   - 1 jasa di kategori itu,
 *   - beberapa pesanan berstatus `selesai`,
 *   - satu ulasan (rating 1-5) per pesanan, dari akun klien contoh.
 * Rating freelancer = rata-rata ulasan (lihat User::rating).
 *
 * Semua akun memakai password: 12345678
 *
 * Jalankan : php artisan db:seed --class=FreelancerPerKategoriSeeder
 * Ulangi   : aman. Akun & jasa yang sudah ada tidak digandakan, dan
 *            freelancer yang sudah punya ulasan tidak diisi ulang.
 *
 * Isinya dibangkitkan secara deterministik (lihat buildPlan), jadi hasilnya
 * sama setiap kali dijalankan.
 */
class FreelancerPerKategoriSeeder extends Seeder
{
    public const PASSWORD = '12345678';
    public const PER_CATEGORY = 10;
    public const CLIENT_COUNT = 15;
    private const SEED = 20261012;

    private const FIRST_NAMES = [
        'Rian', 'Dinda', 'Alif', 'Citra', 'Yoga', 'Intan', 'Reza', 'Wulan', 'Bima', 'Putri',
        'Bagas', 'Anisa', 'Doni', 'Sekar', 'Farhan', 'Nadia', 'Fajar', 'Salsa', 'Rizky', 'Maya',
        'Dimas', 'Laras', 'Hafiz', 'Ayu', 'Raka', 'Nabila', 'Arga', 'Tiara', 'Galih', 'Rani',
        'Eko', 'Sinta', 'Irfan', 'Melati', 'Teguh', 'Kirana', 'Naufal', 'Amelia', 'Bayu', 'Zahra',
        'Satria', 'Fitri', 'Candra', 'Lestari', 'Adit', 'Mawar', 'Vino', 'Hana', 'Rangga', 'Dewi',
        'Ilham', 'Nisa', 'Gilang', 'Ratna', 'Yusuf', 'Aulia', 'Hendra', 'Sabrina', 'Krisna', 'Bella',
    ];

    private const LAST_NAMES = [
        'Pratama', 'Ayu', 'Ramadhan', 'Lestari', 'Saputra', 'Permata', 'Firmansyah', 'Sari', 'Aditya', 'Anjani',
        'Kurniawan', 'Rahma', 'Malik', 'Wijaya', 'Hidayat', 'Putri', 'Nugroho', 'Maulana', 'Santoso', 'Wulandari',
        'Setiawan', 'Utami', 'Prakoso', 'Rahmawati', 'Hakim', 'Kusuma', 'Siregar', 'Nasution', 'Hasibuan', 'Lubis',
        'Pangestu', 'Handayani', 'Susanto', 'Fadillah', 'Mahendra', 'Puspita', 'Irawan', 'Safitri', 'Budiman', 'Amalia',
        'Gunawan', 'Oktaviani', 'Syahputra', 'Febriani', 'Wibowo', 'Maharani', 'Halim', 'Yulianti', 'Darmawan', 'Novita',
        'Alamsyah', 'Purnama', 'Ginting', 'Damayanti', 'Hutapea', 'Rosalina', 'Tanjung', 'Marpaung', 'Kartika', 'Sembiring',
    ];

    private const CAMPUSES = [
        'Universitas Maritim Raja Ali Haji', 'Politeknik Negeri Batam', 'Universitas Telkom',
        'Universitas Multimedia Nusantara', 'Universitas Bina Nusantara', 'Universitas Gunadarma',
        'Universitas Negeri Jakarta', 'Universitas Diponegoro', 'Universitas Airlangga',
        'Universitas Padjadjaran', 'Universitas Brawijaya', 'Universitas Negeri Malang',
        'Universitas Pasundan', 'Universitas Trisakti', 'Institut Seni Indonesia Yogyakarta',
        'Universitas Sumatera Utara', 'Universitas Andalas', 'Universitas Hasanuddin',
    ];

    private const CLOSINGS = [
        'Terbiasa mengerjakan pesanan dari teman kampus, organisasi, dan UMKM.',
        'Suka kerja rapi, tepat waktu, dan terbuka untuk revisi.',
        'Fokus pada hasil yang sesuai brief dengan harga mahasiswa.',
        'Biasa menangani pesanan mendesak tanpa mengorbankan kualitas.',
        'Komunikatif dan selalu update progres selama pengerjaan.',
    ];

    private const METHODS = ['transfer_bca', 'transfer_bri', 'qris', 'ewallet'];

    // Komentar ulasan menurut rating (1 dan 2 memakai daftar yang sama)
    private const COMMENTS = [
        5 => [
            'Hasil rapi dan sesuai brief, pengerjaan cepat. Recommended!',
            'Komunikatif dan revisi dilayani dengan sabar. Puas banget.',
            'Melebihi ekspektasi, pasti order lagi.',
            'Profesional, hasilnya bagus dan selesai sebelum deadline.',
            'Detail dan teliti. Terima kasih banyak!',
        ],
        4 => [
            'Hasil bagus, hanya sedikit lebih lama dari estimasi.',
            'Sesuai permintaan, revisi kecil langsung ditangani.',
            'Kualitas oke dan harga masuk akal untuk mahasiswa.',
            'Komunikasi lancar, hasil memuaskan.',
        ],
        3 => [
            'Hasil cukup baik, perlu beberapa kali revisi.',
            'Lumayan, tetapi balasan chat agak lambat.',
            'Sesuai standar, ada bagian yang perlu disesuaikan lagi.',
        ],
        2 => [
            'Hasil kurang sesuai brief dan revisi memakan waktu.',
            'Pengerjaan telat dari janji awal.',
        ],
        1 => [
            'Hasil kurang sesuai brief dan revisi memakan waktu.',
            'Pengerjaan telat dari janji awal.',
        ],
    ];

    // Isi per kategori (kunci = slug). Kategori lain pakai fallbackContent().
    private const CONTENT = [
        'desain-grafis' => [
            'titles' => [
                'Desain Logo Minimalis untuk Brand dan UMKM',
                'Desain Feed Instagram Estetik (9 Slot)',
                'Desain Poster Event Kampus',
                'Desain Kemasan Produk Siap Cetak',
                'Desain Merchandise Kaos Komunitas',
                'Desain Sertifikat dan Undangan Digital',
                'Ilustrasi Karakter dan Maskot Brand',
                'Desain Banner dan Spanduk Acara',
                'Desain Menu Kafe dan Katalog Produk',
                'Paket Identitas Brand (Logo + Brand Guide)',
            ],
            'skills' => ['Logo Design', 'Branding', 'Figma', 'Photoshop', 'Illustrator', 'Canva', 'Poster', 'Ilustrasi', 'Packaging', 'Typography'],
            'jurusan' => ['Desain Komunikasi Visual', 'Desain Produk', 'Ilmu Komunikasi'],
            'about' => 'Desain dikerjakan sesuai brief dan dikirim dalam format siap pakai (PNG, PDF, dan file sumber bila diminta).',
            'price' => [40000, 250000, 5000],
        ],
        'penyuntingan-video' => [
            'titles' => [
                'Edit Video Reels dan TikTok Gaya Dinamis',
                'Edit Video YouTube (Cut, Subtitle, Musik)',
                'Video Dokumentasi Acara Kampus',
                'Highlight Video Wisuda dan Pernikahan',
                'Motion Graphic Intro dan Outro',
                'Edit Video Profil UMKM',
                'Subtitle dan Teks Otomatis untuk Video',
                'Color Grading Video Sinematik',
                'Video Promosi Produk 30 Detik',
                'Edit Podcast Video dan Klip Pendek',
            ],
            'skills' => ['Premiere Pro', 'After Effects', 'CapCut', 'DaVinci Resolve', 'Color Grading', 'Motion Graphic', 'Subtitle', 'Sound Design', 'Reels', 'YouTube'],
            'jurusan' => ['Film dan Televisi', 'Multimedia', 'Ilmu Komunikasi'],
            'about' => 'Footage dari kamu diolah jadi video yang enak ditonton, lengkap dengan musik dan transisi yang pas.',
            'price' => [50000, 400000, 10000],
        ],
        'fotografi' => [
            'titles' => [
                'Foto Produk untuk Katalog dan Marketplace',
                'Foto Wisuda dan Portrait Outdoor',
                'Dokumentasi Event Kampus dan Organisasi',
                'Food Photography untuk UMKM Kuliner',
                'Foto Profil Profesional untuk LinkedIn',
                'Foto Couple dan Prewedding Sederhana',
                'Retouching dan Edit Foto Lightroom',
                'Foto Lookbook Brand Fashion Lokal',
                'Foto Interior dan Properti',
                'Foto Flatlay dan Produk dengan Model',
            ],
            'skills' => ['Product Photo', 'Portrait', 'Lightroom', 'Photoshop', 'Retouch', 'Food Photo', 'Event', 'Dokumentasi', 'Flatlay', 'Lighting'],
            'jurusan' => ['Fotografi', 'Desain Komunikasi Visual', 'Film dan Televisi'],
            'about' => 'Foto dikerjakan dengan pencahayaan yang rapi dan diedit warna agar natural. Hasil dikirim dalam resolusi tinggi.',
            'price' => [60000, 500000, 10000],
        ],
        'pembuatan-presentasi' => [
            'titles' => [
                'Slide Pitch Deck Startup',
                'Desain Slide Company Profile',
                'Slide Sidang Skripsi dan Tugas Akhir',
                'Presentasi Kelas yang Rapi dan Menarik',
                'Template PowerPoint untuk Organisasi',
                'Infografis untuk Slide Presentasi',
                'Ringkasan Paper Menjadi Slide Seminar',
                'Slide Laporan Bisnis dan Proposal',
                'Animasi dan Transisi PowerPoint',
                'Desain Slide Webinar dan Workshop',
            ],
            'skills' => ['PowerPoint', 'Google Slides', 'Canva', 'Pitch Deck', 'Infografis', 'Keynote', 'Storytelling', 'Layout', 'Data Visualization', 'Copywriting'],
            'jurusan' => ['Manajemen', 'Ilmu Komunikasi', 'Sistem Informasi'],
            'about' => 'Slide ditata dengan alur yang jelas, tipografi konsisten, dan visual yang mudah dipahami audiens.',
            'price' => [30000, 200000, 5000],
        ],
        'pengembangan-web' => [
            'titles' => [
                'Landing Page Responsif (HTML, CSS, JS)',
                'Website Company Profile dengan Laravel',
                'Toko Online Sederhana',
                'Aplikasi CRUD Laravel dan MySQL',
                'Perbaikan Bug dan Optimasi Website',
                'Website Portfolio Pribadi',
                'Integrasi REST API untuk Aplikasi Web',
                'Dashboard Admin dengan Bootstrap',
                'Website Undangan Digital',
                'Pendampingan dan Review Kode Proyek',
            ],
            'skills' => ['Laravel', 'PHP', 'JavaScript', 'HTML', 'CSS', 'Bootstrap', 'Tailwind', 'MySQL', 'React', 'REST API'],
            'jurusan' => ['Teknik Informatika', 'Sistem Informasi', 'Ilmu Komputer'],
            'about' => 'Kode ditulis rapi dan mudah dikembangkan, dengan tampilan responsif di HP maupun laptop.',
            'price' => [100000, 1500000, 50000],
        ],
    ];

    private static int $state = 1;

    // ------------------------------------------------------------------
    // Bagian yang menyentuh database
    // ------------------------------------------------------------------

    public function run(): void
    {
        $categories = Category::orderBy('id')->get(['id', 'name', 'slug'])
            ->map(fn ($c) => ['id' => $c->id, 'name' => $c->name, 'slug' => $c->slug])
            ->all();

        if (empty($categories)) {
            $this->command?->warn('Belum ada kategori. Jalankan dulu: php artisan db:seed --class=CategorySeeder');

            return;
        }

        $plan = self::buildPlan($categories);
        $hash = Hash::make(self::PASSWORD); // sekali saja, dipakai untuk semua akun
        $rows = [];
        $created = 0;

        DB::transaction(function () use ($plan, $hash, &$rows, &$created) {
            // Akun klien contoh (pembeli yang memberi ulasan)
            $clientIds = [];
            foreach ($plan['clients'] as $i => $c) {
                $client = User::firstOrCreate(
                    ['email' => $c['email']],
                    [
                        'name' => $c['name'],
                        'password' => $hash,
                        'is_freelancer' => false,
                        'is_client' => true,
                        'bio' => $c['bio'],
                        'campus' => $c['campus'],
                    ]
                );
                $clientIds[$i] = $client->id;
            }

            foreach ($plan['freelancers'] as $f) {
                $user = User::firstOrCreate(
                    ['email' => $f['email']],
                    [
                        'name' => $f['name'],
                        'password' => $hash,
                        'is_freelancer' => true,
                        'is_client' => true,
                        'bio' => $f['bio'],
                        'campus' => $f['campus'],
                        'skills' => $f['skills'],
                    ]
                );
                if ($user->wasRecentlyCreated) {
                    $created++;
                }

                $service = Service::firstOrCreate(
                    ['user_id' => $user->id, 'category_id' => $f['category_id'], 'title' => $f['service']['title']],
                    ['description' => $f['service']['description'], 'price' => $f['service']['price']]
                );

                // Sudah pernah diisi ulasan -> lewati, supaya tidak dobel
                if (! $user->reviews()->exists()) {
                    foreach ($f['orders'] as $o) {
                        $at = now()->subDays($o['days_ago'])->subMinutes($o['minutes']);

                        $order = new Order([
                            'service_id' => $service->id,
                            'buyer_id' => $clientIds[$o['client']],
                            'price' => $service->price,
                            'note' => null,
                            'status' => 'selesai',
                            'payment_status' => Order::PAY_DICAIRKAN,
                            'payment_method' => $o['method'],
                        ]);
                        $order->created_at = $at;
                        $order->updated_at = $at;
                        $order->save();

                        $reviewAt = $at->copy()->addDays($o['review_after_days']);
                        if ($reviewAt->isFuture()) {
                            $reviewAt = now();
                        }

                        $review = new Review([
                            'order_id' => $order->id,
                            'freelancer_id' => $user->id,
                            'reviewer_id' => $clientIds[$o['client']],
                            'rating' => $o['rating'],
                            'comment' => $o['comment'],
                        ]);
                        $review->created_at = $reviewAt;
                        $review->updated_at = $reviewAt;
                        $review->save();
                    }
                }

                $rows[] = [
                    $f['category_name'], $f['name'], $f['email'], self::PASSWORD,
                    number_format($f['rating_avg'], 1), $f['reviews_total'],
                ];
            }
        });

        $this->command?->table(['Kategori', 'Nama', 'Email', 'Password', 'Rating', 'Ulasan'], $rows);
        $this->command?->info(count($plan['freelancers']).' freelancer diproses ('.$created.' akun baru), '
            .count($plan['clients']).' akun klien contoh. Password semua akun: '.self::PASSWORD);
    }

    // ------------------------------------------------------------------
    // Bagian murni (tanpa database): membangkitkan data. Bisa diuji sendiri.
    // $categories = [['id' => 1, 'name' => 'Desain Grafis', 'slug' => 'desain-grafis'], ...]
    // ------------------------------------------------------------------

    public static function buildPlan(array $categories): array
    {
        self::$state = self::SEED;
        $usedNames = [];

        $clients = [];
        for ($i = 0; $i < self::CLIENT_COUNT; $i++) {
            $name = self::uniqueName($usedNames);
            $jurusan = self::pick(['Manajemen', 'Akuntansi', 'Ilmu Komunikasi', 'Sistem Informasi', 'Teknik Industri', 'Hukum', 'Psikologi']);
            $clients[] = [
                'name' => $name,
                'email' => 'klien.'.self::slug($name).'@gawean.test',
                'bio' => 'Mahasiswa yang sering butuh bantuan kreatif untuk tugas, organisasi, dan usaha kecil.',
                'campus' => $jurusan.' · '.self::pick(self::CAMPUSES),
            ];
        }

        $freelancers = [];
        foreach ($categories as $category) {
            $content = self::CONTENT[$category['slug']] ?? self::fallbackContent($category['name']);

            for ($i = 0; $i < self::PER_CATEGORY; $i++) {
                $name = self::uniqueName($usedNames);
                $jurusan = self::pick($content['jurusan']);
                $kampus = self::pick(self::CAMPUSES);
                $skills = self::pickMany($content['skills'], self::rint(3, 4));
                $title = $content['titles'][$i % count($content['titles'])];

                [$min, $max, $step] = $content['price'];
                $price = $min + $step * self::rint(0, intdiv($max - $min, $step));

                $days = self::rint(1, 5);
                $revisi = self::rint(2, 3);
                $description = $title.'. '.$content['about']
                    ."\n\nPengerjaan sekitar {$days} hari kerja, termasuk {$revisi}x revisi. "
                    .'Chat dulu untuk membahas kebutuhanmu sebelum memesan.';

                $bio = "Mahasiswa {$jurusan} di {$kampus}. ".self::pick(self::CLOSINGS)
                    ." Keahlian utama: {$skills[0]} dan {$skills[1]}.";

                // Kualitas dasar freelancer (3.7 - 4.9) + variasi tiap pesanan -> rata-rata rating bervariasi
                $quality = 3.7 + self::rnd() * 1.2;
                $orderCount = 3 + (int) floor(self::rnd() ** 2 * 13); // 3 sampai 15 pesanan selesai

                $orders = [];
                $sum = 0;
                for ($k = 0; $k < $orderCount; $k++) {
                    $rating = max(1, min(5, (int) round($quality + (self::rnd() - 0.5) * 1.6)));
                    $sum += $rating;
                    $orders[] = [
                        'client' => self::rint(0, self::CLIENT_COUNT - 1),
                        'days_ago' => self::rint(3, 90),
                        'minutes' => self::rint(0, 1439),
                        'review_after_days' => self::rint(1, 3),
                        'rating' => $rating,
                        'comment' => self::rnd() < 0.8 ? self::pick(self::COMMENTS[$rating]) : null,
                        'method' => self::pick(self::METHODS),
                    ];
                }

                $freelancers[] = [
                    'category_id' => $category['id'],
                    'category_name' => $category['name'],
                    'name' => $name,
                    'email' => self::slug($name).'@gawean.test',
                    'bio' => $bio,
                    'campus' => $jurusan.' · '.$kampus,
                    'skills' => $skills,
                    'service' => ['title' => $title, 'description' => $description, 'price' => $price],
                    'orders' => $orders,
                    'reviews_total' => $orderCount,
                    'rating_avg' => round($sum / $orderCount, 1),
                ];
            }
        }

        return ['clients' => $clients, 'freelancers' => $freelancers];
    }

    private static function fallbackContent(string $categoryName): array
    {
        $titles = [];
        for ($i = 1; $i <= self::PER_CATEGORY; $i++) {
            $titles[] = "Jasa {$categoryName} Paket {$i}";
        }

        return [
            'titles' => $titles,
            'skills' => [$categoryName, 'Komunikasi', 'Riset', 'Revisi Cepat', 'Tepat Waktu'],
            'jurusan' => ['Manajemen', 'Ilmu Komunikasi', 'Sistem Informasi'],
            'about' => "Layanan {$categoryName} dikerjakan sesuai kebutuhanmu dengan harga yang ramah mahasiswa.",
            'price' => [50000, 300000, 10000],
        ];
    }

    // ---- Pembangkit angka acak sederhana dengan seed tetap (hasil sama di semua versi PHP) ----

    private static function rnd(): float
    {
        self::$state = (self::$state * 1664525 + 1013904223) & 0xFFFFFFFF;
        $x = self::$state;
        $x = ((($x >> 16) ^ $x) * 73244475) & 0xFFFFFFFF;
        $x = ((($x >> 16) ^ $x) * 73244475) & 0xFFFFFFFF;
        $x = ($x >> 16) ^ $x;

        return $x / 4294967296;
    }

    private static function rint(int $min, int $max): int
    {
        return $min + (int) floor(self::rnd() * ($max - $min + 1));
    }

    private static function pick(array $items): mixed
    {
        return $items[self::rint(0, count($items) - 1)];
    }

    private static function pickMany(array $items, int $count): array
    {
        $items = array_values($items);
        for ($i = count($items) - 1; $i > 0; $i--) { // Fisher-Yates
            $j = self::rint(0, $i);
            [$items[$i], $items[$j]] = [$items[$j], $items[$i]];
        }

        return array_slice($items, 0, min($count, count($items)));
    }

    private static function uniqueName(array &$used): string
    {
        do {
            $name = self::pick(self::FIRST_NAMES).' '.self::pick(self::LAST_NAMES);
        } while (isset($used[$name]));
        $used[$name] = true;

        return $name;
    }

    private static function slug(string $name): string
    {
        return strtolower(trim(preg_replace('/[^a-z0-9]+/i', '.', $name), '.'));
    }
}
