# Gawean — Platform Jasa Freelance Mahasiswa Berbasis Web

Proyek mata kuliah Perancangan Sistem, Prodi Teknik Informatika, Universitas Maritim Raja Ali Haji (2025/2026).
Dibangun dengan **Laravel 13**, **SQLite**, dan Bootstrap 5.

## Alur transaksi

1. Client memesan jasa (catatan opsional; tidak bisa memesan jasa sendiri).
2. Client memilih metode bayar (BCA, BRI, QRIS, e-wallet) dan mengunggah bukti transfer. Tanpa payment gateway.
3. Admin memeriksa bukti. Valid -> dana berstatus **held** (ditahan). Ditolak -> client mengunggah ulang.
4. Freelancer **menerima** atau **menolak** pesanan. Ditolak -> dana **refunded**.
5. Pesanan diterima dikerjakan, freelancer klik **Selesai** -> dana **dicairkan**, pesanan masuk hitungan leaderboard.
6. Client memberi **rating bintang (1-5)** dan ulasan di halaman *Pesanan Saya*.

Status pesanan: `menunggu -> diterima | ditolak -> selesai` (tanpa status revisi).

## Fitur yang sudah ada

| Modul | Fitur |
|---|---|
| Autentikasi & profil | Register/login (peran freelancer, client, atau keduanya, minimal satu); edit profil, foto, bio, jurusan + kampus; keahlian freelancer maks. 8 item; profil publik freelancer (bio, keahlian, jasa, rating, ulasan) dan client (info dasar) |
| Admin | Guard & tabel terpisah (`admins`), login lewat `/admin` dibatasi 5 percobaan/menit, akun dibuat lewat `php artisan admin:create` (tidak ada pendaftaran admin lewat web) |
| Listing jasa | Buat/edit/hapus jasa (judul, harga, kategori, deskripsi, gambar); kategori; pencarian; filter **kategori dan harga maksimal**; rating hanya ditampilkan |
| Transaksi & order | Pemesanan, pembayaran manual + bukti, verifikasi admin, terima/tolak freelancer, **Pesanan Saya** dan **Pesanan Masuk** |
| Komunikasi | Chat client-freelancer (polling 3 detik, maks. 30 pesan/menit); client boleh chat ke freelancer, freelancer hanya ke client yang membeli jasanya; badge jumlah belum dibaca di navbar (polling 20 detik lewat `/badge-counts`); chat tidak membuat notifikasi |
| Notifikasi | Order baru; pembayaran terverifikasi; pesanan siap diproses; bukti pembayaran ditolak; pesanan diterima / ditolak / selesai |
| Rating | Rating bintang + ulasan setelah pesanan selesai (satu ulasan per pesanan) |
| Panel admin | Dashboard (jumlah pengguna, freelancer, jasa, pesanan, pembayaran menunggu verifikasi, 5 pesanan terbaru); antrean verifikasi pembayaran; CRUD kategori; moderasi (hapus pengguna, lihat/hapus jasa, pantau pesanan) |
| Leaderboard | 10 freelancer dengan pesanan selesai terbanyak, rating rata-rata sebagai pembeda bila jumlah sama |

## Pengembangan lanjutan (belum diimplementasikan)

Fitur berikut **tidak ada** di alur saat ini dan tidak dijanjikan sebagai bagian rilis ini:

- Portofolio hasil kerja freelancer
- Modul Job Posting (posting kebutuhan, apply, pilih freelancer)
- Status order Revisi dan fitur request revisi
- Upload file hasil kerja (deliverable)
- Halaman riwayat transaksi khusus
- Filter pencarian berdasarkan rating
- Notifikasi untuk pesan masuk (saat ini hanya badge jumlah belum dibaca)
- Badge / level freelancer
- Dashboard freelancer (pendapatan, performa jasa) dan dashboard client
- Penanganan laporan user bermasalah
- Wishlist dan rekomendasi jasa
- WebSocket (Laravel Echo + Pusher) sebagai pengganti polling
- Login OAuth 2.0 dengan akun Google mahasiswa

## Menjalankan proyek

Syarat: PHP 8.4+ (sesuai `composer.lock`), Composer, dan Node.js (opsional).

```bash
composer install
copy .env.example .env          # Windows. Di Linux/macOS: cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan db:seed --class=CategorySeeder
php artisan admin:create        # buat akun admin, lalu buka /admin
php artisan serve
```

Data contoh (opsional): `php artisan db:seed --class=FreelancerPerKategoriSeeder`
(membuat freelancer, jasa, pesanan selesai, dan ulasan contoh; password semua akun contoh: `12345678`).

## Pengujian

```bash
php artisan test
```

`tests/Feature/GaweanFlowTest.php` menguji aturan di proposal: alur status pesanan, dana held/refunded/dicairkan, ulasan,
aturan chat, batas 30 pesan/menit, batas 5 percobaan login admin, maks. 8 keahlian, leaderboard, dan CRUD kategori.

## Catatan teknis

- Foto dan bukti pembayaran disimpan di `storage/app/public` (perlu `php artisan storage:link`).
- URL gambar memakai `asset('storage/...')`, bukan `Storage::url()`, supaya tidak bergantung pada `APP_URL`.
- Kolom `users.is_admin` adalah sisa lama dan tidak dipakai; admin sekarang di tabel `admins`.
