<?php

namespace App\Models;

use App\Observers\PurchaseOrderPaymentObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderPayment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'purchase_order_id',
        'sumber_dana',
        'amount_purchase_order',
        'amount'
    ];
    protected $casts = [
        'id' => 'string',
    ];

    public static function boot(): void
    {
        parent::boot();
        self::observe(PurchaseOrderPaymentObserver::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'id');
    }
}
