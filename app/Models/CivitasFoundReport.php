<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CivitasFoundReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_code',
        'user_id',
        'category_id',
        'item_name',
        'found_date',
        'found_location',
        'public_description',
        'private_features',
        'temp_storage',
        'contact_phone',
        'image_path',
        'status',
        'received_by',
        'received_at',
        'found_item_id',
        'storage_location',
        'officer_notes',
    ];

    protected function casts(): array
    {
        return [
            'found_date' => 'date',
            'received_at' => 'datetime',
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

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function foundItem(): BelongsTo
    {
        return $this->belongsTo(FoundItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'menunggu_penyerahan' => 'Menunggu penyerahan ke pos security',
            'diterima_security' => 'Telah diterima security & masuk inventaris',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }
}
