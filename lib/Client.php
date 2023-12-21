<?php

namespace YgoProDeckClient;

use YgoProDeckClient\Client\ArchetypeClient;
use YgoProDeckClient\Client\CardClient;
use YgoProDeckClient\Client\CardSetClient;
use YgoProDeckClient\Client\RandomCardClient;

class Client
{
    final public const BASE_URI = "https://db.ygoprodeck.com/api";

    final public const PARAM_LANGUAGE = 'language';
    final public const PARAM_API_VERSION = 'api_version';

    public CardClient $cards;
    public RandomCardClient $randomCards;
    public CardSetClient $cardSets;
    public ArchetypeClient $archetypes;

    public function __construct(array $options = [])
    {
        $options = \array_merge($this->getDefaultOptions(), $options);

        $this->cards = new CardClient($options);
        $this->randomCards = new RandomCardClient($options);
        $this->cardSets = new CardSetClient($options);
        $this->archetypes = new ArchetypeClient($options);
    }

    private function getDefaultOptions(): array
    {
        return [
            self::PARAM_LANGUAGE => 'en',
            self::PARAM_API_VERSION => 'v7'
        ];
    }
}