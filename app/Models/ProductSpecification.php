<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ProductSpecification Model
 *
 * Represents a flexible specification for a product (e.g., "refreshRate" => "3840Hz").
 *
 * @property int $id
 * @property int $product_id
 * @property string $spec_key
 * @property string $spec_value
 * @property int $order
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read Product $product
 */
class ProductSpecification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'spec_key',
        'spec_value',
        'order',
    ];

    /**
     * The default ordering for the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope('ordered', function ($query) {
            $query->orderBy('order');
        });
    }

    /**
     * Get the product that owns this specification.
     *
     * @return BelongsTo<Product, ProductSpecification>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
