<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_code',
        'found_item_id',
        'user_id',
        'chronology',
        'private_proof_features',
        'contact_phone',
        'proof_image_path',
        'status',
        'verified_by',
        'verified_at',
        'officer_notes',
        'handover_notes',
        'handed_over_at',
    ];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
            'handed_over_at' => 'datetime',
        ];
    }

    public function foundItem(): BelongsTo
    {
        return $this->belongsTo(FoundItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu verifikasi',
            'approved' => 'Klaim disetujui (Belum diambil)',
            'rejected' => 'Klaim ditolak',
            'completed' => 'Serah terima selesai',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'amber',
            'approved' => 'green',
            'rejected' => 'red',
            'completed' => 'blue',
            default => 'slate',
        };
    }
}
