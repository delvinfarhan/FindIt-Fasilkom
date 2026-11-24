<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OfficerClaimController extends Controller
{
    public function index(Request $request): View
    {
        $query = Claim::with(['foundItem.category', 'user', 'verifier']);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('claim_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('identity_number', 'like', "%{$search}%");
                  })
                  ->orWhereHas('foundItem', function ($f) use ($search) {
                      $f->where('item_code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                  });
            });
        }

        $claims = $query->latest()->paginate(12)->withQueryString();

        return view('officer.claims.index', compact('claims', 'status', 'search'));
    }

    public function show(Claim $claim): View
    {
        $claim->load(['foundItem.category', 'user', 'verifier']);
        return view('officer.claims.show', compact('claim'));
    }

    public function approve(Request $request, Claim $claim): RedirectResponse
    {
        $validated = $request->validate([
            'officer_notes' => ['nullable', 'string'],
        ]);

        $claim->update([
            'status' => 'approved',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'officer_notes' => $validated['officer_notes'] ?? 'Bukti dan ciri kepemilikan cocok dengan data inventaris pos security. Pemohon dipersilakan mengambil barang di Pos Security.',
        ]);

        // Update item status to diverifikasi (Kepemilikan terverifikasi tapi fisik belum diambil)
        $claim->foundItem->update([
            'status' => 'diverifikasi',
        ]);

        // Send detailed notification to student (Layar 18 Desktop / Layar 17 Mobile)
        Notification::create([
            'user_id' => $claim->user_id,
            'title' => 'Klaim Disetujui: ' . $claim->foundItem->name,
            'message' => 'Selamat, klaim kepemilikan Anda (' . $claim->claim_code . ') telah disetujui! Silakan datang ke Pos Security FASILKOM (Senin - Jumat 08.00 - 16.00 WIB) dengan membawa KTM/Kartu Identitas asli untuk serah terima fisik.',
            'type' => 'claim_approved',
            'link_url' => route('claims.show', $claim),
        ]);

        return redirect()->route('officer.claims.show', $claim)
            ->with('success', 'Klaim ' . $claim->claim_code . ' berhasil disetujui. Status barang diubah menjadi "Kepemilikan sudah diverifikasi, menunggu pengambilan fisik di pos security".');
    }

    public function reject(Request $request, Claim $claim): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi secara jelas bagi pemohon.',
            'rejection_reason.min' => 'Alasan penolakan minimal 10 karakter.',
        ]);

        $claim->update([
            'status' => 'rejected',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'officer_notes' => $validated['rejection_reason'],
        ]);

        // If no other approved claims exist for this item, keep it 'tersedia'
        $otherApproved = Claim::where('found_item_id', $claim->found_item_id)
            ->where('id', '!=', $claim->id)
            ->where('status', 'approved')
            ->exists();

        if (! $otherApproved) {
            $claim->foundItem->update(['status' => 'tersedia']);
        }

        // Notify student
        Notification::create([
            'user_id' => $claim->user_id,
            'title' => 'Hasil Verifikasi Klaim ' . $claim->claim_code,
            'message' => 'Mohon maaf, klaim kepemilikan Anda belum dapat disetujui. Alasan: ' . $validated['rejection_reason'] . '. Anda dapat mengajukan ulang dengan bukti pendukung yang lebih lengkap.',
            'type' => 'claim_rejected',
            'link_url' => route('claims.show', $claim),
        ]);

        return redirect()->route('officer.claims.show', $claim)
            ->with('info', 'Klaim ' . $claim->claim_code . ' telah ditolak dan alasan tertulis telah dikirimkan ke pemohon.');
    }

    public function showHandover(Claim $claim): View
    {
        // Screen 08 & 19 Desktop: Physical handover verification
        if ($claim->status !== 'approved') {
            return redirect()->route('officer.claims.show', $claim)
                ->with('error', 'Klaim harus disetujui terlebih dahulu sebelum serah terima fisik.');
        }

        $claim->load(['foundItem.category', 'user']);
        return view('officer.claims.handover', compact('claim'));
    }

    public function completeHandover(Request $request, Claim $claim): RedirectResponse
    {
        $validated = $request->validate([
            'ktm_verified' => ['required', 'accepted'],
            'physical_item_checked' => ['required', 'accepted'],
            'recipient_identity_confirmed' => ['required', 'accepted'],
            'handover_notes' => ['nullable', 'string'],
        ], [
            'ktm_verified.accepted' => 'Pemeriksaan fisik KTM / Identitas asli wajib dicentang.',
            'physical_item_checked.accepted' => 'Pemeriksaan fisik barang dari lemari wajib dicentang.',
            'recipient_identity_confirmed.accepted' => 'Konfirmasi kehadiran pemilik sah wajib dicentang.',
        ]);

        $now = now();
        $officer = Auth::user();

        // 1. Update claim to completed
        $claim->update([
            'status' => 'completed',
            'handover_notes' => $validated['handover_notes'] ?? 'Serah terima fisik selesai di Pos Security FASILKOM. KTM asli terverifikasi.',
            'handed_over_at' => $now,
            'verified_by' => $officer->id,
        ]);

        // 2. Update found item status to dikembalikan
        $claim->foundItem->update([
            'status' => 'dikembalikan',
            'handed_over_to_user_id' => $claim->user_id,
            'handed_over_at' => $now,
        ]);

        // 3. Notify student
        Notification::create([
            'user_id' => $claim->user_id,
            'title' => 'Serah Terima Barang Selesai',
            'message' => 'Barang "' . $claim->foundItem->name . '" telah resmi diserahterimakan kepada Anda oleh petugas ' . $officer->name . '. Terima kasih telah menggunakan layanan FindIt FASILKOM.',
            'type' => 'claim_completed',
            'link_url' => route('claims.show', $claim),
        ]);

        return redirect()->route('officer.claims.receipt', $claim)
            ->with('success', 'Serah terima fisik berhasil dikonfirmasi! Bukti tanda terima digital telah diterbitkan.');
    }

    public function receipt(Claim $claim): View
    {
        // Screen 15 Desktop: Tanda bukti serah terima resmi
        $claim->load(['foundItem.category', 'user', 'verifier']);
        return view('officer.claims.receipt', compact('claim'));
    }
}
