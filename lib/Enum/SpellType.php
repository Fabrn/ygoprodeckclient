<?php

namespace YgoProDeckClient\Enum;

enum SpellType: string
{
    case Normal = "Normal";
    case Field = "Field";
    case Equip = "Equip";
    case Continuous = "Continuous";
    case QuickPlay = "Quick-Play";
    case Ritual = "Ritual";
}