<?php

namespace App\Models;

use App\Observers\HistoryBalanceCustomerObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryBalanceCustomer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'balance_id',
        'number',
        'nominal',
        'start_balance',
        'end_balance',
        'status',
        'note',
    ];
    protected $casts = [
        'id' => 'string',
    ];

    public static function boot(): void
    {
        parent::boot();
        self::observe(HistoryBalanceCustomerObserver::class);
    }
}
