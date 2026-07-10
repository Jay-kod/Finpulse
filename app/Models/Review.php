<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    /** @use HasFactory<\Database\Factories\ReviewFactory> */
    use HasFactory, \App\Shared\Core\Traits\Auditable, SoftDeletes;

    protected $fillable = [
        'dataset_id',
        'source_id',
        'author_name',
        'rating',
        'content',
        'cleaned_content',
        'detected_language',
        'word_count',
        'processed_status',
        'ml_status',
        'sentiment_status',
        'topic',
        'intent',
        'is_bug',
        'sentiment_positive',
        'sentiment_negative',
        'sentiment_neutral',
        'sentiment_compound',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'word_count' => 'integer',
            'is_bug' => 'boolean',
            'sentiment_positive' => 'float',
            'sentiment_negative' => 'float',
            'sentiment_neutral' => 'float',
            'sentiment_compound' => 'float',
            'published_at' => 'datetime',
        ];
    }

    public function dataset(): BelongsTo
    {
        return $this->belongsTo(Dataset::class);
    }
}
