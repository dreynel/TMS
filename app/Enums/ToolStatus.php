<?php

namespace App\Enums;

enum ToolStatus: string
{
    case AVAILABLE = 'available';
    case IN_USE = 'in_use';
    case UNDER_MAINTENANCE = 'under_maintenance';
    case DAMAGED = 'damaged';
    case LOST = 'lost';
    case RETIRED = 'retired';

    public function label(): string
    {
        return match($this) {
            self::AVAILABLE => 'Available',
            self::IN_USE => 'In Use / Borrowed',
            self::UNDER_MAINTENANCE => 'Under Maintenance',
            self::DAMAGED => 'Damaged',
            self::LOST => 'Lost',
            self::RETIRED => 'Retired / Decommissioned',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::AVAILABLE => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            self::IN_USE => 'bg-blue-100 text-blue-800 border-blue-300',
            self::UNDER_MAINTENANCE => 'bg-amber-100 text-amber-800 border-amber-300',
            self::DAMAGED => 'bg-rose-100 text-rose-800 border-rose-300',
            self::LOST => 'bg-purple-100 text-purple-800 border-purple-300',
            self::RETIRED => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }
}
