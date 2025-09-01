<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class StockMovement extends Model
{
    use HasFactory, HasUlids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id', 'warehouse_id', 'stock_item_id', 'type', 'quantity', 
        'reason', 'ref_type', 'ref_id', 'related_movement_id', 'user_id'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const TYPE_RECEIPT = 'receipt';
    const TYPE_WITHDRAWAL = 'withdrawal';
    const TYPE_ADJUSTMENT = 'adjustment';
    const TYPE_TRANSFER_IN = 'transfer_in';
    const TYPE_TRANSFER_OUT = 'transfer_out';

    public static function getTypes(): array
    {
        return [
            self::TYPE_RECEIPT,
            self::TYPE_WITHDRAWAL,
            self::TYPE_ADJUSTMENT,
            self::TYPE_TRANSFER_IN,
            self::TYPE_TRANSFER_OUT
        ];
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function stockItem(): BelongsTo
    {
        return $this->belongsTo(StockItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function relatedMovement()
    {
        return $this->belongsTo(StockMovement::class, 'related_movement_id');
    }

    public function isTransfer(): bool
    {
        return in_array($this->type, [self::TYPE_TRANSFER_IN, self::TYPE_TRANSFER_OUT]);
    }

    public function isReceipt(): bool
    {
        return $this->type === self::TYPE_RECEIPT;
    }
    
    public function isWithdrawal(): bool
    {
        return $this->type === self::TYPE_WITHDRAWAL;
    }
    
    public function isAdjustment(): bool
    {
        return $this->type === self::TYPE_ADJUSTMENT;
    }
    
    public function isTransferIn(): bool
    {
        return $this->type === self::TYPE_TRANSFER_IN;
    }
    
    public function isTransferOut(): bool
    {
        return $this->type === self::TYPE_TRANSFER_OUT;
    }
}
