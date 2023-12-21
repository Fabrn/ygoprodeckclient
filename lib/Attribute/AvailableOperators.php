<?php

namespace YgoProDeckClient\Attribute;

use YgoProDeckClient\Expression\Operator;

#[\Attribute(\Attribute::TARGET_CLASS_CONSTANT)]
class AvailableOperators
{
    public function __construct(
        /**
         * @var Operator|string $operators
         */
        public readonly array|string $operators
    ) {
    }
}