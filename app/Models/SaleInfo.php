<?php

namespace App\Models;

use App\Observers\SaleInfoObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleInfo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'number',
        'sale_id',
        'pay_amount',
        'change',
    ];
    protected $casts = [
        'id' => 'string',
    ];

    public static function boot(): void
    {
        parent::boot();
        self::observe(SaleInfoObserver::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sales::class, 'sale_id', 'id');
    }
}
