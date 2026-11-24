<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LostReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_code',
        'user_id',
        'category_id',
        'item_name',
        'lost_date',
        'lost_time',
        'lost_location',
        'public_description',
        'private_features',
        'contact_phone',
        'image_path',
        'status',
        'matched_found_item_id',
        'officer_notes',
    ];

    protected function casts(): array
    {
        return [
            'lost_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function matchedFoundItem(): BelongsTo
    {
        return $this->belongsTo(FoundItem::class, 'matched_found_item_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'menunggu' => 'Laporan terkirim',
            'diproses' => 'Sedang diperiksa security',
            'cocok' => 'Terdapat barang berpotensi cocok',
            'selesai' => 'Laporan selesai / Ditemukan',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'menunggu' => 'slate',
            'diproses' => 'amber',
            'cocok' => 'blue',
            'selesai' => 'green',
            'dibatalkan' => 'red',
            default => 'slate',
        };
    }
}
