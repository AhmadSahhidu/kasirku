<?php

namespace App\Models;

use App\Observers\BalanceStoreHistoryObserver;
use App\Observers\BalanceStoreObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceStoreHistory extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'balance_store_id',
        'balance_start',
        'balance_end',
        'amount',
        'type',
        'tgl',
        'description'
    ];
    protected $casts = [
        'id' => 'string',
    ];

    public static function boot(): void
    {
        parent::boot();
        self::observe(BalanceStoreHistoryObserver::class);
    }
}
