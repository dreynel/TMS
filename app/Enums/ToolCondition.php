<?php

namespace App\Enums;

enum ToolCondition: string
{
    case NEW = 'new';
    case GOOD = 'good';
    case FAIR = 'fair';
    case NEEDS_REPAIR = 'needs_repair';
    case DAMAGED = 'damaged';

    public function label(): string
    {
        return match($this) {
            self::NEW => 'Brand New',
            self::GOOD => 'Good Condition',
            self::FAIR => 'Fair / Minor Wear',
            self::NEEDS_REPAIR => 'Needs Repair',
            self::DAMAGED => 'Damaged / Unusable',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::NEW => 'bg-teal-100 text-teal-800 border-teal-300',
            self::GOOD => 'bg-green-100 text-green-800 border-green-300',
            self::FAIR => 'bg-yellow-100 text-yellow-800 border-yellow-300',
            self::NEEDS_REPAIR => 'bg-orange-100 text-orange-800 border-orange-300',
            self::DAMAGED => 'bg-red-100 text-red-800 border-red-300',
        };
    }
}
