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
        // 1. Ensure public storage directories exist
        Storage::disk('public')->makeDirectory('items');
        Storage::disk('public')->makeDirectory('proofs');

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
            'handed_over_at' => '2026-11-06 10:30:00',
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
}
