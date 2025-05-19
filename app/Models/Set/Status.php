<?php

namespace App\Models\Set;

enum Status: string
{
    case Draft          = 'draft';
    case Processing     = 'processing';
    case Completed      = 'completed';
    case CallbackSent   = 'callback_sent';
    case CallbackFailed = 'callback_failed';
}
