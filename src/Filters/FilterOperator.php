<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters;

enum FilterOperator: string
{
    case Equals = 'eq';
    case NotEquals = 'not_equals';
    case GreaterThan = 'greater_than';
    case GreaterThanOrEqual = 'gte';
    case LessThan = 'less_than';
    case LessThanOrEqual = 'lte';
    case Contains = 'contains';
    case NotContains = 'not_contains';
    case StartsWith = 'starts_with';
    case EndsWith = 'ends_with';
    case IsOneOf = 'is_one_of';
    case IsNoneOf = 'is_none_of';
    case IsEmpty = 'is_empty';
    case IsNotEmpty = 'is_not_empty';

    public function label(): string
    {
        return match ($this) {
            self::Equals => __('Equals'),
            self::NotEquals => __('Does not equal'),
            self::GreaterThan => __('Greater than'),
            self::GreaterThanOrEqual => __('Greater than or equal'),
            self::LessThan => __('Less than'),
            self::LessThanOrEqual => __('Less than or equal'),
            self::Contains => __('Contains'),
            self::NotContains => __('Does not contain'),
            self::StartsWith => __('Starts with'),
            self::EndsWith => __('Ends with'),
            self::IsOneOf => __('Is one of'),
            self::IsNoneOf => __('Is none of'),
            self::IsEmpty => __('Is empty'),
            self::IsNotEmpty => __('Is not empty'),
        };
    }
}
