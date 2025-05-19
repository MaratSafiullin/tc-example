<?php

namespace App\Models\ThemeText;

enum Sentiment: string
{
    case Positive = 'positive';
    case Neutral  = 'neutral';
    case Negative = 'negative';
}
