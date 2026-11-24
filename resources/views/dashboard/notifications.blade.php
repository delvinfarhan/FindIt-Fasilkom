@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">Kabar Terbaru untukmu</h1>
            <p class="text-sm text-[#64748B] mt-1">Pemberitahuan lintas alur laporan, verifikasi klaim, dan serah terima barang.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-[#2563EB] hover:underline">
            &larr; Dashboard
        </a>
    </div>

    <!-- Notification List (Layar 20 Figma) -->
    <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs overflow-hidden">
        @if($notifications->count() > 0)
            <div class="divide-y divide-[#E2E8F0]">
                @foreach($notifications as $notif)
                    <div class="p-5 hover:bg-slate-50 transition flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 mt-0.5
                            @if(str_contains($notif->type, 'approved')) bg-emerald-100 text-[#16A34A]
                            @elseif(str_contains($notif->type, 'rejected')) bg-red-100 text-[#DC2626]
                            @elseif(str_contains($notif->type, 'matched')) bg-blue-100 text-[#2563EB]
                            @elseif(str_contains($notif->type, 'completed')) bg-blue-100 text-[#2563EB]
                            @else bg-slate-100 text-slate-600 @endif">
                            @if(str_contains($notif->type, 'approved') || str_contains($notif->type, 'completed'))
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @elseif(str_contains($notif->type, 'rejected'))
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <h3 class="text-sm font-bold text-[#0F172A]">{{ $notif->title }}</h3>
                                <span class="text-[11px] text-[#94A3B8] shrink-0">{{ $notif->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-[#64748B] leading-relaxed mb-2">{{ $notif->message }}</p>
                            @if($notif->link_url)
                                <a href="{{ $notif->link_url }}" class="inline-flex items-center gap-1 text-xs font-semibold text-[#2563EB] hover:underline">
                                    Buka Halaman Terkait &rarr;
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="p-4 border-t border-[#E2E8F0]">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
                <p class="text-sm text-[#64748B]">Belum ada pemberitahuan.</p>
            </div>
        @endif
    </div>
</div>
@endsection
