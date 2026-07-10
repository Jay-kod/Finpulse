<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FintechApp extends Model
{
    /** @use HasFactory<\Database\Factories\FintechAppFactory> */
    use HasFactory, SoftDeletes, \App\Shared\Core\Traits\Auditable;

    protected $fillable = [
        'name',
        'package_name',
        'playstore_id',
        'appstore_id',
        'platform',
        'downloads',
        'average_rating',
        'description',
        'logo_url',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'average_rating' => 'decimal:2',
            'downloads' => 'integer',
        ];
    }

    public function datasets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Dataset::class);
    }

    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Review::class, Dataset::class);
    }
}
