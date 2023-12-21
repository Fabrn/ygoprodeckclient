<?php

namespace YgoProDeckClient\Util;

class StringUtil
{
    public static function camelToSnake(string $input): string
    {
        return \strtolower(\preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }
}