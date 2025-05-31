<?php

namespace App\ModesStates\Text;

enum StatusName: string
{
    case Created   = 'created';
    case Processed = 'processed';
    case Failed    = 'failed';
}
