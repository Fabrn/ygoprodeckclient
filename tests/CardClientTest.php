<?php declare(strict_types=1);

namespace YgoProDeckClient\Tests;

use PHPUnit\Framework\TestCase;
use YgoProDeckClient\Client;
use YgoProDeckClient\Enum\LinkMarker;
use YgoProDeckClient\Enum\SpellType;
use YgoProDeckClient\Enum\TrapType;
use YgoProDeckClient\Enum\Type;
use YgoProDeckClient\Exception\HttpClientResponseException;
use YgoProDeckClient\Http\Pagination;
use YgoProDeckClient\Model\Card;

class CardClientTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = new Client();
    }

    public function testGetAllCardsContainingMagicianInName(): void
    {
        $response = $this->client->cards->getAllContainingName('Magician', limit: 4, page: 1);

        self::assertNotEmpty($response->data);
        self::assertInstanceOf(Card::class, $response->data[0]);
        self::assertStringContainsString('Magician', $response->data[0]->name);
        self::assertStringContainsString('Magician', $response->data[\array_key_last($response->data)]->name);
    }

    public function testGetAllWithAtk(): void
    {
        $response = $this->client->cards->getAllWithAtk(2000, limit: 4, page: 1);

        self::assertNotEmpty($response->data);
        self::assertInstanceOf(Card::class, $response->data[0]);
        self::assertEquals(2000, $response->data[0]->atk);
        self::assertEquals(2000, $response->data[\array_key_last($response->data)]->atk);
    }

    public function testGetAllWithDef(): void
    {
        $response = $this->client->cards->getAllWithDef(2000, limit: 4, page: 1);

        self::assertNotEmpty($response->data);
        self::assertInstanceOf(Card::class, $response->data[0]);
        self::assertEquals(2000, $response->data[0]->def);
        self::assertEquals(2000, $response->data[\array_key_last($response->data)]->def);
    }

    public function testGetAllWithLevel(): void
    {
        $response = $this->client->cards->getAllWithLevel(7, limit: 4, page: 1);

        self::assertNotEmpty($response->data);
        self::assertInstanceOf(Card::class, $response->data[0]);
        self::assertEquals(7, $response->data[0]->level);
        self::assertEquals(7, $response->data[\array_key_last($response->data)]->level);
    }

    public function testGetAllWithStats(): void
    {
        $response = $this->client->cards->getAllWithStats(atk: 2000, def: 1500, level: 4, limit: 4, page: 1);

        self::assertNotEmpty($response->data);
        self::assertInstanceOf(Card::class, $response->data[0]);
        self::assertEquals(2000, $response->data[0]->atk);
        self::assertEquals(1500, $response->data[0]->def);
        self::assertEquals(4, $response->data[0]->level);
        self::assertEquals(2000, $response->data[\array_key_last($response->data)]->atk);
        self::assertEquals(1500, $response->data[\array_key_last($response->data)]->def);
        self::assertEquals(4, $response->data[\array_key_last($response->data)]->level);
    }

    public function testGetAllContinuousTraps(): void
    {
        $response = $this->client->cards->getAllTrapsOfType(TrapType::Continuous, limit: 4, page: 1);

        self::assertNotEmpty($response->data);
        self::assertInstanceOf(Card::class, $response->data[0]);
        self::assertEquals(TrapType::Continuous, $response->data[0]->race);
        self::assertEquals(TrapType::Continuous, $response->data[\array_key_last($response->data)]->race);
        self::assertEquals(Type::Trap, $response->data[0]->type);
        self::assertEquals(Type::Trap, $response->data[\array_key_last($response->data)]->type);
    }

    public function testGetAllContinuousSpells(): void
    {
        $response = $this->client->cards->getAllSpellsOfType(SpellType::Continuous, limit: 4, page: 1);

        self::assertNotEmpty($response->data);
        self::assertInstanceOf(Card::class, $response->data[0]);
        self::assertEquals(SpellType::Continuous, $response->data[0]->race);
        self::assertEquals(SpellType::Continuous, $response->data[\array_key_last($response->data)]->race);
        self::assertEquals(Type::Spell, $response->data[0]->type);
        self::assertEquals(Type::Spell, $response->data[\array_key_last($response->data)]->type);
    }

    public function testGetAllLinkMonsters(): void
    {
        $response = $this->client->cards->getAllLinks(2);

        self::assertNotEmpty($response->data);
        self::assertInstanceOf(Card::class, $response->data[0]);
        self::assertEquals(2, $response->data[0]->link);
        self::assertEquals(2, $response->data[\array_key_last($response->data)]->link);
    }

    public function testGetAllLinkMonstersWithMarker(): void
    {
        $response = $this->client->cards->getAllLinks(2, LinkMarker::Bottom, limit: 4, page: 1);

        self::assertNotEmpty($response->data);
        self::assertInstanceOf(Card::class, $response->data[0]);
        self::assertContains(LinkMarker::Bottom, $response->data[0]->linkMarkers);
        self::assertContains(LinkMarker::Bottom, $response->data[\array_key_last($response->data)]->linkMarkers);
    }

    public function testFindOneById(): void
    {
        $response = $this->client->cards->findOneById(6983839);

        self::assertInstanceOf(Card::class, $response);
        self::assertEquals(6983839, $response->id);
    }

    public function testFindOneByNonExistingId(): void
    {
        $this->expectException(HttpClientResponseException::class);

        $this->client->cards->findOneById(1);
    }

    public function testFindOneByName(): void
    {
        $response = $this->client->cards->findOneByName('Tornado Dragon');

        self::assertInstanceOf(Card::class, $response);
        self::assertEquals('Tornado Dragon', $response->name);
    }

    public function testFindOneByNonExistingName(): void
    {
        $this->expectException(HttpClientResponseException::class);

        $this->client->cards->findOneByName('Tornado Drago');
    }

    public function testGetAllWithPagination(): void
    {
        $response = $this->client->cards->getAll(limit: 4, page: 1);

        self::assertInstanceOf(Pagination::class, $response->pagination);
        self::assertEquals(4, $response->pagination->currentRows);
        self::assertEquals(4, $response->pagination->nextPageOffset);
    }

    public function testGetAllWithPaginationFurtherPage(): void
    {
        $response = $this->client->cards->getAll(limit: 4, page: 3);

        self::assertInstanceOf(Pagination::class, $response->pagination);
        self::assertEquals(4, $response->pagination->currentRows);
        self::assertEquals(12, $response->pagination->nextPageOffset);
    }

    public function testGetAllWithoutPagination(): void
    {
        $response = $this->client->cards->getAllWithAtk(4000);

        self::assertNotEmpty($response->data);
        self::assertInstanceOf(Card::class, $response->data[0]);
        self::assertNull($response->pagination);
    }
}