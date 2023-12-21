<?php

namespace YgoProDeckClient\Enum;

enum LinkMarker: string
{
    case Top = 'Top';
    case Bottom = 'Bottom';
    case Left = 'Left';
    case Right = 'Right';
    case BottomRight = 'Bottom-Right';
    case BottomLeft = 'Bottom-Left';
    case TopRight = 'Top-Right';
    case TopLeft = 'Top-Left';
}