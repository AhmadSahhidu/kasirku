<?php

namespace App\Models;

use App\Observers\CashObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cash extends Model
{
    use HasFactory;


    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'number',
        'type_cash',
        'type_cash_out',
        'amount',
        'note',
        'store_id',
        'tgl',
        'user_id'
    ];
    protected $casts = [
        'id' => 'string',
    ];

    public static function boot(): void
    {
        parent::boot();
        self::observe(CashObserver::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
