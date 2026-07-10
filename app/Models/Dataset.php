<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dataset extends Model
{
    /** @use HasFactory<\Database\Factories\DatasetFactory> */
    use HasFactory, SoftDeletes, \App\Shared\Core\Traits\Auditable;

    protected $fillable = [
        'fintech_app_id',
        'name',
        'source',
        'status',
        'record_count',
    ];

    protected function casts(): array
    {
        return [
            'record_count' => 'integer',
        ];
    }

    public function fintechApp(): BelongsTo
    {
        return $this->belongsTo(FintechApp::class);
    }

    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Review::class);
    }
}
