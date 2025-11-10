<?php

namespace App\Enums;

enum DesignCategoryEnum: string
{
    case SUBWOOFER = 'Subwoofer';
    case FULL_RANGE = 'Full-Range';
    case TWO_WAY = 'Two-Way';
    case THREE_WAY = 'Three-Way';
    case FOUR_WAY_PLUS = 'Four-Way+';
    case PORTABLE = 'Portable';
    case ESOTERIC = 'Esoteric';
    case SYSTEM = 'System';
}
