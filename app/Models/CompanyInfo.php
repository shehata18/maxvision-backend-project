<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * CompanyInfo Model
 *
 * Flexible key-value store for company information.
 * Stores JSON data for milestones, team members, certifications, partners, and stats.
 *
 * @property int $id
 * @property string $key
 * @property array $value
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * Usage examples:
 * - CompanyInfo::getMilestones() → [{year, title, description}, ...]
 * - CompanyInfo::getTeam() → [{name, role, bio, initials}, ...]
 * - CompanyInfo::getCertifications() → [{name, description}, ...]
 * - CompanyInfo::getPartners() → [{name, logo}, ...]
 * - CompanyInfo::getStats() → [{label, value}, ...]
 * - CompanyInfo::updateKey('milestones', [...]) → creates or updates entry
 */
class CompanyInfo extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company_info';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'value' => 'array',
        ];
    }

    /**
     * Get milestones data.
     *
     * @return array Array of milestone objects [{year, title, description}, ...]
     */
    public static function getMilestones(): array
    {
        $record = static::where('key', 'milestones')->first();
        return $record ? $record->value : [];
    }

    /**
     * Get team members data.
     *
     * @return array Array of team member objects [{name, role, bio, initials}, ...]
     */
    public static function getTeam(): array
    {
        $record = static::where('key', 'team_members')->first();
        return $record ? $record->value : [];
    }

    /**
     * Get certifications data.
     *
     * @return array Array of certification objects [{name, description}, ...]
     */
    public static function getCertifications(): array
    {
        $record = static::where('key', 'certifications')->first();
        return $record ? $record->value : [];
    }

    /**
     * Get partners data.
     *
     * @return array Array of partner objects [{name, logo}, ...]
     */
    public static function getPartners(): array
    {
        $record = static::where('key', 'partners')->first();
        return $record ? $record->value : [];
    }

    /**
     * Get company stats data.
     *
     * @return array Array of stat objects [{label, value}, ...]
     */
    public static function getStats(): array
    {
        $record = static::where('key', 'stats')->first();
        return $record ? $record->value : [];
    }

    /**
     * Update or create a company info entry by key.
     *
     * @param string $key The key to update
     * @param array $value The value to store
     * @return CompanyInfo
     */
    public static function updateKey(string $key, array $value): CompanyInfo
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
