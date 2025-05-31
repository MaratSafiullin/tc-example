<?php

namespace App\ModesStates\Set;

enum StatusName: string
{
    case Draft           = 'draft';
    case Processing      = 'processing';
    case Completed       = 'completed';
    case CallbackSent    = 'callback_sent';
    case CallbackFailed  = 'callback_failed';
    case CallbackSkipped = 'callback_skipped';
}
