<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CivitasFoundReport;
use App\Models\Claim;
use App\Models\FoundItem;
use App\Models\LostReport;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create sample SVG icons/images in public storage
        Storage::disk('public')->makeDirectory('items');
        Storage::disk('public')->makeDirectory('proofs');

        $this->generateSampleImages();

        // 2. Users
        // Petugas Security
        $officer = User::create([
            'name' => 'Dedi Saputra',
            'email' => 'petugas@findit.ac.id',
            'identity_number' => 'SEC-FASILKOM-01',
            'phone_number' => '0813-9999-0001',
            'department' => 'Pos Keamanan FASILKOM',
            'role' => 'petugas',
            'password' => Hash::make('password'),
        ]);

        $officer2 = User::create([
            'name' => 'Bambang Irawan',
            'email' => 'bambang.security@unsika.ac.id',
            'identity_number' => 'SEC-FASILKOM-02',
            'phone_number' => '0813-9999-0002',
            'department' => 'Pos Keamanan FASILKOM',
            'role' => 'petugas',
            'password' => Hash::make('password'),
        ]);

        // Mahasiswa (Civitas Akademika)
        $nadia = User::create([
            'name' => 'Nadia Putri',
            'email' => 'nadia.putri@example.ac.id',
            'identity_number' => '2210631170042',
            'phone_number' => '0812-0000-0142',
            'department' => 'S1 Sistem Informasi',
            'role' => 'mahasiswa',
            'password' => Hash::make('password'),
        ]);

        $delvin = User::create([
            'name' => 'Mochammad Delvin Farhan Akbar',
            'email' => 'delvin@student.unsika.ac.id',
            'identity_number' => '2310631250063',
            'phone_number' => '0812-3456-7890',
            'department' => 'S1 Sistem Informasi',
            'role' => 'mahasiswa',
            'password' => Hash::make('password'),
        ]);

        $fauzi = User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad.fauzi@student.unsika.ac.id',
            'identity_number' => '2210631170015',
            'phone_number' => '0812-9876-5432',
            'department' => 'S1 Informatika',
            'role' => 'mahasiswa',
            'password' => Hash::make('password'),
        ]);

        // 3. Categories
        $catElectronics = Category::create([
            'name' => 'Elektronik & Gadget',
            'slug' => 'elektronik-gadget',
            'icon' => 'cpu-chip',
            'description' => 'Laptop, ponsel pintar, pengisi daya, earphone, flashdisk, dan aksesori elektronik lainnya.',
        ]);

        $catAccessories = Category::create([
            'name' => 'Aksesori & Dompet',
            'slug' => 'aksesori-dompet',
            'icon' => 'wallet',
            'description' => 'Dompet, jam tangan, kacamata, perhiasan, dan pouch barang pribadi.',
        ]);

        $catDocuments = Category::create([
            'name' => 'Dokumen & Kartu Identitas',
            'slug' => 'dokumen-kartu',
            'icon' => 'identification',
            'description' => 'KTM (Kartu Tanda Mahasiswa), KTP, SIM, STNK, buku catatan, dan berkas akademik.',
        ]);

        $catClothing = Category::create([
            'name' => 'Pakaian & Perlengkapan',
            'slug' => 'pakaian-perlengkapan',
            'icon' => 'shirt',
            'description' => 'Payung, jaket almamater, helm, tas ransel, topi, dan pakaian dinas.',
        ]);

        $catKeys = Category::create([
            'name' => 'Kunci & Kendaraan',
            'slug' => 'kunci-kendaraan',
            'icon' => 'key',
            'description' => 'Kunci motor, kunci loker, remote smart key, dan gantungan kunci.',
        ]);

        $catOthers = Category::create([
            'name' => 'Lain-lain',
            'slug' => 'lain-lain',
            'icon' => 'archive-box',
            'description' => 'Tumbler minum, botol termos stainless, alat tulis, dan barang lainnya.',
        ]);

        // 4. Found Items (Inventaris Pos Security)
        // Showcase item 1: Dompet Hitam (from Figma study case FND-2026-0142)
        $dompet = FoundItem::create([
            'item_code' => 'FND-2026-0142',
            'category_id' => $catAccessories->id,
            'name' => 'Dompet hitam',
            'found_date' => '2026-11-14',
            'found_time' => '09:30:00',
            'found_location' => 'Lobi Gedung FASILKOM',
            'public_description' => 'Dompet lipat berwarna hitam ditemukan di area lobi utama. Barang telah diamankan oleh petugas security.',
            'private_description' => 'Dompet merk Baellerry kulit sintetis hitam. Ciri khusus: jahitan benang biru di lipatan dalam sisi kiri, inisial N.P. tercetak samar pada slot kartu, berisi uang tunai nominal Rp 150.000 dan kartu belanja minimarket.',
            'storage_location' => 'Lemari A • Rak 02',
            'image_path' => 'items/dompet-hitam.jpg',
            'status' => 'tersedia',
            'created_by' => $officer->id,
            'created_at' => now()->subDays(1)->setTime(9, 30),
        ]);

        // Showcase item 2: Tumbler stainless
        $tumbler = FoundItem::create([
            'item_code' => 'FND-2026-0141',
            'category_id' => $catOthers->id,
            'name' => 'Tumbler stainless silver',
            'found_date' => '2026-11-14',
            'found_time' => '10:15:00',
            'found_location' => 'Ruang Seminar 1 Gd. FASILKOM Lt. 2',
            'public_description' => 'Tumbler minum bahan stainless warna silver metalik kapasitas 500ml ditemukan di kursi baris belakang.',
            'private_description' => 'Terdapat stiker logo anime One Piece sedikit pudar di bagian bawah dan sedikit penyok pada tutup bagian atas.',
            'storage_location' => 'Lemari B • Rak 03',
            'image_path' => 'items/tumbler-stainless.jpg',
            'status' => 'tersedia',
            'created_by' => $officer->id,
            'created_at' => now()->subDays(2)->setTime(10, 15),
        ]);

        // Showcase item 3: Charger laptop Asus
        $charger = FoundItem::create([
            'item_code' => 'FND-2026-0140',
            'category_id' => $catElectronics->id,
            'name' => 'Charger laptop hitam Type-C',
            'found_date' => '2026-11-13',
            'found_time' => '14:00:00',
            'found_location' => 'Lab Komputer 2 FASILKOM Lt. 2',
            'public_description' => 'Adaptor charger laptop warna hitam port Type-C tertinggal di colokan meja baris ketiga.',
            'private_description' => 'Output 65W merk Asus ROG, ada selotip hitam pada kabel dekat ujung adaptor untuk pelindung kabel.',
            'storage_location' => 'Lemari A • Rak 01',
            'image_path' => 'items/charger-laptop.jpg',
            'status' => 'tersedia',
            'created_by' => $officer->id,
            'created_at' => now()->subDays(3)->setTime(14, 0),
        ]);

        // Showcase item 4: Earbuds putih
        $earbuds = FoundItem::create([
            'item_code' => 'FND-2026-0139',
            'category_id' => $catElectronics->id,
            'name' => 'Earbuds TWS putih',
            'found_date' => '2026-11-13',
            'found_time' => '11:20:00',
            'found_location' => 'Perpustakaan FASILKOM Lt. 1',
            'public_description' => 'Case TWS berwarna putih glossy ditemukan di meja kubikel baca mandiri.',
            'private_description' => 'Casing bertali silikon warna mint hijau, nomor seri di dalam tutup case berakhiran 8892.',
            'storage_location' => 'Lemari A • Rak 01',
            'image_path' => 'items/earbuds-putih.jpg',
            'status' => 'tersedia',
            'created_by' => $officer->id,
            'created_at' => now()->subDays(4)->setTime(11, 20),
        ]);

        // Showcase item 5: Payung lipat
        $payung = FoundItem::create([
            'item_code' => 'FND-2026-0138',
            'category_id' => $catClothing->id,
            'name' => 'Payung lipat biru navy',
            'found_date' => '2026-11-12',
            'found_time' => '16:45:00',
            'found_location' => 'Lobi Gedung FASILKOM',
            'public_description' => 'Payung lipat otomatis warna biru navy tertinggal di wadah payung depan lobi.',
            'private_description' => 'Gagang kayu cokelat doff, tombol pembuka otomatis sedikit macet, ada ukiran huruf D kecil di tali gantung.',
            'storage_location' => 'Lemari B • Rak 01',
            'image_path' => 'items/payung-lipat.jpg',
            'status' => 'tersedia',
            'created_by' => $officer->id,
            'created_at' => now()->subDays(5)->setTime(16, 45),
        ]);

        // Showcase item 6: Flashdisk
        $flashdisk = FoundItem::create([
            'item_code' => 'FND-2026-0065',
            'category_id' => $catElectronics->id,
            'name' => 'Flashdisk Sandisk 32GB',
            'found_date' => '2026-11-05',
            'found_time' => '13:10:00',
            'found_location' => 'Lab Jaringan Komputer Lt. 3',
            'public_description' => 'Flashdisk model sliding merah hitam kapasitas 32GB tertinggal di PC-08.',
            'private_description' => 'Berisi folder dokumen tugas akhir dan folder backup foto kegiatan himpunan.',
            'storage_location' => 'Lemari A • Rak 01',
            'image_path' => 'items/flashdisk.jpg',
            'status' => 'dikembalikan',
            'created_by' => $officer->id,
            'handed_over_to_user_id' => $fauzi->id,
            'handed_over_at' => now()->subDays(4)->setTime(10, 30),
            'created_at' => now()->subDays(6)->setTime(13, 10),
        ]);

        // Additional items to enrich the inventory (total 12 items seeded)
        $moreItems = [
            [
                'item_code' => 'FND-2026-0136',
                'category_id' => $catKeys->id,
                'name' => 'Kunci Motor Honda dengan Gantungan Akrilik',
                'found_date' => '2026-11-12',
                'found_location' => 'Area Parkir Motor FASILKOM',
                'public_desc' => 'Kunci kontak motor Honda Beat dengan gantungan akrilik ditemukan di dekat tiang parkir nomor 4.',
                'private_desc' => 'Gantungan akrilik foto karakter anime Spy x Family (Anya), kunci cadangan warna hitam kecil disertakan.',
                'shelf' => 'Kotak Kunci • Rak 01',
                'image' => 'items/kunci-motor.jpg',
                'status' => 'tersedia',
                'created_at' => now()->subDays(1)->setTime(14, 30),
            ],
            [
                'item_code' => 'FND-2026-0135',
                'category_id' => $catDocuments->id,
                'name' => 'Kartu Tanda Mahasiswa (KTM) UNSIKA',
                'found_date' => '2026-11-11',
                'found_location' => 'Kantin FASILKOM',
                'public_desc' => 'KTM mahasiswa Fakultas Ilmu Komputer tertinggal di meja kasir kantin.',
                'private_desc' => 'KTM atas nama Rian Pratama, Prodi Informatika angkatan 2023, kartu memiliki stiker hologram UNSIKA di sudut kanan atas.',
                'shelf' => 'Lemari Dokumen • Map 02',
                'image' => 'items/ktm.jpg',
                'status' => 'tersedia',
                'created_at' => now()->subDays(2)->setTime(11, 15),
            ],
            [
                'item_code' => 'FND-2026-0134',
                'category_id' => $catClothing->id,
                'name' => 'Jaket Hoodie Hitam Polos',
                'found_date' => '2026-11-10',
                'found_location' => 'Ruang Kuliah F-204',
                'public_desc' => 'Jaket hoodie warna hitam ukuran XL tergantung di sandaran kursi baris kedua.',
                'private_desc' => 'Merk Uniqlo, tali hoodie ujungnya diganti selotip transparan, di kantong kiri ada tiket parkir tanggal 10 Nov.',
                'shelf' => 'Lemari B • Rak 02',
                'image' => 'items/jaket-hitam.jpg',
                'status' => 'tersedia',
                'created_at' => now()->subDays(3)->setTime(15, 0),
            ],
            [
                'item_code' => 'FND-2026-0133',
                'category_id' => $catElectronics->id,
                'name' => 'Mouse Wireless Logitech Abu-abu',
                'found_date' => '2026-11-09',
                'found_location' => 'Lab Multimedia FASILKOM',
                'public_desc' => 'Mouse wireless warna silent click abu-abu gelap tertinggal di meja dosen.',
                'private_desc' => 'Model Logitech M220, tutup baterai di bawah ada tulisan inisial spidol perak "R.H".',
                'shelf' => 'Lemari A • Rak 03',
                'image' => 'items/mouse.jpg',
                'status' => 'tersedia',
                'created_at' => now()->setTime(8, 45),
            ],
            [
                'item_code' => 'FND-2026-0132',
                'category_id' => $catAccessories->id,
                'name' => 'Kacamata Frame Hitam Kotak',
                'found_date' => '2026-11-08',
                'found_location' => 'Musholla FASILKOM Lt. 1',
                'public_desc' => 'Kacamata minus bingkai hitam kotak diletakkan di rak tempat wudhu pria.',
                'private_desc' => 'Case kacamata kain warna biru tua bermerk Optik Seis, gagang kiri ada bekas solasi bening kecil.',
                'shelf' => 'Lemari A • Rak 02',
                'image' => 'items/kacamata.jpg',
                'status' => 'tersedia',
                'created_at' => now()->setTime(10, 30),
            ],
            [
                'item_code' => 'FND-2026-0092',
                'category_id' => $catClothing->id,
                'name' => 'Payung lipat merah kotak-kotak',
                'found_date' => '2026-10-01',
                'found_location' => 'Tangga Darurat Gd. FASILKOM',
                'public_desc' => 'Payung lipat merah corak kotak-kotak.',
                'private_desc' => 'Gagang plastik hitam retak halus, melewati batas 60 hari penyimpanan tanpa pemilik.',
                'shelf' => 'Gudang Arsip • Kotak 04',
                'image' => 'items/payung-lipat.jpg',
                'status' => 'diarsipkan',
                'created_at' => now()->subDays(6)->setTime(9, 0),
            ],
        ];

        foreach ($moreItems as $item) {
            FoundItem::create([
                'item_code' => $item['item_code'],
                'category_id' => $item['category_id'],
                'name' => $item['name'],
                'found_date' => $item['found_date'],
                'found_time' => '10:00:00',
                'found_location' => $item['found_location'],
                'public_description' => $item['public_desc'],
                'private_description' => $item['private_desc'],
                'storage_location' => $item['shelf'],
                'image_path' => $item['image'],
                'status' => $item['status'],
                'created_by' => $officer->id,
                'created_at' => $item['created_at'],
            ]);
        }

        // 5. Lost Reports
        // Report 1: Nadia Putri lost her wallet (matching FND-2026-0142)
        $report1 = LostReport::create([
            'report_code' => 'LPR-2026-0087',
            'user_id' => $nadia->id,
            'category_id' => $catAccessories->id,
            'item_name' => 'Dompet hitam lipat berbahan kulit',
            'lost_date' => '2026-11-13',
            'lost_time' => '15:30:00',
            'lost_location' => 'Lobi Gedung FASILKOM atau sekitar Gazebo Depan',
            'public_description' => 'Dompet lipat hitam berbahan kulit sintetis hilang setelah kuliah praktikum sore.',
            'private_features' => 'Jahitan benang biru di lipatan dalam, terdapat inisial N.P. di slot kartu, uang tunai sekitar Rp 150rb.',
            'contact_phone' => '0812-0000-0142',
            'status' => 'cocok',
            'matched_found_item_id' => $dompet->id,
            'officer_notes' => 'Ciri-ciri fisik dan inisial cocok dengan barang inventaris FND-2026-0142. Pemohon diarahkan mengajukan klaim kepemilikan.',
        ]);

        // Report 2: Ahmad Fauzi - Flashdisk Sandisk (already completed)
        $report2 = LostReport::create([
            'report_code' => 'LPR-2026-0065',
            'user_id' => $fauzi->id,
            'category_id' => $catElectronics->id,
            'item_name' => 'Flashdisk Sandisk Cruzer Blade 32GB',
            'lost_date' => '2026-11-04',
            'lost_time' => '16:00:00',
            'lost_location' => 'Lab Jaringan Komputer',
            'public_description' => 'Flashdisk merah hitam berisi bahan presentasi praktikum sistem terdistribusi.',
            'private_features' => 'Di body flashdisk ada goresan silang kecil dekat lubang gantungan tali.',
            'contact_phone' => '0812-9876-5432',
            'status' => 'selesai',
            'matched_found_item_id' => $flashdisk->id,
            'officer_notes' => 'Barang telah berhasil diverifikasi dan diserahkan ke mahasiswa bersangkutan pada 6 Nov 2026.',
        ]);

        // Report 3: Delvin - Kunci Motor (menunggu)
        $report3 = LostReport::create([
            'report_code' => 'LPR-2026-0092',
            'user_id' => $delvin->id,
            'category_id' => $catKeys->id,
            'item_name' => 'Kunci Motor Honda Vario 160',
            'lost_date' => '2026-11-14',
            'lost_time' => '11:00:00',
            'lost_location' => 'Area Parkir Mahasiswa Depan Gedung Rektorat/FASILKOM',
            'public_description' => 'Kunci kontak motor remote smart key dengan gantungan tali merah bertuliskan Honda.',
            'private_features' => 'Ada cover silikon warna karbon dan barcode nomor ID remote di baliknya.',
            'contact_phone' => '0812-3456-7890',
            'status' => 'diproses',
            'officer_notes' => 'Petugas sedang menyisir area parkir dan mencocokkan dengan laporan warga kampus.',
        ]);

        // 6. Claim: Nadia Putri claiming Dompet Hitam
        $claim = Claim::create([
            'claim_code' => 'KLM-2026-0031',
            'found_item_id' => $dompet->id,
            'user_id' => $nadia->id,
            'chronology' => 'Hilang setelah kuliah pada hari Rabu 13 Nov sore saat membeli minuman di lobi.',
            'private_proof_features' => 'Ciri khusus yang saya ingat: ada jahitan benang warna biru terang di bagian dalam lipatan kiri, dan di slot kartu kedua terdapat kartu keanggotaan Gramedia dengan nama Nadia Putri.',
            'contact_phone' => '0812-0000-0142',
            'proof_image_path' => 'proofs/foto-dompet-pribadi.jpg',
            'status' => 'pending', // Can be approved by officer during demo
            'officer_notes' => null,
        ]);

        // 7. Civitas Found Report: Alur D (Payung lipat biru oleh civitas)
        CivitasFoundReport::create([
            'report_code' => 'TMN-2026-0012',
            'user_id' => $nadia->id,
            'category_id' => $catClothing->id,
            'item_name' => 'Payung lipat warna biru tua',
            'found_date' => '2026-11-14',
            'found_location' => 'Area parkir FASILKOM samping gazebo',
            'public_description' => 'Menemukan payung lipat biru tua tergeletak di atas jok motor yang sedang parkir.',
            'private_features' => 'Gagang payung memiliki stiker kecil logo Apple.',
            'temp_storage' => 'Barang masih saya bawa dan siap diserahkan ke Pos Security FASILKOM hari ini.',
            'contact_phone' => '0812-0000-0142',
            'image_path' => 'items/payung-lipat.jpg',
            'status' => 'menunggu_penyerahan',
        ]);

        // 8. Notifications
        Notification::create([
            'user_id' => $nadia->id,
            'title' => 'Laporan Berpotensi Cocok',
            'message' => 'Laporan kehilangan Anda LPR-2026-0087 (Dompet hitam) berpotensi cocok dengan barang temuan FND-2026-0142. Silakan ajukan klaim kepemilikan.',
            'type' => 'report_matched',
            'link_url' => '/catalog/' . $dompet->id,
            'is_read' => false,
        ]);

        Notification::create([
            'user_id' => $nadia->id,
            'title' => 'Pengajuan Klaim Diterima',
            'message' => 'Pengajuan klaim Anda dengan nomor KLM-2026-0031 telah terdaftar dan sedang dalam proses verifikasi 1-2 hari kerja oleh petugas security.',
            'type' => 'claim_submitted',
            'link_url' => '/claims/' . $claim->id,
            'is_read' => true,
        ]);

        Notification::create([
            'user_id' => $officer->id,
            'title' => 'Klaim Baru Menunggu Verifikasi',
            'message' => 'Mahasiswa Nadia Putri mengajukan klaim KLM-2026-0031 untuk barang FND-2026-0142 (Dompet hitam).',
            'type' => 'claim_pending',
            'link_url' => '/officer/claims/' . $claim->id,
            'is_read' => false,
        ]);

        Notification::create([
            'user_id' => $officer->id,
            'title' => 'Laporan Temuan Civitas Masuk',
            'message' => 'Nadia Putri melaporkan penemuan payung lipat (TMN-2026-0012) di Area parkir dan menunggu penyerahan fisik.',
            'type' => 'civitas_item_pending',
            'link_url' => '/officer/civitas-reports',
            'is_read' => false,
        ]);
    }

    /**
     * Generate clear SVG images for visual cards
     */
    private function generateSampleImages(): void
    {
        $svgs = [
            'items/dompet-hitam.svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300" width="400" height="300"><rect width="400" height="300" fill="#1E293B"/><rect x="60" y="50" width="280" height="190" rx="18" fill="#0F172A" stroke="#334155" stroke-width="4"/><path d="M 60 120 L 340 120" stroke="#1E293B" stroke-width="3" stroke-dasharray="6,4"/><rect x="250" y="100" width="70" height="40" rx="8" fill="#1E293B" stroke="#475569" stroke-width="2"/><circle cx="270" cy="120" r="5" fill="#E2E8F0"/><text x="200" y="270" font-family="Arial, sans-serif" font-size="14" fill="#94A3B8" text-anchor="middle">Foto Barang: Dompet Kulit Hitam</text></svg>',
            'items/tumbler-stainless.svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300" width="400" height="300"><rect width="400" height="300" fill="#F1F5F9"/><rect x="150" y="40" width="100" height="20" rx="6" fill="#64748B"/><rect x="155" y="60" width="90" height="180" rx="12" fill="#CBD5E1" stroke="#94A3B8" stroke-width="3"/><rect x="165" y="70" width="20" height="160" fill="#E2E8F0" opacity="0.6"/><text x="200" y="270" font-family="Arial, sans-serif" font-size="14" fill="#64748B" text-anchor="middle">Foto Barang: Tumbler Stainless</text></svg>',
            'items/charger-laptop.svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300" width="400" height="300"><rect width="400" height="300" fill="#0F172A"/><rect x="130" y="70" width="140" height="100" rx="10" fill="#1E293B" stroke="#475569" stroke-width="3"/><path d="M 270 120 C 330 120, 310 200, 260 210 C 210 220, 160 190, 110 210" fill="none" stroke="#64748B" stroke-width="5"/><rect x="100" y="205" width="20" height="10" rx="3" fill="#94A3B8"/><text x="200" y="270" font-family="Arial, sans-serif" font-size="14" fill="#94A3B8" text-anchor="middle">Foto Barang: Adaptor Charger Laptop</text></svg>',
            'items/earbuds-putih.svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300" width="400" height="300"><rect width="400" height="300" fill="#E2E8F0"/><rect x="135" y="65" width="130" height="120" rx="35" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="3"/><circle cx="200" cy="140" r="4" fill="#10B981"/><path d="M 135 110 L 265 110" stroke="#E2E8F0" stroke-width="2"/><text x="200" y="260" font-family="Arial, sans-serif" font-size="14" fill="#64748B" text-anchor="middle">Foto Barang: Earbuds TWS Case Putih</text></svg>',
            'items/payung-lipat.svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300" width="400" height="300"><rect width="400" height="300" fill="#F8FAFC"/><rect x="100" y="110" width="180" height="55" rx="12" fill="#1E3A8A" stroke="#172554" stroke-width="2"/><rect x="270" y="125" width="35" height="25" rx="4" fill="#3B82F6"/><path d="M 305 137 C 325 137, 335 155, 315 165" fill="none" stroke="#60A5FA" stroke-width="4"/><text x="200" y="250" font-family="Arial, sans-serif" font-size="14" fill="#64748B" text-anchor="middle">Foto Barang: Payung Lipat Otomatis</text></svg>',
            'items/flashdisk.svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300" width="400" height="300"><rect width="400" height="300" fill="#0F172A"/><rect x="120" y="100" width="140" height="60" rx="8" fill="#DC2626" stroke="#991B1B" stroke-width="2"/><rect x="250" y="110" width="45" height="40" rx="4" fill="#94A3B8"/><rect x="265" y="120" width="15" height="8" fill="#475569"/><rect x="265" y="132" width="15" height="8" fill="#475569"/><text x="200" y="250" font-family="Arial, sans-serif" font-size="14" fill="#94A3B8" text-anchor="middle">Foto Barang: Flashdisk Sandisk 32GB</text></svg>',
            'items/kunci-motor.svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300" width="400" height="300"><rect width="400" height="300" fill="#F1F5F9"/><circle cx="150" cy="120" r="30" fill="none" stroke="#64748B" stroke-width="8"/><rect x="175" y="115" width="90" height="12" rx="3" fill="#64748B"/><path d="M 230 127 L 230 137 M 250 127 L 250 142" stroke="#64748B" stroke-width="6"/><rect x="90" y="130" width="50" height="60" rx="10" fill="#EF4444"/><text x="200" y="260" font-family="Arial, sans-serif" font-size="14" fill="#64748B" text-anchor="middle">Foto Barang: Kunci Motor &amp; Gantungan</text></svg>',
            'items/ktm.svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300" width="400" height="300"><rect width="400" height="300" fill="#F8FAFC"/><rect x="80" y="50" width="240" height="150" rx="12" fill="#173B70" stroke="#0F172A" stroke-width="3"/><circle cx="130" cy="115" r="25" fill="#E2E8F0"/><rect x="170" y="90" width="120" height="10" rx="3" fill="#FFFFFF"/><rect x="170" y="110" width="90" height="8" rx="3" fill="#93C5FD"/><rect x="170" y="125" width="100" height="8" rx="3" fill="#93C5FD"/><text x="200" y="260" font-family="Arial, sans-serif" font-size="14" fill="#64748B" text-anchor="middle">Foto Dokumen: Kartu Tanda Mahasiswa</text></svg>',
            'items/jaket-hitam.svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300" width="400" height="300"><rect width="400" height="300" fill="#F1F5F9"/><path d="M 140 70 L 200 100 L 260 70 L 310 130 L 275 150 L 250 120 L 250 240 L 150 240 L 150 120 L 125 150 L 90 130 Z" fill="#1E293B"/><text x="200" y="275" font-family="Arial, sans-serif" font-size="14" fill="#64748B" text-anchor="middle">Foto Barang: Jaket Hitam</text></svg>',
            'items/mouse.svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300" width="400" height="300"><rect width="400" height="300" fill="#F8FAFC"/><rect x="140" y="60" width="120" height="160" rx="55" fill="#475569" stroke="#334155" stroke-width="3"/><path d="M 200 60 L 200 110" stroke="#94A3B8" stroke-width="3"/><rect x="194" y="80" width="12" height="24" rx="6" fill="#1E293B"/><text x="200" y="265" font-family="Arial, sans-serif" font-size="14" fill="#64748B" text-anchor="middle">Foto Barang: Mouse Wireless</text></svg>',
            'items/kacamata.svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300" width="400" height="300"><rect width="400" height="300" fill="#F1F5F9"/><rect x="100" y="110" width="80" height="55" rx="10" fill="#E2E8F0" stroke="#0F172A" stroke-width="6"/><rect x="220" y="110" width="80" height="55" rx="10" fill="#E2E8F0" stroke="#0F172A" stroke-width="6"/><path d="M 180 130 C 190 120, 210 120, 220 130" fill="none" stroke="#0F172A" stroke-width="6"/><path d="M 100 125 L 60 110 M 300 125 L 340 110" stroke="#0F172A" stroke-width="5"/><text x="200" y="250" font-family="Arial, sans-serif" font-size="14" fill="#64748B" text-anchor="middle">Foto Barang: Kacamata Frame Hitam</text></svg>',
            'proofs/foto-dompet-pribadi.svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300" width="400" height="300"><rect width="400" height="300" fill="#F8FAFC"/><rect x="70" y="40" width="260" height="180" rx="8" fill="#FEF3C7" stroke="#F59E0B" stroke-width="2"/><text x="200" y="110" font-family="Arial, sans-serif" font-size="16" font-weight="bold" fill="#92400E" text-anchor="middle">BUKTI KEPEMILIKAN PRIBADI</text><text x="200" y="140" font-family="Arial, sans-serif" font-size="13" fill="#B45309" text-anchor="middle">Foto lama dompet di atas meja belajar</text><text x="200" y="165" font-family="Arial, sans-serif" font-size="12" fill="#78350F" text-anchor="middle">Memperlihatkan inisial N.P. &amp; jahitan biru</text><text x="200" y="260" font-family="Arial, sans-serif" font-size="13" fill="#64748B" text-anchor="middle">Bukti Privat Pengaju: foto-dompet-pribadi.jpg</text></svg>',
        ];

        foreach ($svgs as $path => $content) {
            Storage::disk('public')->put($path, $content);
        }
    }
}
