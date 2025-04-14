<?php

namespace App\Models;

use App\Types\Positions\PositionStatus;
use App\Types\Positions\PositionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Position extends Model
{
    protected $fillable = [
        'user_id',
        'base_amount',
        'amount',
        'price_per_gram',
        'type',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'type' => PositionType::class,
            'status' => PositionStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
