<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FoundItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code',
        'category_id',
        'name',
        'found_date',
        'found_time',
        'found_location',
        'public_description',
        'private_description',
        'storage_location',
        'image_path',
        'status',
        'created_by',
        'handed_over_to_user_id',
        'handed_over_at',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'found_date' => 'date',
            'handed_over_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function handedOverTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handed_over_to_user_id');
    }

    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }

    public function lostReports(): HasMany
    {
        return $this->hasMany(LostReport::class, 'matched_found_item_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'tersedia' => 'Barang tersedia',
            'diverifikasi' => 'Kepemilikan diverifikasi (Belum diambil)',
            'dikembalikan' => 'Sudah diserahkan / Selesai',
            'diarsipkan' => 'Diarsipkan',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'tersedia' => 'green',
            'diverifikasi' => 'amber',
            'dikembalikan' => 'blue',
            'diarsipkan' => 'slate',
            default => 'slate',
        };
    }
}
