<?php

namespace YgoProDeckClient\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class SubModel
{
    public function __construct(
        public readonly string $root,
        public readonly ?string $model = null,
        public readonly bool $collection = false
    ) {
    }
}