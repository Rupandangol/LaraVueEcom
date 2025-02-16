<?php

namespace App\Enums;

enum Moods: string
{
    case HAPPY = 'happy';
    case SAD = 'sad';
    case NEUTRAL = 'neutral';
    case ANGRY = 'angry';
    case EXCITED = 'excited';
    case TIRED = 'tired';
}
