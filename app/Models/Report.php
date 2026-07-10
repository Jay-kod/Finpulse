<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Shared\Core\Traits\Auditable;

class Report extends Model
{
    use HasFactory, Auditable;
    protected $fillable = [
        'title',
        'description',
        'parameters',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'parameters' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
