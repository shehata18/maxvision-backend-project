<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Solution Model
 *
 * Represents a solution category (e.g., "Retail Digital Signage", "Outdoor Advertising").
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string|null $tagline
 * @property string|null $description
 * @property string $category
 * @property string|null $image
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<SolutionBenefit> $benefits
 * @property-read \Illuminate\Database\Eloquent\Collection<SolutionSpec> $specs
 * @property-read \Illuminate\Database\Eloquent\Collection<Product> $recommendedProducts
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Solution active() Scope to filter only active solutions
 * @method static \Illuminate\Database\Eloquent\Builder|Solution byCategory(string $category) Scope to filter by category
 */
class Solution extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'title',
        'tagline',
        'description',
        'category',
        'image',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Boot the model.
     * Auto-generates slug from title if not provided.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Solution $solution) {
            if (empty($solution->slug)) {
                $solution->slug = Str::slug($solution->title);
            }
        });
    }

    /**
     * Get the solution's benefits.
     *
     * @return HasMany<SolutionBenefit>
     */
    public function benefits(): HasMany
    {
        return $this->hasMany(SolutionBenefit::class);
    }

    /**
     * Get the solution's specifications.
     *
     * @return HasMany<SolutionSpec>
     */
    public function specs(): HasMany
    {
        return $this->hasMany(SolutionSpec::class);
    }

    /**
     * Get the recommended products for this solution.
     *
     * @return BelongsToMany<Product>
     */
    public function recommendedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'solution_product')
            ->withPivot('display_name', 'series', 'pitch', 'brightness', 'order')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include active solutions.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter solutions by category.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $category
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
