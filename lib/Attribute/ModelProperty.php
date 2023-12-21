<?php

namespace YgoProDeckClient\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class ModelProperty
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $deserializationMethod = null,
        public readonly ?string $enumCollection = null
    ) {
    }
}