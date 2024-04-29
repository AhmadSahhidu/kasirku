<?php

namespace App\Models;

use App\Observers\SaleReturnItemObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleReturnItem extends Model
{
    use HasFactory;

    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sale_return_id',
        'sale_item_id',
        'qty',
        'total'
    ];
    protected $casts = [
        'id' => 'string',
    ];



    public static function boot(): void
    {
        parent::boot();
        self::observe(SaleReturnItemObserver::class);
    }

    public function items(): BelongsTo
    {
        return $this->belongsTo(SaleItems::class, 'sale_item_id', 'id');
    }
}
