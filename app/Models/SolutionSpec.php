<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * SolutionSpec Model
 *
 * Represents a technical specification for a solution category (e.g., "Pixel Pitch" => "2.5 – 6mm").
 *
 * @property int $id
 * @property int $solution_id
 * @property string $label
 * @property string $value
 * @property int $order
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read Solution $solution
 */
class SolutionSpec extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'solution_id',
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
     * Get the solution that owns this spec.
     *
     * @return BelongsTo<Solution, SolutionSpec>
     */
    public function solution(): BelongsTo
    {
        return $this->belongsTo(Solution::class);
    }
}
