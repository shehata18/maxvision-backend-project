<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * CaseStudyMetric Model
 *
 * Represents a KPI/metric for a case study (e.g., "Foot Traffic Increase" => "+34%").
 *
 * @property int $id
 * @property int $case_study_id
 * @property string $label
 * @property string $value
 * @property string $icon
 * @property int $order
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read CaseStudy $caseStudy
 */
class CaseStudyMetric extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'case_study_id',
        'label',
        'value',
        'icon',
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
     * Get the case study that owns this metric.
     *
     * @return BelongsTo<CaseStudy, CaseStudyMetric>
     */
    public function caseStudy(): BelongsTo
    {
        return $this->belongsTo(CaseStudy::class);
    }
}
