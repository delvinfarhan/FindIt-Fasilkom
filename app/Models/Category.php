<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description',
    ];

    public function foundItems(): HasMany
    {
        return $this->hasMany(FoundItem::class);
    }

    public function lostReports(): HasMany
    {
        return $this->hasMany(LostReport::class);
    }

    public function civitasFoundReports(): HasMany
    {
        return $this->hasMany(CivitasFoundReport::class);
    }
}
