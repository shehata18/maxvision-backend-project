<?php

namespace App\Enums;

enum JobCategory: string
{
    case Engineering = 'engineering';
    case Sales = 'sales';
    case Marketing = 'marketing';
    case Operations = 'operations';
    case CustomerSupport = 'customer_support';
    case Finance = 'finance';
    case HumanResources = 'human_resources';
    case Design = 'design';

    /**
     * Get all options as an array for select inputs.
     */
    public static function options(): array
    {
        return [
            self::Engineering->value => 'Engineering',
            self::Sales->value => 'Sales',
            self::Marketing->value => 'Marketing',
            self::Operations->value => 'Operations',
            self::CustomerSupport->value => 'Customer Support',
            self::Finance->value => 'Finance',
            self::HumanResources->value => 'Human Resources',
            self::Design->value => 'Design',
        ];
    }

    /**
     * Get the label for this enum value.
     */
    public function label(): string
    {
        return match ($this) {
            self::Engineering => 'Engineering',
            self::Sales => 'Sales',
            self::Marketing => 'Marketing',
            self::Operations => 'Operations',
            self::CustomerSupport => 'Customer Support',
            self::Finance => 'Finance',
            self::HumanResources => 'Human Resources',
            self::Design => 'Design',
        };
    }
}
