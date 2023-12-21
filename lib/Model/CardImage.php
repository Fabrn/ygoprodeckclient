<?php

namespace YgoProDeckClient\Model;

class CardImage extends Model
{
    public function __construct(
        public readonly string $url,
        public readonly string $urlSmall,
        // Cropped is missing for random cards
        public readonly ?string $urlCropped = null
    ) {
    }
}