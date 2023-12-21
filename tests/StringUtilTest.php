<?php declare(strict_types=1);

namespace YgoProDeckClient\Tests;

use PHPUnit\Framework\TestCase;
use YgoProDeckClient\Util\StringUtil;

final class StringUtilTest extends TestCase
{
    public function testCamelToSnake(): void
    {
        $string = 'iAmAString';
        $snake = StringUtil::camelToSnake($string);

        $this->assertEquals('i_am_a_string', $snake);
    }
}