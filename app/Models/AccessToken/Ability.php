<?php

namespace App\Models\AccessToken;

enum Ability: string
{
    case InternalApi = 'internal-api';
    case PublicApi   = 'public-api';
    case ServicesApi = 'services-api';
}
