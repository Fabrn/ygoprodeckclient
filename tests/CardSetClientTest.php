<?php

namespace YgoProDeckClient\Tests;

use PHPUnit\Framework\TestCase;
use YgoProDeckClient\Client;
use YgoProDeckClient\Exception\HttpClientResponseException;
use YgoProDeckClient\Model\CardSet;

class CardSetClientTest extends TestCase
{
    private ?Client $client = null;

    protected function setUp(): void
    {
        $this->client = new Client();
    }

    public function testGetAllCardSets(): void
    {
        $response = $this->client->cardSets->getAll();

        self::assertNotEmpty($response->data);
        self::assertInstanceOf(CardSet::class, $response->data[0]);

        /** @var CardSet $testSet */
        $testSet = $response->data[0];

        self::assertNull($testSet->id);
        self::assertNull($testSet->name);
        self::assertNotNull($testSet->numOfCards);
        self::assertGreaterThan(0, $testSet->numOfCards);
        self::assertNotNull($testSet->set->image);
        self::assertNotNull($testSet->set->name);
    }

    public function testFindCardSetByCode(): void
    {
        $response = $this->client->cardSets->findOneByCode('SDY-046');

        self::assertInstanceOf(CardSet::class, $response);
        self::assertNull($response->numOfCards);
        self::assertEquals(54652250, $response->id);
        self::assertEquals('Man-Eater Bug', $response->name);
        self::assertEquals('Starter Deck: Yugi', $response->set->name);
        self::assertEquals('SDY-046', $response->set->code);
        self::assertEquals('Common', $response->set->rarity);
        self::assertEquals(2.25, $response->set->price);
        self::assertNull($response->set->image);
    }

    public function testFindCardSetByNonExistingCode(): void
    {
        $this->expectException(HttpClientResponseException::class);

        $this->client->cardSets->findOneByCode('SDY-111');
    }
}