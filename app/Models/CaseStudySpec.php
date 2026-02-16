<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * CaseStudySpec Model
 *
 * Represents a technical specification for a case study (e.g., "Display Size" => "42 m²").
 *
 * @property int $id
 * @property int $case_study_id
 * @property string $label
 * @property string $value
 * @property int $order
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read CaseStudy $caseStudy
 */
class CaseStudySpec extends Model
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
     * Get the case study that owns this spec.
     *
     * @return BelongsTo<CaseStudy, CaseStudySpec>
     */
    public function caseStudy(): BelongsTo
    {
        return $this->belongsTo(CaseStudy::class);
    }
}
