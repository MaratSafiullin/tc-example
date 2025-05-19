<?php

namespace App\Models\Text;

enum Status: string
{
    case Created   = 'created';
    case Processed = 'processed';
    case Failed    = 'failed';
}
