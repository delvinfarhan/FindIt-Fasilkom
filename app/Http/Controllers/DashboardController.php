<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function student(): View
    {
        $user = Auth::user();

        $stats = [
            'reports_count' => $user->lostReports()->count(),
            'active_reports' => $user->lostReports()->whereIn('status', ['menunggu', 'diproses', 'cocok'])->count(),
            'claims_count' => $user->claims()->count(),
            'pending_claims' => $user->claims()->whereIn('status', ['pending', 'approved'])->count(),
            'found_reports_count' => $user->civitasFoundReports()->count(),
        ];

        $recentReports = $user->lostReports()->with(['category', 'matchedFoundItem'])->latest()->take(3)->get();
        $recentClaims = $user->claims()->with(['foundItem.category'])->latest()->take(3)->get();
        $recentCivitasReports = $user->civitasFoundReports()->with('category')->latest()->take(2)->get();
        $notifications = $user->notifications()->take(5)->get();

        return view('dashboard.student', compact(
            'user',
            'stats',
            'recentReports',
            'recentClaims',
            'recentCivitasReports',
            'notifications'
        ));
    }

    public function notifications(): View
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(15);

        // Mark all as read
        $user->notifications()->where('is_read', false)->update(['is_read' => true]);

        return view('dashboard.notifications', compact('notifications'));
    }

    public function profile(): View
    {
        $user = Auth::user();
        return view('dashboard.profile', compact('user'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone_number' => ['required', 'string', 'min:10', 'max:15'],
            'department' => ['nullable', 'string', 'max:100'],
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'phone_number.required' => 'Nomor WhatsApp wajib diisi.',
            'current_password.current_password' => 'Kata sandi saat ini tidak sesuai.',
            'new_password.min' => 'Kata sandi baru minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user->name = $validated['name'];
        $user->phone_number = $validated['phone_number'];
        $user->department = $validated['department'] ?? $user->department;

        if (! empty($validated['new_password'])) {
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return back()->with('success', 'Profil dan pengaturan berhasil diperbarui.');
    }
}
