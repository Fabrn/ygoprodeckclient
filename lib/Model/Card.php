<?php

namespace YgoProDeckClient\Model;

use YgoProDeckClient\Attribute\ModelProperty;
use YgoProDeckClient\Attribute\SubModel;
use YgoProDeckClient\Enum\Attribute;
use YgoProDeckClient\Enum\FrameType;
use YgoProDeckClient\Enum\LinkMarker;
use YgoProDeckClient\Enum\Race;
use YgoProDeckClient\Enum\SpellType;
use YgoProDeckClient\Enum\TrapType;
use YgoProDeckClient\Enum\Type;

class Card extends Model
{
    public function __construct(
        public readonly int                     $id,
        public readonly string                  $name,
        public readonly string                  $desc,
        public readonly Type                    $type,
        #[ModelProperty(name: 'race', deserializationMethod: 'deserializeRace')]
        public readonly Race|SpellType|TrapType|string $race,
        public readonly int|string|null         $atk = null,
        public readonly int|string|null         $def = null,
        #[ModelProperty(name: 'linkval')]
        public readonly int|null                $link = null,
        #[ModelProperty(name: 'linkmarkers', enumCollection: LinkMarker::class)]
        /**
         * @var list<LinkMarker> $linkMarkers
         */
        public readonly array                   $linkMarkers = [],
        public readonly int|null                $scale = null,
        public readonly int|null                $level = null,
        public readonly FrameType|null          $frameType = null,
        public readonly Attribute|null          $attribute = null,
        #[ModelProperty(name: 'card_sets')]
        #[SubModel(root: 'set_', model: Set::class, collection: true)]
        /**
         * @var list<Set> $sets
         */
        public readonly array $sets = [],
        #[ModelProperty(name: 'card_images')]
        #[SubModel(root: 'image_', model: CardImage::class, collection: true)]
        /**
         * @var list<CardImage> $images
         */
        public readonly array $images = [],
    ) {
    }

    public static function deserializeRace(array $args, string $value): Race|SpellType|TrapType|string
    {
        return match ($args['type']) {
            Type::Spell => SpellType::from($value),
            Type::Trap => TrapType::from($value),
            Type::Skill => $value,
            default => Race::from($value)
        };
    }
}