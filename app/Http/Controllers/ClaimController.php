<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\FoundItem;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ClaimController extends Controller
{
    public function create(FoundItem $foundItem): View|RedirectResponse
    {
        // Don't allow claiming already returned or archived items
        if ($foundItem->status === 'dikembalikan' || $foundItem->status === 'diarsipkan') {
            return redirect()->route('catalog.show', $foundItem)
                ->with('error', 'Barang ini telah diserahkan kepada pemilik atau telah diarsipkan.');
        }

        // Check if user already has an active pending/approved claim on this item
        $existingClaim = Claim::where('found_item_id', $foundItem->id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->first();

        if ($existingClaim) {
            return redirect()->route('claims.show', $existingClaim)
                ->with('info', 'Anda sudah memiliki riwayat klaim untuk barang ini.');
        }

        return view('claims.create', compact('foundItem'));
    }

    public function store(Request $request, FoundItem $foundItem): RedirectResponse
    {
        $validated = $request->validate([
            'chronology' => ['required', 'string', 'min:10'],
            'private_proof_features' => ['required', 'string', 'min:10'],
            'contact_phone' => ['required', 'string', 'min:10', 'max:15'],
            'proof_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ], [
            'chronology.required' => 'Kronologi atau perkiraan kehilangan wajib dijelaskan.',
            'private_proof_features.required' => 'Ciri khusus privat wajib diisi untuk dicocokkan dengan catatan rahasia inventaris.',
            'contact_phone.required' => 'Nomor kontak WhatsApp aktif wajib diisi.',
            'contact_phone.min' => 'Nomor kontak minimal 10 digit.',
            'contact_phone.max' => 'Nomor kontak maksimal 15 digit.',
            'proof_image.max' => 'Ukuran file foto bukti maksimal 4MB.',
        ]);

        $proofImagePath = null;
        if ($request->hasFile('proof_image')) {
            $proofImagePath = $request->file('proof_image')->store('claim-proofs', 'public');
        }

        // Generate Ticket code format: KLM-2026-XXXX
        $count = Claim::whereYear('created_at', now()->year)->count() + 1;
        $claimCode = sprintf('KLM-%s-%04d', now()->format('Y'), $count);

        $claim = Claim::create([
            'claim_code' => $claimCode,
            'found_item_id' => $foundItem->id,
            'user_id' => Auth::id(),
            'chronology' => $validated['chronology'],
            'private_proof_features' => $validated['private_proof_features'],
            'contact_phone' => $validated['contact_phone'],
            'proof_image_path' => $proofImagePath,
            'status' => 'pending',
        ]);

        // Notify officers
        $officers = User::where('role', 'petugas')->get();
        foreach ($officers as $officer) {
            Notification::create([
                'user_id' => $officer->id,
                'title' => 'Pengajuan Klaim Baru (' . $claimCode . ')',
                'message' => Auth::user()->name . ' mengajukan klaim atas barang "' . $foundItem->name . '" (' . $foundItem->item_code . ').',
                'type' => 'claim_submitted',
                'link_url' => route('officer.claims.show', $claim),
            ]);
        }

        return redirect()->route('claims.show', $claim)
            ->with('success', 'Pengajuan klaim berhasil dikirim dengan kode tiket ' . $claimCode . '. Harap tunggu verifikasi petugas.');
    }

    public function show(Claim $claim): View
    {
        // Student can only view their own claims
        if (Auth::user()->isMahasiswa() && $claim->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat klaim ini.');
        }

        $claim->load(['foundItem.category', 'user', 'verifier']);

        return view('claims.show', compact('claim'));
    }
}
