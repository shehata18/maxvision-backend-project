<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * CaseStudy Model
 *
 * Represents a project case study showcasing LED display installations.
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string $client
 * @property string $industry
 * @property string $location
 * @property string $date
 * @property string|null $image
 * @property string|null $description
 * @property string|null $challenge
 * @property string|null $solution
 * @property bool $is_featured
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<CaseStudyMetric> $metrics
 * @property-read \Illuminate\Database\Eloquent\Collection<CaseStudySpec> $specs
 * @property-read \Illuminate\Database\Eloquent\Collection<Product> $products
 *
 * @method static \Illuminate\Database\Eloquent\Builder|CaseStudy active() Scope to filter only active case studies
 * @method static \Illuminate\Database\Eloquent\Builder|CaseStudy featured() Scope to filter only featured case studies
 * @method static \Illuminate\Database\Eloquent\Builder|CaseStudy byIndustry(string $industry) Scope to filter by industry
 */
class CaseStudy extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'case_studies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'title',
        'client',
        'industry',
        'location',
        'date',
        'image',
        'description',
        'challenge',
        'solution',
        'is_featured',
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
            'is_featured' => 'boolean',
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

        static::creating(function (CaseStudy $caseStudy) {
            if (empty($caseStudy->slug)) {
                $caseStudy->slug = Str::slug($caseStudy->title);
            }
        });
    }

    /**
     * Get the case study's metrics/KPIs.
     *
     * @return HasMany<CaseStudyMetric>
     */
    public function metrics(): HasMany
    {
        return $this->hasMany(CaseStudyMetric::class);
    }

    /**
     * Get the case study's technical specifications.
     *
     * @return HasMany<CaseStudySpec>
     */
    public function specs(): HasMany
    {
        return $this->hasMany(CaseStudySpec::class);
    }

    /**
     * Get the products used in this case study.
     *
     * @return BelongsToMany<Product>
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'case_study_product')
            ->withPivot('product_name')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include active case studies.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured case studies.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to filter case studies by industry.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $industry
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByIndustry($query, string $industry)
    {
        return $query->where('industry', $industry);
    }
}
