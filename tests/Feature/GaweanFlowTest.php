<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Order;
use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

// Menguji aturan yang tertulis di proposal (alur baru Gawean).
class GaweanFlowTest extends TestCase
{
    use RefreshDatabase;

    private function freelancer(string $name = 'Frela'): User
    {
        return User::factory()->create(['name' => $name, 'is_freelancer' => true, 'is_client' => false]);
    }

    private function client(string $name = 'Klien'): User
    {
        return User::factory()->create(['name' => $name, 'is_freelancer' => false, 'is_client' => true]);
    }

    private function service(User $owner): Service
    {
        $cat = Category::firstOrCreate(['slug' => 'desain-grafis'], ['name' => 'Desain Grafis']);

        return Service::create([
            'user_id' => $owner->id, 'category_id' => $cat->id,
            'title' => 'Logo', 'description' => 'Bikin logo', 'price' => 50000,
        ]);
    }

    private function order(Service $s, User $buyer, string $status = 'menunggu', string $pay = Order::PAY_HELD): Order
    {
        return Order::create([
            'service_id' => $s->id, 'buyer_id' => $buyer->id, 'price' => $s->price,
            'status' => $status, 'payment_status' => $pay,
        ]);
    }

    public function test_client_tidak_bisa_memesan_jasa_sendiri(): void
    {
        $u = User::factory()->create(['is_freelancer' => true, 'is_client' => true]);
        $s = $this->service($u);

        $this->actingAs($u)->post(route('orders.store', $s))->assertForbidden();
    }

    public function test_alur_status_menunggu_diterima_selesai_dan_dana_dicairkan(): void
    {
        $f = $this->freelancer(); $c = $this->client();
        $o = $this->order($this->service($f), $c);

        // selesai langsung dari menunggu ditolak
        $this->actingAs($f)->patch(route('orders.updateStatus', $o), ['status' => 'selesai'])->assertForbidden();

        $this->actingAs($f)->patch(route('orders.updateStatus', $o), ['status' => 'diterima'])->assertRedirect();
        $this->assertSame('diterima', $o->fresh()->status);

        $this->actingAs($f)->patch(route('orders.updateStatus', $o), ['status' => 'selesai'])->assertRedirect();
        $this->assertSame('selesai', $o->fresh()->status);
        $this->assertSame(Order::PAY_DICAIRKAN, $o->fresh()->payment_status);
    }

    public function test_tolak_pesanan_mengembalikan_dana(): void
    {
        $f = $this->freelancer(); $c = $this->client();
        $o = $this->order($this->service($f), $c);

        $this->actingAs($f)->patch(route('orders.updateStatus', $o), ['status' => 'ditolak'])->assertRedirect();
        $this->assertSame(Order::PAY_REFUNDED, $o->fresh()->payment_status);

        // setelah ditolak tidak bisa diubah lagi
        $this->actingAs($f)->patch(route('orders.updateStatus', $o), ['status' => 'diterima'])->assertForbidden();
    }

    public function test_freelancer_tidak_bisa_memproses_sebelum_pembayaran_diverifikasi(): void
    {
        $f = $this->freelancer(); $c = $this->client();
        $o = $this->order($this->service($f), $c, 'menunggu', Order::PAY_MENUNGGU_VERIFIKASI);

        $this->actingAs($f)->patch(route('orders.updateStatus', $o), ['status' => 'diterima'])->assertForbidden();
    }

    public function test_review_hanya_setelah_selesai_oleh_pembeli_dan_sekali_saja(): void
    {
        $f = $this->freelancer(); $c = $this->client(); $lain = $this->client('Lain');
        $o = $this->order($this->service($f), $c, 'diterima');

        $this->actingAs($c)->post(route('reviews.store', $o), ['rating' => 5])->assertForbidden(); // belum selesai

        $o->update(['status' => 'selesai', 'payment_status' => Order::PAY_DICAIRKAN]);

        $this->actingAs($lain)->post(route('reviews.store', $o), ['rating' => 5])->assertForbidden(); // bukan pembeli
        $this->actingAs($c)->post(route('reviews.store', $o), ['rating' => 6])->assertSessionHasErrors('rating');
        $this->actingAs($c)->post(route('reviews.store', $o), [])->assertSessionHasErrors('rating');

        $this->actingAs($c)->post(route('reviews.store', $o), ['rating' => 4, 'comment' => 'Mantap'])
            ->assertRedirect(route('orders.mine'));
        $this->assertSame(1, Review::count());
        $this->assertSame(4.0, (float) $f->fresh()->rating);

        // ulasan kedua ditolak
        $this->actingAs($c)->post(route('reviews.store', $o), ['rating' => 1]);
        $this->assertSame(1, Review::count());
    }

    public function test_halaman_pesanan_saya_menampilkan_form_ulasan_untuk_pesanan_selesai(): void
    {
        $f = $this->freelancer(); $c = $this->client();
        $this->order($this->service($f), $c, 'selesai', Order::PAY_DICAIRKAN);

        $this->actingAs($c)->get(route('orders.mine'))->assertOk()->assertSee('Beri Ulasan')->assertSee('Kirim Ulasan');
    }

    public function test_aturan_chat_berbasis_hubungan_pembeli_penjual(): void
    {
        $f1 = $this->freelancer('F1'); $f2 = $this->freelancer('F2'); $c = $this->client();
        $svc = $this->service($f1);

        $this->assertTrue($c->canStartChatWith($f1));            // client -> freelancer
        $this->assertFalse($f1->canStartChatWith($c));           // freelancer -> client yang belum membeli
        $this->assertFalse($f1->canStartChatWith($f2));          // freelancer -> freelancer lain
        $this->assertFalse($f1->canStartChatWith($f1));          // diri sendiri

        $this->order($svc, $c);
        $this->assertTrue($f1->fresh()->canStartChatWith($c));   // client yang membeli jasanya
        $this->assertFalse($f2->canStartChatWith($c));           // bukan pembeli jasa f2
    }

    public function test_chat_dibatasi_30_pesan_per_menit(): void
    {
        $f = $this->freelancer(); $c = $this->client();
        $conv = \App\Models\Conversation::between($c, $f);

        for ($i = 0; $i < 30; $i++) {
            $this->actingAs($c)->postJson(route('chat.send', $conv), ['body' => "p{$i}"])->assertCreated();
        }
        $this->actingAs($c)->postJson(route('chat.send', $conv), ['body' => 'lebih'])->assertStatus(429);
    }

    public function test_keahlian_maksimal_8_item(): void
    {
        $f = $this->freelancer();
        $skills = implode(',', range(1, 9));

        $this->actingAs($f)->put(route('profile.update'), ['name' => 'Frela', 'skills' => $skills])
            ->assertSessionHasErrors('skills');
        $this->actingAs($f)->put(route('profile.update'), ['name' => 'Frela', 'skills' => implode(',', range(1, 8))])
            ->assertSessionDoesntHaveErrors();
    }

    public function test_login_admin_dibatasi_5_percobaan_per_menit(): void
    {
        Admin::create(['name' => 'A', 'email' => 'a@gawean.test', 'password' => 'rahasia123']);

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('admin.login'), ['email' => 'a@gawean.test', 'password' => 'salah'])
                ->assertSessionHasErrors('email');
        }
        // percobaan ke-6 diblokir walau password benar
        $this->post(route('admin.login'), ['email' => 'a@gawean.test', 'password' => 'rahasia123'])
            ->assertSessionHasErrors('email');
        $this->assertGuest('admin');
    }

    public function test_user_biasa_tidak_bisa_masuk_panel_admin(): void
    {
        $u = $this->client();
        $this->actingAs($u)->get(route('admin.dashboard'))->assertRedirect(route('admin.home'));
    }

    public function test_leaderboard_10_teratas_pembeda_rating(): void
    {
        $c = $this->client();
        $a = $this->freelancer('A'); $b = $this->freelancer('B');
        $sa = $this->service($a); $sb = $this->service($b);
        $oa = $this->order($sa, $c, 'selesai', Order::PAY_DICAIRKAN);
        $ob = $this->order($sb, $c, 'selesai', Order::PAY_DICAIRKAN);
        Review::create(['order_id' => $oa->id, 'freelancer_id' => $a->id, 'reviewer_id' => $c->id, 'rating' => 3]);
        Review::create(['order_id' => $ob->id, 'freelancer_id' => $b->id, 'reviewer_id' => $c->id, 'rating' => 5]);

        $top = User::topSellers(10);
        $this->assertSame(['B', 'A'], $top->pluck('name')->all()); // jumlah sama -> rating tinggi di atas
    }

    public function test_kategori_menyimpan_ikon_dari_admin(): void
    {
        $admin = Admin::create(['name' => 'A', 'email' => 'a@gawean.test', 'password' => 'rahasia123']);

        $this->actingAs($admin, 'admin')->post(route('admin.categories.store'), ['name' => 'Musik', 'icon' => 'bi-music-note'])
            ->assertSessionHasNoErrors();
        $this->assertSame('bi-music-note', Category::where('slug', 'musik')->first()->icon);

        $this->actingAs($admin, 'admin')->post(route('admin.categories.store'), ['name' => 'Lain', 'icon' => '<script>'])
            ->assertSessionHasErrors('icon');
    }

    public function test_beranda_dan_detail_jasa_tampil_tanpa_error(): void
    {
        $f = $this->freelancer(); $s = $this->service($f);

        $this->get('/')->assertOk();
        $this->get('/?q=Logo&max_price=100000')->assertOk()->assertSee('Logo');
        $this->get(route('services.show', $s))->assertOk();
        $this->get(route('freelancers.show', $f))->assertOk();
        $this->get(route('leaderboard'))->assertOk();
    }
}
