<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ProductApplication Model
 *
 * Represents an application/use case for a product (e.g., "Billboards", "Control Rooms").
 *
 * @property int $id
 * @property int $product_id
 * @property string $name
 * @property int $order
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read Product $product
 */
class ProductApplication extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'name',
        'order',
    ];

    /**
     * The default ordering for the model.
     *
     * @var array<string, string>
     */
    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope('ordered', function ($query) {
            $query->orderBy('order');
        });
    }

    /**
     * Get the product that owns this application.
     *
     * @return BelongsTo<Product, ProductApplication>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
