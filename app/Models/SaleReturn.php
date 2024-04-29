<?php

namespace App\Models;

use App\Observers\SaleReturnObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaleReturn extends Model
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
        'sale_id',
        'status',
        'user_approval',
        'total',
        'user_id',
        'store_id'
    ];
    protected $casts = [
        'id' => 'string',
    ];

    public static function generateNumber(): string
    {
        $date = date('Ymd');
        $numberMax = SaleReturn::max('number');
        $numberMax = ($numberMax) ? (int)substr($numberMax, -3) : 0;
        $numberMax++;

        return 'RET' . $date . sprintf('%03d', $numberMax);
    }


    public static function boot(): void
    {
        parent::boot();
        self::observe(SaleReturnObserver::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    public function sales(): BelongsTo
    {
        return $this->belongsTo(Sales::class, 'sale_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function userApproval(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_approval', 'id');
    }

    public function returnItems(): HasMany
    {
        return $this->hasMany(SaleReturnItem::class, 'sale_return_id', 'id');
    }
}
