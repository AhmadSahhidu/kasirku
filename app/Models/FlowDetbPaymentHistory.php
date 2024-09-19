<?php

namespace App\Models;

use App\Observers\FlowDetbPaymentHistoryObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlowDetbPaymentHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'flow_debt_id',
        'tanggal',
        'payment_method',
        'amount',
        'paid_debt',
        'remaining_debt',
    ];
    protected $casts = [
        'id' => 'string',
    ];

    public static function boot(): void
    {
        parent::boot();
        self::observe(FlowDetbPaymentHistoryObserver::class);
    }

    public function debt(): BelongsTo
    {
        return $this->belongsTo(FlowDebt::class, 'flow_debt_id', 'id');
    }
}
