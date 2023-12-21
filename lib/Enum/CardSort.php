<?php

namespace YgoProDeckClient\Enum;

enum CardSort: string
{
    case Atk = "ATK";
    case Def = "DEF";
    case Name = "Name";
    case Type = "Type";
    case Level = "Level";
    case Id = "ID";
    case New = "New";
    case Relevance = "Relevance";
}