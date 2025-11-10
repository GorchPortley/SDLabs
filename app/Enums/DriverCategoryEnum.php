<?php

namespace App\Enums;

enum DriverCategoryEnum: string
{
    case SUBWOOFER = 'Subwoofer';
    case MIDBASS = 'Midbass';
    case WOOFER = 'Woofer';
    case MIDRANGE = 'Midrange';
    case COAXIAL = 'Coaxial';
    case TWEETER = 'Tweeter';
    case WIDEBAND = 'Wideband';
    case FULLRANGE = 'Fullrange';
    case PASSIVE_RADIATOR = 'Passive Radiator';
    case COMPRESSION_DRIVER = 'Compression Driver';
    case EXCITER = 'Exciter';
    case OTHER = 'Other';
}
