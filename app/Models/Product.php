<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Product Model
 *
 * Represents an LED display product with its specifications, features, and applications.
 *
 * @property int $id
 * @property string $name
 * @property string $series
 * @property string $category
 * @property float $pixel_pitch
 * @property int $brightness_min
 * @property int $brightness_max
 * @property string $cabinet_size
 * @property string $weight
 * @property string $power_consumption
 * @property string $protection_rating
 * @property string $lifespan
 * @property string $operating_temp
 * @property string $environment
 * @property string|null $price
 * @property string|null $tagline
 * @property string|null $description
 * @property string|null $image
 * @property array|null $gallery
 * @property string|null $specs_pdf
 * @property string|null $datasheet_pdf
 * @property string|null $cad_drawings
 * @property string $slug
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read string $brightness_range Formatted brightness range (e.g., "6000-7500 nits")
 * @property-read \Illuminate\Database\Eloquent\Collection<ProductFeature> $features
 * @property-read \Illuminate\Database\Eloquent\Collection<ProductApplication> $applications
 * @property-read \Illuminate\Database\Eloquent\Collection<ProductSpecification> $specifications
 * @property-read \Illuminate\Database\Eloquent\Collection<Solution> $solutions
 * @property-read \Illuminate\Database\Eloquent\Collection<CaseStudy> $caseStudies
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Product active() Scope to filter only active products
 * @method static \Illuminate\Database\Eloquent\Builder|Product byCategory(string $category) Scope to filter by category
 */
class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'series',
        'category',
        'pixel_pitch',
        'brightness_min',
        'brightness_max',
        'cabinet_size',
        'weight',
        'power_consumption',
        'protection_rating',
        'lifespan',
        'operating_temp',
        'environment',
        'price',
        'tagline',
        'description',
        'image',
        'gallery',
        'specs_pdf',
        'datasheet_pdf',
        'cad_drawings',
        'slug',
        'is_active',
        'view_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'gallery' => 'array',
        'is_active' => 'boolean',
        'pixel_pitch' => 'decimal:2',
        'brightness_min' => 'integer',
        'brightness_max' => 'integer',
        'view_count' => 'integer',
    ];

    /**
     * Boot the model.
     * Auto-generates slug from name if not provided.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    /**
     * Get the product's features.
     *
     * @return HasMany<ProductFeature>
     */
    public function features(): HasMany
    {
        return $this->hasMany(ProductFeature::class);
    }

    /**
     * Get the product's applications.
     *
     * @return HasMany<ProductApplication>
     */
    public function applications(): HasMany
    {
        return $this->hasMany(ProductApplication::class);
    }

    /**
     * Get the product's specifications.
     *
     * @return HasMany<ProductSpecification>
     */
    public function specifications(): HasMany
    {
        return $this->hasMany(ProductSpecification::class);
    }

    /**
     * Get the solutions that recommend this product.
     *
     * @return BelongsToMany<Solution>
     */
    public function solutions(): BelongsToMany
    {
        return $this->belongsToMany(Solution::class, 'solution_product');
    }

    /**
     * Get the case studies that use this product.
     *
     * @return BelongsToMany<CaseStudy>
     */
    public function caseStudies(): BelongsToMany
    {
        return $this->belongsToMany(CaseStudy::class, 'case_study_product');
    }

    /**
     * Scope a query to only include active products.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter products by category.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $category
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to get most viewed products.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMostViewed($query, int $limit = 10)
    {
        return $query->orderByDesc('view_count')->limit($limit);
    }

    /**
     * Increment the product view count without updating timestamps.
     */
    public function incrementViewCount(): void
    {
        $this->timestamps = false;
        $this->increment('view_count');
        $this->timestamps = true;
    }

    /**
     * Get the formatted brightness range.
     *
     * Example: "6000-7500 nits"
     *
     * @return string
     */
    public function getBrightnessRangeAttribute(): string
    {
        return "{$this->brightness_min}-{$this->brightness_max} nits";
    }

    /**
     * Get full URL for the product image.
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? app(\App\Services\ImageService::class)->getUrl($this->image) : null;
    }

    /**
     * Get responsive image URLs for all thumbnail sizes.
     */
    public function getImageResponsiveAttribute(): array
    {
        return $this->image ? app(\App\Services\ImageService::class)->getResponsiveUrls($this->image) : [];
    }

    /**
     * Get full URLs for the product gallery images.
     */
    public function getGalleryUrlsAttribute(): array
    {
        $service = app(\App\Services\ImageService::class);
        return array_map(fn ($path) => $service->getUrl($path), $this->gallery ?? []);
    }

    /**
     * Get responsive URLs for each gallery image.
     */
    public function getGalleryResponsiveAttribute(): array
    {
        $service = app(\App\Services\ImageService::class);
        return array_map(fn ($path) => $service->getResponsiveUrls($path), $this->gallery ?? []);
    }

    /**
     * Get full URL for the product spec sheet PDF.
     */
    public function getSpecsPdfUrlAttribute(): ?string
    {
        if (!$this->specs_pdf) {
            return null;
        }
        
        $url = \Illuminate\Support\Facades\Storage::disk('public')->url($this->specs_pdf);
        
        // Ensure absolute URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $url = config('app.url') . $url;
        }
        
        return $url;
    }

    /**
     * Get full URL for the full technical datasheet PDF.
     */
    public function getDatasheetPdfUrlAttribute(): ?string
    {
        if (!$this->datasheet_pdf) {
            return null;
        }
        
        $url = \Illuminate\Support\Facades\Storage::disk('public')->url($this->datasheet_pdf);
        
        // Ensure absolute URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $url = config('app.url') . $url;
        }
        
        return $url;
    }

    /**
     * Get full URL for the CAD drawings file.
     */
    public function getCadDrawingsUrlAttribute(): ?string
    {
        if (!$this->cad_drawings) {
            return null;
        }
        
        $url = \Illuminate\Support\Facades\Storage::disk('public')->url($this->cad_drawings);
        
        // Ensure absolute URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $url = config('app.url') . $url;
        }
        
        return $url;
    }
}
