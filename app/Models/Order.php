<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'rush_images' => 'array',
    ];

    public function booster(): BelongsTo
    {
        return $this->belongsTo(Booster::class);
    }
}
