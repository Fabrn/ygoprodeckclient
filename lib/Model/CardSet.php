<?php

namespace YgoProDeckClient\Model;

use YgoProDeckClient\Attribute\SubModel;

class CardSet extends Model
{
    public function __construct(
        #[SubModel(root: 'set_')]
        public readonly Set $set,
        // Not set for card set collection
        public readonly ?int $id = null,
        // Not set for card set collection
        public readonly ?string $name = null,
        public readonly ?int $numOfCards = null,
        public readonly ?\DateTimeInterface $tcgDate = null,
    ) {
    }
}