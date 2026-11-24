<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CivitasFoundReport;
use App\Models\Claim;
use App\Models\FoundItem;
use App\Models\LostReport;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FindItApplicationTest extends TestCase
{
    use DatabaseTransactions;

    protected User $student;
    protected User $officer;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::firstOrCreate([
            'slug' => 'aksesori-dompet',
        ], [
            'name' => 'Aksesori & Dompet',
            'icon' => 'wallet',
        ]);

        $this->student = User::firstOrCreate([
            'email' => 'nadia.putri@example.ac.id',
        ], [
            'name' => 'Nadia Putri',
            'identity_number' => '2210631170042',
            'phone_number' => '0812-0000-0142',
            'department' => 'S1 Sistem Informasi',
            'role' => 'mahasiswa',
            'password' => bcrypt('password'),
        ]);

        $this->officer = User::firstOrCreate([
            'email' => 'petugas@findit.ac.id',
        ], [
            'name' => 'Dedi Saputra',
            'identity_number' => 'SEC-FASILKOM-01',
            'phone_number' => '0813-9999-0001',
            'department' => 'Pos Keamanan FASILKOM',
            'role' => 'petugas',
            'password' => bcrypt('password'),
        ]);
    }

    /** Test 1: Public Pages (Landing & Catalog) */
    public function test_public_pages_load_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('FindIt');
        $response->assertSee('Temukan Kembali');
        $response->assertDontSee('PROTOTYPE DEMO');
        $response->assertDontSee('Pindah Peran:');

        $responseCatalog = $this->get('/catalog');
        $responseCatalog->assertStatus(200);
        $responseCatalog->assertSee('Katalog Barang Temuan');
        $responseCatalog->assertSee('.jpg');

        $responseLogin = $this->get('/login');
        $responseLogin->assertStatus(200);
        $responseLogin->assertSee('Masuk');
        $responseLogin->assertDontSee('Akses Demo Satu Klik');
        $responseLogin->assertDontSee('PROTOTYPE DEMO');

        $responseRegister = $this->get('/register');
        $responseRegister->assertStatus(200);
        $responseRegister->assertSee('Buat Akun Civitas');

        $responseForgot = $this->get('/forgot-password');
        $responseForgot->assertStatus(200);
    }

    /** Test 2: Role Authorization - Student blocked from Officer panel */
    public function test_student_cannot_access_officer_panel(): void
    {
        $response = $this->actingAs($this->student)->get('/officer');
        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('error');
    }

    /** Test 3: Officer can access Officer panel & all subpages */
    public function test_officer_can_access_officer_panel(): void
    {
        $response = $this->actingAs($this->officer)->get('/officer');
        $response->assertStatus(200);
        $response->assertSee('Panel Pos Security');
        $response->assertSee('Selamat Bertugas');
        $response->assertSee('Aktivitas 7 Hari Terakhir');
        $response->assertDontSee('px"');

        $this->actingAs($this->officer)->get('/officer/items')->assertStatus(200);
        $this->actingAs($this->officer)->get('/officer/items/create')->assertStatus(200);
        $this->actingAs($this->officer)->get('/officer/reports')->assertStatus(200);
        $this->actingAs($this->officer)->get('/officer/claims')->assertStatus(200);
        $this->actingAs($this->officer)->get('/officer/civitas-reports')->assertStatus(200);
    }

    /** Test 4: Student authenticated dashboard and views */
    public function test_student_dashboard_and_views(): void
    {
        $this->actingAs($this->student)->get('/dashboard')->assertStatus(200)->assertSee('Nadia Putri');
        $this->actingAs($this->student)->get('/reports')->assertStatus(200);
        $this->actingAs($this->student)->get('/reports/create')->assertStatus(200);
        $this->actingAs($this->student)->get('/civitas-reports/create')->assertStatus(200);
        $this->actingAs($this->student)->get('/notifications')->assertStatus(200);
        $this->actingAs($this->student)->get('/profile')->assertStatus(200);
    }

    /** Test 5: Alur A - Civitas Laporkan Kehilangan */
    public function test_alur_a_student_creates_lost_report(): void
    {
        $response = $this->actingAs($this->student)->post('/reports', [
            'item_name' => 'Kacamata Hitam Rayban',
            'category_id' => $this->category->id,
            'lost_date' => now()->toDateString(),
            'lost_location' => 'Gazebo FASILKOM Depan',
            'public_description' => 'Kacamata hitam frame bulat dalam case cokelat.',
            'private_features' => 'Ada tulisan inisial N.P di gagang bagian dalam.',
            'contact_phone' => '0812-0000-0142',
        ]);

        $report = LostReport::where('item_name', 'Kacamata Hitam Rayban')->first();
        $this->assertNotNull($report);
        $this->assertStringStartsWith('LPR-', $report->report_code);
        $response->assertRedirect(route('reports.submitted', $report));

        $this->actingAs($this->student)->get(route('reports.show', $report))->assertStatus(200);
    }

    /** Test 6: Alur B & C - Cari, Klaim, Verifikasi, & Serah Terima Fisik */
    public function test_alur_b_and_c_claim_and_handover_flow(): void
    {
        // 1. Create Found Item in pos
        $item = FoundItem::create([
            'item_code' => 'FND-2026-TEST-' . uniqid(),
            'category_id' => $this->category->id,
            'name' => 'Dompet Kulit Cokelat',
            'found_date' => now()->toDateString(),
            'found_location' => 'Lobi FASILKOM',
            'public_description' => 'Dompet kulit cokelat ditemukan di kursi tunggu.',
            'private_description' => 'Berisi kartu e-toll dan inisial N.P. di sudut dalam.',
            'storage_location' => 'Lemari A • Rak 02',
            'status' => 'tersedia',
            'created_by' => $this->officer->id,
        ]);

        // Public page must not show private description
        $detailResponse = $this->get('/catalog/' . $item->id);
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Dompet Kulit Cokelat');
        $detailResponse->assertDontSee('kartu e-toll dan inisial N.P.'); // Security check!

        // 2. Student submits claim (Alur B)
        $claimResponse = $this->actingAs($this->student)->post('/catalog/' . $item->id . '/claim', [
            'chronology' => 'Tertinggal saat menunggu teman di lobi.',
            'private_proof_features' => 'Di dalam dompet ada kartu e-toll Mandiri dan foto kecil.',
            'contact_phone' => '0812-0000-0142',
        ]);

        $claim = Claim::where('found_item_id', $item->id)->first();
        $this->assertNotNull($claim);
        $this->assertStringStartsWith('KLM-', $claim->claim_code);
        $this->assertEquals('pending', $claim->status);

        // Student can view tracking screen
        $this->actingAs($this->student)->get(route('claims.show', $claim))->assertStatus(200);

        // Officer inspects side-by-side
        $this->actingAs($this->officer)->get(route('officer.claims.show', $claim))->assertStatus(200);

        // 3. Officer approves claim (Alur C)
        $approveResponse = $this->actingAs($this->officer)->post('/officer/claims/' . $claim->id . '/approve', [
            'officer_notes' => 'Ciri kartu dan foto cocok.',
        ]);
        $claim->refresh();
        $item->refresh();
        $this->assertEquals('approved', $claim->status);
        $this->assertEquals('diverifikasi', $item->status); // Principle: Disetujui != Sudah Dikembalikan

        // 4. Officer conducts physical handover at Pos Security
        $this->actingAs($this->officer)->get(route('officer.claims.handover', $claim))->assertStatus(200);

        $handoverResponse = $this->actingAs($this->officer)->post('/officer/claims/' . $claim->id . '/complete-handover', [
            'ktm_verified' => '1',
            'physical_item_checked' => '1',
            'recipient_identity_confirmed' => '1',
            'handover_notes' => 'KTM asli Nadia Putri cocok. Barang telah diserahkan di pos.',
        ]);

        $claim->refresh();
        $item->refresh();
        $this->assertEquals('completed', $claim->status);
        $this->assertEquals('dikembalikan', $item->status);
        $handoverResponse->assertRedirect(route('officer.claims.receipt', $claim));

        // Receipt can be viewed
        $this->actingAs($this->officer)->get(route('officer.claims.receipt', $claim))->assertStatus(200)->assertSee('Tanda Bukti Serah Terima');
    }

    /** Test 7: Alur D - Lapor Temuan Civitas & Penerimaan Security */
    public function test_alur_d_civitas_found_report_and_reception(): void
    {
        // 1. Student reports found item
        $reportResponse = $this->actingAs($this->student)->post('/civitas-reports', [
            'item_name' => 'Payung Lipat Hijau',
            'category_id' => $this->category->id,
            'found_date' => now()->toDateString(),
            'found_location' => 'Parkiran Motor FASILKOM',
            'public_description' => 'Payung lipat warna hijau lumut tergeletak di spion motor.',
            'temp_storage' => 'Disimpan pelapor di loker lab',
            'contact_phone' => '0812-0000-0142',
        ]);

        $cReport = CivitasFoundReport::where('item_name', 'Payung Lipat Hijau')->first();
        $this->assertNotNull($cReport);
        $this->assertStringStartsWith('TMN-', $cReport->report_code);
        $this->assertEquals('menunggu_penyerahan', $cReport->status);

        $this->actingAs($this->student)->get(route('civitas-reports.show', $cReport))->assertStatus(200);
        $this->actingAs($this->officer)->get(route('officer.civitas-reports.show', $cReport))->assertStatus(200);

        // 2. Officer receives physical item at pos security
        $receiveResponse = $this->actingAs($this->officer)->post('/officer/civitas-reports/' . $cReport->id . '/receive', [
            'storage_location' => 'Lemari B • Rak 01',
            'officer_notes' => 'Barang fisik diterima dari mahasiswa pelapor dalam kondisi baik.',
        ]);

        $cReport->refresh();
        $this->assertEquals('diterima_security', $cReport->status);
        $this->assertNotNull($cReport->found_item_id);

        $newFoundItem = FoundItem::find($cReport->found_item_id);
        $this->assertNotNull($newFoundItem);
        $this->assertEquals('tersedia', $newFoundItem->status);
        $this->assertEquals('Lemari B • Rak 01', $newFoundItem->storage_location);
    }
}
