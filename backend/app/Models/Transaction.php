<?php

namespace App\Models;

use App\Support\Parents\Models\ParentModel;
use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\HasFactory<TransactionFactory>
 */
class Transaction extends ParentModel
{
    protected $fillable = [
        'buyer_position_id',
        'seller_position_id',
        'amount',
        'price_per_gram',
        'fee',
    ];

    public function buyerPosition(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'buyer_position_id');
    }

    public function sellerPosition(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'seller_position_id');
    }
}
