<?php

namespace YgoProDeckClient\Expression;

use YgoProDeckClient\Attribute\AvailableOperators;

enum ExpressionCriteria: string
{
    #[AvailableOperators([
        Operator::Equals,
        Operator::Greater,
        Operator::GreaterOrEquals,
        Operator::Lighter,
        Operator::LighterOrEquals
    ])]
    case Atk = 'atk';

    #[AvailableOperators([
        Operator::Equals,
        Operator::Greater,
        Operator::GreaterOrEquals,
        Operator::Lighter,
        Operator::LighterOrEquals
    ])]
    case Def = 'def';

    #[AvailableOperators([
        Operator::Equals,
        Operator::Greater,
        Operator::GreaterOrEquals,
        Operator::Lighter,
        Operator::LighterOrEquals
    ])]
    case Level = 'level';

    #[AvailableOperators([
        Operator::Equals,
        Operator::In
    ])]
    case Race = 'race';

    #[AvailableOperators([
        Operator::Equals,
        Operator::In
    ])]
    case Type = 'type';

    #[AvailableOperators([
        Operator::Equals,
        Operator::In
    ])]
    case Attribute = 'attribute';

    #[AvailableOperators([
        Operator::Equals,
        Operator::Contains
    ])]
    case Name = 'name';

    #[AvailableOperators([
        Operator::Equals
    ])]
    case Link = 'link';

    #[AvailableOperators([
        Operator::Equals,
        Operator::All
    ])]
    case LinkMarker = 'linkmarker';

    #[AvailableOperators([
        Operator::Equals
    ])]
    case Archetype = 'archetype';

    #[AvailableOperators([
        Operator::Equals
    ])]
    case Scale = 'scale';

    #[AvailableOperators([
        Operator::Equals
    ])]
    case Set = 'cardset';

    #[AvailableOperators([
        Operator::Equals
    ])]
    case Sort = 'sort';

    #[AvailableOperators([
        Operator::Equals
    ])]
    case Num = 'num';

    #[AvailableOperators([
        Operator::Equals
    ])]
    case Offset = 'offset';
}