<?php

namespace App\Support;

use Illuminate\Support\Str;

class DummyFreelancers
{
    // Data mentah 10 freelancer contoh per kategori (dipakai kalau kategori belum punya jasa asli)
    public static function all(): array
    {
        return [
            'desain-grafis' => [
                ['name' => 'Rian Pratama', 'initial' => 'R', 'badge' => 'Top Rated', 'meta' => 'Desain Komunikasi Visual · Univ. Telkom', 'bio' => 'Fokus bikin logo & identitas brand yang simpel tapi nempel di ingatan.', 'skills' => ['Logo Design', 'Branding', 'Figma'], 'price' => 75000, 'reviews' => 128, 'rating' => 4.9],
                ['name' => 'Dinda Ayu', 'initial' => 'D', 'badge' => 'Level 2', 'meta' => 'Desain Komunikasi Visual · ISI Yogyakarta', 'bio' => 'Bikin poster & feed Instagram yang estetik buat UMKM dan event kampus.', 'skills' => ['Poster', 'Feed IG', 'Canva'], 'price' => 50000, 'reviews' => 96, 'rating' => 4.8],
                ['name' => 'Alif Ramadhan', 'initial' => 'A', 'badge' => 'Level 1', 'meta' => 'Desain Grafis · Univ. Trisakti', 'bio' => 'Desain kemasan produk sederhana sampai siap cetak, harga mahasiswa.', 'skills' => ['Packaging', 'Illustrator'], 'price' => 90000, 'reviews' => 34, 'rating' => 4.7],
                ['name' => 'Citra Lestari', 'initial' => 'C', 'badge' => 'Top Rated', 'meta' => 'Desain Komunikasi Visual · Univ. Multimedia Nusantara', 'bio' => 'Spesialis desain UI aplikasi & website sederhana untuk tugas akhir.', 'skills' => ['UI Design', 'Figma', 'Wireframe'], 'price' => 120000, 'reviews' => 71, 'rating' => 4.9],
                ['name' => 'Yoga Saputra', 'initial' => 'Y', 'badge' => 'Level 2', 'meta' => 'Desain Komunikasi Visual · Univ. Bina Nusantara', 'bio' => 'Bikin merchandise & desain kaos komunitas / organisasi kampus.', 'skills' => ['Merchandise', 'Ilustrasi'], 'price' => 65000, 'reviews' => 58, 'rating' => 4.6],
                ['name' => 'Intan Permata', 'initial' => 'I', 'badge' => 'Level 1', 'meta' => 'Desain Grafis · Univ. Gunadarma', 'bio' => 'Desain sertifikat, undangan, dan materi cetak acara kampus.', 'skills' => ['Sertifikat', 'Undangan'], 'price' => 40000, 'reviews' => 22, 'rating' => 4.5],
                ['name' => 'Reza Firmansyah', 'initial' => 'R', 'badge' => 'Top Rated', 'meta' => 'Desain Komunikasi Visual · Univ. Petra', 'bio' => 'Ilustrasi karakter dan mascot brand dengan gaya flat & playful.', 'skills' => ['Ilustrasi', 'Mascot', 'Procreate'], 'price' => 110000, 'reviews' => 84, 'rating' => 5.0],
                ['name' => 'Wulan Sari', 'initial' => 'W', 'badge' => 'Level 2', 'meta' => 'Desain Komunikasi Visual · Univ. Diponegoro', 'bio' => 'Desain slide deck company profile & pitch deck rapi dan profesional.', 'skills' => ['Pitch Deck', 'PowerPoint'], 'price' => 85000, 'reviews' => 47, 'rating' => 4.8],
                ['name' => 'Bima Aditya', 'initial' => 'B', 'badge' => 'Level 1', 'meta' => 'Desain Grafis · Univ. Airlangga', 'bio' => 'Edit foto & retouching sederhana untuk kebutuhan katalog online.', 'skills' => ['Photoshop', 'Retouch'], 'price' => 45000, 'reviews' => 19, 'rating' => 4.6],
                ['name' => 'Putri Anjani', 'initial' => 'P', 'badge' => 'Top Rated', 'meta' => 'Desain Komunikasi Visual · Univ. Padjadjaran', 'bio' => 'Bikin moodboard & style guide brand kecil biar konsisten di semua media.', 'skills' => ['Moodboard', 'Brand Guide'], 'price' => 95000, 'reviews' => 63, 'rating' => 4.9],
            ],
            'fotografi' => [
                ['name' => 'Bagas Adi', 'initial' => 'B', 'badge' => 'Top Rated', 'meta' => 'Fotografi · Institut Kesenian Jakarta', 'bio' => 'Foto produk buat katalog & marketplace, lighting rapi dan cepat.', 'skills' => ['Product Photo', 'Lightroom'], 'price' => 60000, 'reviews' => 55, 'rating' => 4.7],
                ['name' => 'Anisa Rahma', 'initial' => 'A', 'badge' => 'Level 2', 'meta' => 'Fotografi · ISBI Bandung', 'bio' => 'Foto wisuda dan portrait outdoor dengan editing warna natural.', 'skills' => ['Portrait', 'Wisuda'], 'price' => 150000, 'reviews' => 41, 'rating' => 4.8],
                ['name' => 'Doni Kurniawan', 'initial' => 'D', 'badge' => 'Level 1', 'meta' => 'Fotografi · Univ. Negeri Jakarta', 'bio' => 'Dokumentasi event kampus dan organisasi dengan harga terjangkau.', 'skills' => ['Event', 'Dokumentasi'], 'price' => 200000, 'reviews' => 17, 'rating' => 4.5],
                ['name' => 'Sekar Ayu', 'initial' => 'S', 'badge' => 'Top Rated', 'meta' => 'Fotografi · IKJ', 'bio' => 'Food photography untuk UMKM kuliner dan menu digital.', 'skills' => ['Food Photo', 'Styling'], 'price' => 80000, 'reviews' => 62, 'rating' => 5.0],
                ['name' => 'Farhan Malik', 'initial' => 'F', 'badge' => 'Level 2', 'meta' => 'Fotografi · Univ. Pasundan', 'bio' => 'Prewedding & couple photoshoot dengan konsep simpel dan estetik.', 'skills' => ['Prewedding', 'Couple'], 'price' => 300000, 'reviews' => 29, 'rating' => 4.7],
                ['name' => 'Melati Putri', 'initial' => 'M', 'badge' => 'Level 1', 'meta' => 'Fotografi · Univ. Trisakti', 'bio' => 'Foto produk fashion untuk toko online kecil, background custom.', 'skills' => ['Fashion Photo', 'Editing'], 'price' => 70000, 'reviews' => 24, 'rating' => 4.6],
                ['name' => 'Aditya Wibowo', 'initial' => 'A', 'badge' => 'Top Rated', 'meta' => 'Fotografi · ISI Surakarta', 'bio' => 'Foto arsitektur dan interior untuk portofolio jurusan desain.', 'skills' => ['Arsitektur', 'Interior'], 'price' => 130000, 'reviews' => 38, 'rating' => 4.9],
                ['name' => 'Nabila Zahra', 'initial' => 'N', 'badge' => 'Level 2', 'meta' => 'Fotografi · Univ. Brawijaya', 'bio' => 'Headshot profesional untuk LinkedIn dan CV lamaran kerja.', 'skills' => ['Headshot', 'Studio'], 'price' => 90000, 'reviews' => 33, 'rating' => 4.8],
                ['name' => 'Galih Prasetyo', 'initial' => 'G', 'badge' => 'Level 1', 'meta' => 'Fotografi · Univ. Sebelas Maret', 'bio' => 'Dokumentasi produk handmade dan kerajinan untuk marketplace.', 'skills' => ['Handmade', 'Marketplace'], 'price' => 55000, 'reviews' => 14, 'rating' => 4.5],
                ['name' => 'Clarissa Dewi', 'initial' => 'C', 'badge' => 'Top Rated', 'meta' => 'Fotografi · Univ. Parahyangan', 'bio' => 'Konten foto lifestyle untuk feed Instagram brand personal.', 'skills' => ['Lifestyle', 'IG Feed'], 'price' => 85000, 'reviews' => 49, 'rating' => 4.9],
            ],
            'pembuatan-presentasi' => [
                ['name' => 'Salsa Dewi', 'initial' => 'S', 'badge' => 'Level 1', 'meta' => 'Manajemen · Univ. Indonesia', 'bio' => 'Bantu rapiin slide presentasi skripsi & bisnis biar enak dilihat.', 'skills' => ['PowerPoint', 'Canva'], 'price' => 45000, 'reviews' => 41, 'rating' => 4.9],
                ['name' => 'Hafiz Ramadhan', 'initial' => 'H', 'badge' => 'Top Rated', 'meta' => 'Manajemen · Univ. Gadjah Mada', 'bio' => 'Desain pitch deck untuk lomba business plan dan startup kampus.', 'skills' => ['Pitch Deck', 'Storytelling'], 'price' => 100000, 'reviews' => 67, 'rating' => 5.0],
                ['name' => 'Vania Kusuma', 'initial' => 'V', 'badge' => 'Level 2', 'meta' => 'Komunikasi · Univ. Airlangga', 'bio' => 'Slide untuk seminar proposal dan sidang akhir, layout konsisten.', 'skills' => ['Sidang', 'Layout'], 'price' => 50000, 'reviews' => 35, 'rating' => 4.7],
                ['name' => 'Fikri Alamsyah', 'initial' => 'F', 'badge' => 'Level 1', 'meta' => 'Akuntansi · Univ. Padjadjaran', 'bio' => 'Bikin infografis data & laporan keuangan jadi slide menarik.', 'skills' => ['Infografis', 'Data'], 'price' => 60000, 'reviews' => 18, 'rating' => 4.6],
                ['name' => 'Larasati Putri', 'initial' => 'L', 'badge' => 'Top Rated', 'meta' => 'Desain Komunikasi Visual · Univ. Bina Nusantara', 'bio' => 'Template PowerPoint custom sesuai identitas brand kampus/klien.', 'skills' => ['Template', 'Branding'], 'price' => 75000, 'reviews' => 52, 'rating' => 4.9],
                ['name' => 'Dimas Saputra', 'initial' => 'D', 'badge' => 'Level 2', 'meta' => 'Teknik Industri · ITS', 'bio' => 'Slide laporan magang dan project akhir dengan visual data rapi.', 'skills' => ['Laporan', 'Chart'], 'price' => 55000, 'reviews' => 27, 'rating' => 4.8],
                ['name' => 'Amanda Putri', 'initial' => 'A', 'badge' => 'Level 1', 'meta' => 'Manajemen · Univ. Diponegoro', 'bio' => 'Bantu susun ulang isi slide biar poin utamanya lebih jelas.', 'skills' => ['Copywriting', 'Struktur'], 'price' => 40000, 'reviews' => 12, 'rating' => 4.5],
                ['name' => 'Rendra Wijaya', 'initial' => 'R', 'badge' => 'Top Rated', 'meta' => 'Sistem Informasi · Univ. Telkom', 'bio' => 'Slide company profile dan proposal proyek IT untuk klien.', 'skills' => ['Company Profile', 'Proposal'], 'price' => 90000, 'reviews' => 44, 'rating' => 4.9],
                ['name' => 'Nadya Safitri', 'initial' => 'N', 'badge' => 'Level 2', 'meta' => 'Psikologi · Univ. Indonesia', 'bio' => 'Slide untuk presentasi riset dan jurnal ilmiah yang enak dibaca.', 'skills' => ['Riset', 'Akademik'], 'price' => 50000, 'reviews' => 21, 'rating' => 4.7],
                ['name' => 'Arya Nugroho', 'initial' => 'A', 'badge' => 'Level 1', 'meta' => 'Ekonomi · Univ. Brawijaya', 'bio' => 'Bikin slide training/workshop internal organisasi kampus.', 'skills' => ['Training', 'Workshop'], 'price' => 45000, 'reviews' => 9, 'rating' => 4.6],
            ],
            'pengembangan-web' => [
                ['name' => 'Fajar Nugraha', 'initial' => 'F', 'badge' => 'Top Rated', 'meta' => 'Teknik Informatika · ITB', 'bio' => 'Bangun website portofolio & landing page ringan pakai Laravel dan React.', 'skills' => ['Laravel', 'React', 'Tailwind'], 'price' => 250000, 'reviews' => 64, 'rating' => 4.8],
                ['name' => 'Naufal Hakim', 'initial' => 'N', 'badge' => 'Level 2', 'meta' => 'Informatika · Univ. Telkom', 'bio' => 'Website company profile responsive untuk UMKM dan organisasi.', 'skills' => ['HTML', 'CSS', 'JS'], 'price' => 180000, 'reviews' => 39, 'rating' => 4.7],
                ['name' => 'Kevin Chandra', 'initial' => 'K', 'badge' => 'Top Rated', 'meta' => 'Sistem Informasi · Univ. Bina Nusantara', 'bio' => 'Bikin toko online sederhana pakai WordPress/WooCommerce.', 'skills' => ['WordPress', 'WooCommerce'], 'price' => 300000, 'reviews' => 58, 'rating' => 4.9],
                ['name' => 'Tasya Amelia', 'initial' => 'T', 'badge' => 'Level 1', 'meta' => 'Informatika · Univ. Gunadarma', 'bio' => 'Landing page promosi produk/event dengan animasi ringan.', 'skills' => ['Landing Page', 'Animasi'], 'price' => 150000, 'reviews' => 15, 'rating' => 4.5],
                ['name' => 'Ilham Maulana', 'initial' => 'I', 'badge' => 'Level 2', 'meta' => 'Teknik Komputer · UGM', 'bio' => 'Aplikasi web CRUD sederhana untuk tugas kuliah / proyek kecil.', 'skills' => ['PHP', 'MySQL'], 'price' => 200000, 'reviews' => 26, 'rating' => 4.6],
                ['name' => 'Rania Zulfa', 'initial' => 'R', 'badge' => 'Top Rated', 'meta' => 'Informatika · Univ. Indonesia', 'bio' => 'Bikin dashboard admin dan sistem informasi berbasis web.', 'skills' => ['Dashboard', 'Vue.js'], 'price' => 350000, 'reviews' => 47, 'rating' => 4.9],
                ['name' => 'Bayu Saputro', 'initial' => 'B', 'badge' => 'Level 1', 'meta' => 'Informatika · Univ. Diponegoro', 'bio' => 'Perbaikan bug & maintenance website WordPress yang sudah ada.', 'skills' => ['Bug Fix', 'Maintenance'], 'price' => 100000, 'reviews' => 11, 'rating' => 4.6],
                ['name' => 'Della Puspita', 'initial' => 'D', 'badge' => 'Level 2', 'meta' => 'Sistem Informasi · Univ. Padjadjaran', 'bio' => 'Integrasi API dan form pemesanan online untuk website kecil.', 'skills' => ['API', 'Form'], 'price' => 220000, 'reviews' => 30, 'rating' => 4.7],
                ['name' => 'Rafi Hidayat', 'initial' => 'R', 'badge' => 'Top Rated', 'meta' => 'Teknik Informatika · Univ. Brawijaya', 'bio' => 'Optimasi kecepatan & SEO dasar untuk website yang sudah jadi.', 'skills' => ['SEO', 'Optimasi'], 'price' => 175000, 'reviews' => 42, 'rating' => 4.8],
                ['name' => 'Shafira Nur', 'initial' => 'S', 'badge' => 'Level 1', 'meta' => 'Informatika · Univ. Airlangga', 'bio' => 'Website blog/portofolio pribadi dengan CMS ringan.', 'skills' => ['CMS', 'Blog'], 'price' => 120000, 'reviews' => 8, 'rating' => 4.5],
            ],
            'penyuntingan-video' => [
                ['name' => 'Nadia Putri', 'initial' => 'N', 'badge' => 'Level 2', 'meta' => 'Ilmu Komunikasi · Univ. Padjadjaran', 'bio' => 'Spesialis edit video pendek buat Reels & TikTok yang lagi tren.', 'skills' => ['Video Editing', 'CapCut'], 'price' => 100000, 'reviews' => 87, 'rating' => 5.0],
                ['name' => 'Rizky Ananda', 'initial' => 'R', 'badge' => 'Top Rated', 'meta' => 'Broadcasting · Univ. Padjadjaran', 'bio' => 'Edit vlog dan video YouTube dengan color grading rapi.', 'skills' => ['YouTube', 'Color Grading'], 'price' => 150000, 'reviews' => 73, 'rating' => 4.9],
                ['name' => 'Kirana Ayu', 'initial' => 'K', 'badge' => 'Level 1', 'meta' => 'Ilmu Komunikasi · Univ. Sriwijaya', 'bio' => 'Video promosi produk pendek untuk UMKM dan marketplace.', 'skills' => ['Promo Video', 'Premiere'], 'price' => 90000, 'reviews' => 20, 'rating' => 4.6],
                ['name' => 'Fikar Ramadhan', 'initial' => 'F', 'badge' => 'Level 2', 'meta' => 'Desain Komunikasi Visual · Univ. Multimedia Nusantara', 'bio' => 'Motion graphic sederhana untuk intro dan outro video.', 'skills' => ['Motion Graphic', 'After Effects'], 'price' => 130000, 'reviews' => 36, 'rating' => 4.7],
                ['name' => 'Sabrina Halim', 'initial' => 'S', 'badge' => 'Top Rated', 'meta' => 'Broadcasting · Univ. Prof. Dr. Moestopo', 'bio' => 'Edit video wisuda dan momen kampus jadi cinematic recap.', 'skills' => ['Cinematic', 'Recap'], 'price' => 175000, 'reviews' => 54, 'rating' => 4.9],
                ['name' => 'Gilang Permana', 'initial' => 'G', 'badge' => 'Level 1', 'meta' => 'Ilmu Komunikasi · Univ. Padjadjaran', 'bio' => 'Video dokumentasi event organisasi dan UKM kampus.', 'skills' => ['Event Video', 'Dokumentasi'], 'price' => 110000, 'reviews' => 16, 'rating' => 4.5],
                ['name' => 'Aulia Rahmawati', 'initial' => 'A', 'badge' => 'Level 2', 'meta' => 'Broadcasting · Univ. Sebelas Maret', 'bio' => 'Podcast video editing dan pemotongan klip untuk sosial media.', 'skills' => ['Podcast', 'Clipping'], 'price' => 95000, 'reviews' => 28, 'rating' => 4.7],
                ['name' => 'Bintang Wijaya', 'initial' => 'B', 'badge' => 'Top Rated', 'meta' => 'Desain Komunikasi Visual · Univ. Trisakti', 'bio' => 'Edit video review produk & unboxing dengan gaya dinamis.', 'skills' => ['Review Video', 'Unboxing'], 'price' => 120000, 'reviews' => 45, 'rating' => 4.8],
                ['name' => 'Zahra Amelia', 'initial' => 'Z', 'badge' => 'Level 1', 'meta' => 'Ilmu Komunikasi · Univ. Hasanuddin', 'bio' => 'Video edukasi singkat untuk konten belajar di media sosial.', 'skills' => ['Konten Edukasi', 'Subtitle'], 'price' => 85000, 'reviews' => 13, 'rating' => 4.6],
                ['name' => 'Yusuf Firdaus', 'initial' => 'Y', 'badge' => 'Top Rated', 'meta' => 'Broadcasting · Univ. Andalas', 'bio' => 'Video profil bisnis singkat lengkap dengan sound design.', 'skills' => ['Company Profile', 'Sound Design'], 'price' => 200000, 'reviews' => 60, 'rating' => 4.9],
            ],
        ];
    }

    // Ambil 10 freelancer sebuah kategori, sudah dilengkapi slug, about, dan 1 ulasan contoh
    public static function forCategory(string $categorySlug): array
    {
        $list = static::all()[$categorySlug] ?? [];

        $sampleReviewers = [
            ['name' => 'anandaputra', 'country' => 'Indonesia'],
            ['name' => 'client_umkm', 'country' => 'Indonesia'],
            ['name' => 'studentprojectid', 'country' => 'Indonesia'],
            ['name' => 'usahakecilku', 'country' => 'Indonesia'],
        ];

        foreach ($list as $idx => &$f) {
            $mainSkill = $f['skills'][0] ?? 'jasa ini';
            [$jurusan, $kampus] = array_pad(explode(' · ', $f['meta']), 2, '');

            $f['slug'] = Str::slug($f['name']);
            $f['about'] = $f['bio'] . ' Saat ini aktif kuliah jurusan ' . $jurusan . ' di ' . $kampus
                . ', dan sudah mengerjakan ' . $f['reviews'] . '+ pesanan untuk klien di Gawean.';
            $f['work_title'] = 'Freelancer ' . $mainSkill;

            $reviewer = $sampleReviewers[$idx % count($sampleReviewers)];
            $f['review_name'] = $reviewer['name'];
            $f['review_country'] = $reviewer['country'];
            $f['review_text'] = 'Hasil kerjanya rapi dan sesuai permintaan. Komunikasi lancar dan revisi dikerjakan dengan cepat. Pasti order lagi untuk kebutuhan ' . strtolower($mainSkill) . ' berikutnya.';
        }
        unset($f);

        return $list;
    }

    // Cari 1 freelancer berdasarkan kategori + slug namanya (dipakai di halaman profil)
    public static function find(string $categorySlug, string $freelancerSlug): ?array
    {
        foreach (static::forCategory($categorySlug) as $f) {
            if ($f['slug'] === $freelancerSlug) {
                return $f;
            }
        }
        return null;
    }
}