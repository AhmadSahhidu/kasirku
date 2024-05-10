<?php

namespace App\Models;

use App\Observers\SaleDebtObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleDebtPayment extends Model
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
        'due_date',
        'paid',
        'remaining',
        'status',
    ];
    protected $casts = [
        'id' => 'string',
    ];

    public static function boot(): void
    {
        parent::boot();
        self::observe(SaleDebtObserver::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sales::class, 'sale_id', 'id');
    }
}
