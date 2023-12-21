<?php

namespace YgoProDeckClient\Model;

use YgoProDeckClient\Attribute\ModelProperty;

class Archetype extends Model
{
    public function __construct(
        #[ModelProperty(name: 'archetype_name')]
        public readonly string $name
    ) {
    }
}