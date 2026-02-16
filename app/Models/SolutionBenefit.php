<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * SolutionBenefit Model
 *
 * Represents a benefit of a solution category.
 *
 * @property int $id
 * @property int $solution_id
 * @property string $benefit_text
 * @property int $order
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read Solution $solution
 */
class SolutionBenefit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'solution_id',
        'benefit_text',
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
     * Get the solution that owns this benefit.
     *
     * @return BelongsTo<Solution, SolutionBenefit>
     */
    public function solution(): BelongsTo
    {
        return $this->belongsTo(Solution::class);
    }
}
