<?php

namespace App\Models;

use App\Observers\SaleObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Nette\Utils\Strings;
use PhpParser\Node\Expr\Cast\String_;

class Sales extends Model
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
        'customer_id',
        'discount',
        'total',
        'grand_total',
        'payment_method',
        'user_id',
        'store_id'
    ];
    protected $casts = [
        'id' => 'string',
    ];

    public static function generateNumber(): string
    {
        $date = date('Ymd');
        $numberMax = Sales::max('number');
        $numberMax = ($numberMax) ? (int)substr($numberMax, -3) : 0;
        $numberMax++;

        return 'INV' . $date . sprintf('%03d', $numberMax);
    }


    public static function boot(): void
    {
        parent::boot();
        self::observe(SaleObserver::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItems::class, 'sale_id', 'id');
    }
}
