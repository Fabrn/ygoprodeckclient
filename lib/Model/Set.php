<?php

namespace YgoProDeckClient\Model;

use YgoProDeckClient\Attribute\ModelProperty;

class Set extends Model
{
    public function __construct(
        public readonly string $name,
        public readonly string $code,
        // Not set for card set collection
        public readonly ?string $rarity = null,
        // Not set for card set collection
        #[ModelProperty(name: 'price', deserializationMethod: 'deserializePrice')]
        public readonly ?float $price = null,
        // Not set for card set collection
        public readonly ?string $rarityCode = null,
        public readonly ?string $image = null
    ) {
    }

    /**
     * Cleans up dollar signs or other currency signs that could appear in the price.
     * Most often it seems to appear for random cards.
     */
    public static function deserializePrice(array $data, string $value): float
    {
        return (float) \preg_replace('/[^0-9|(.,)]/', '', $value);
    }
}