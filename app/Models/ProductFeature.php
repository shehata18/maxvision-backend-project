<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ProductFeature Model
 *
 * Represents a feature of a product (e.g., "High Brightness", "Weather Resistant").
 *
 * @property int $id
 * @property int $product_id
 * @property string $icon
 * @property string $title
 * @property string $description
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read Product $product
 */
class ProductFeature extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'icon',
        'title',
        'description',
    ];

    /**
     * Get the product that owns this feature.
     *
     * @return BelongsTo<Product, ProductFeature>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
