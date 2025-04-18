<?php

namespace App\Models;

use App\Support\Parents\Models\ParentModel;
use App\Types\Positions\PositionStatus;
use App\Types\Positions\PositionType;
use Database\Factories\PositionFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\HasFactory<PositionFactory>
 */
class Position extends ParentModel
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
            'amount' => 'float',
            'type' => PositionType::class,
            'status' => PositionStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isOwner(int|User $user): bool
    {
        $userId = is_int($user) ? $user : $user->getKey();

        return $this->user_id === $userId;
    }
}
