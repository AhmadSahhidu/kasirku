<?php

namespace App\Models;

use App\Observers\BalanceStoreObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceStores extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'amount_in_hand',
        'amount_in_cashier',
        'amount_customer_debt',
        'store_id',
        'grand_total'
    ];
    protected $casts = [
        'id' => 'string',
    ];

    public static function boot(): void
    {
        parent::boot();
        self::observe(BalanceStoreObserver::class);
    }
}
