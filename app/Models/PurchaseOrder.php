<?php

namespace App\Models;

use App\Observers\PurchaseOrderObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrder extends Model
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
        'supplier_id',
        'grand_total',
        'due_date',
        'status',
        'store_id',
        'user_id'
    ];
    protected $casts = [
        'id' => 'string',
    ];

    public static function boot(): void
    {
        parent::boot();
        self::observe(PurchaseOrderObserver::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
}
