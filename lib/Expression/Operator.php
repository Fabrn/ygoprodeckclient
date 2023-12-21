<?php

namespace YgoProDeckClient\Expression;

enum Operator: string
{
    case Equals = 'equals';
    case Lighter = 'lt';
    case LighterOrEquals = 'lte';
    case Greater = 'gt';
    case GreaterOrEquals = 'gte';
    case Contains = 'contains';
    case In = 'in';
    case All = 'all';
}