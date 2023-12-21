<?php

namespace YgoProDeckClient\Tests;

use PHPUnit\Framework\TestCase;
use YgoProDeckClient\Client;
use YgoProDeckClient\Enum\Attribute;
use YgoProDeckClient\Enum\LinkMarker;
use YgoProDeckClient\Enum\Race;
use YgoProDeckClient\Enum\SpellType;
use YgoProDeckClient\Enum\TrapType;
use YgoProDeckClient\Enum\Type;
use YgoProDeckClient\Exception\InvalidMonsterTypeException;
use YgoProDeckClient\Exception\InvalidOperatorException;
use YgoProDeckClient\Exception\InvalidValueTypeException;
use YgoProDeckClient\Expression\Expr;
use YgoProDeckClient\Expression\Operator;
use YgoProDeckClient\Http\Pagination;
use YgoProDeckClient\Model\Card;

final class CardClientExprTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = new Client();
    }

    public function testGetAllWithAtkEquals(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->atk(Operator::Equals, 2000)
            ->paginate(4)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertEquals(2000, $response->data[0]->atk);
        self::assertEquals(2000, $response->data[\array_key_last($response->data)]->atk);
    }

    public function testGetAllWithAtkGreater(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->atk(Operator::Greater, 2000)
            ->paginate(4)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertGreaterThan(2000, $response->data[0]->atk);
        self::assertGreaterThan(2000, $response->data[\array_key_last($response->data)]->atk);
    }

    public function testGetAllWithAtkLighter(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->atk(Operator::Lighter, 2000)
            ->paginate(4)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertLessThan(2000, $response->data[0]->atk);
        self::assertLessThan(2000, $response->data[\array_key_last($response->data)]->atk);
    }

    public function testGetAllWithDefEquals(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->def(Operator::Equals, 2000)
            ->paginate(4)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertEquals(2000, $response->data[0]->def);
        self::assertEquals(2000, $response->data[\array_key_last($response->data)]->def);
    }

    public function testGetAllWithDefGreater(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->def(Operator::Greater, 2000)
            ->paginate(4)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertGreaterThan(2000, $response->data[0]->def);
        self::assertGreaterThan(2000, $response->data[\array_key_last($response->data)]->def);
    }

    public function testGetAllWithDefLighter(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->def(Operator::Lighter, 2000)
            ->paginate(4)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertLessThan(2000, $response->data[0]->def);
        self::assertLessThan(2000, $response->data[\array_key_last($response->data)]->def);
    }

    public function testGetAllWithLevelEquals(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->level(Operator::Equals, 4)
            ->paginate(4)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertEquals(4, $response->data[0]->level);
        self::assertEquals(4, $response->data[\array_key_last($response->data)]->level);
    }

    public function testGetAllWithLevelGreater(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->level(Operator::Greater, 4)
            ->paginate(4)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertGreaterThan(4, $response->data[0]->def);
        self::assertGreaterThan(4, $response->data[\array_key_last($response->data)]->def);
    }

    public function testGetAllWithLevelLighter(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->level(Operator::Lighter, 4)
            ->paginate(4)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertLessThan(4, $response->data[0]->level);
        self::assertLessThan(4, $response->data[\array_key_last($response->data)]->level);
    }

    public function testGetAllOfUniqueType(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->type(Operator::Equals, Type::Fusion)
            ->paginate(4)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertEquals(Type::Fusion, $response->data[0]->type);
        self::assertEquals(Type::Fusion, $response->data[\array_key_last($response->data)]->type);
    }

    public function testGetAllOfSeveralTypes(): void
    {
        $types = [Type::Fusion, Type::Normal];

        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->type(Operator::In, $types)
            ->paginate(30)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);

        foreach ($types as $type) {
            self::assertNotEmpty(
                \array_filter(
                    $response->data,
                    fn (Card $card) => $card->type === $type
                )
            );
        }
    }

    public function testGetAllOfUniqueAttribute(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->attribute(Operator::Equals, Attribute::Dark)
            ->paginate(4)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertEquals(Attribute::Dark, $response->data[0]->attribute);
        self::assertEquals(Attribute::Dark, $response->data[\array_key_last($response->data)]->attribute);
    }

    public function testGetAllOfSeveralAttributes(): void
    {
        $attributes = [Attribute::Dark, Attribute::Wind];

        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->attribute(Operator::In, $attributes)
            ->paginate(30)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);

        foreach ($attributes as $attribute) {
            self::assertNotEmpty(
                \array_filter(
                    $response->data,
                    fn (Card $card) => $card->attribute === $attribute
                )
            );
        }
    }

    public function testGetAllOfUniqueRace(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->race(Operator::Equals, Race::Dragon)
            ->paginate(4)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertEquals(Race::Dragon, $response->data[0]->race);
        self::assertEquals(Race::Dragon, $response->data[\array_key_last($response->data)]->race);
    }

    public function testGetAllOfSeveralRaces(): void
    {
        $races = [Race::Dragon, Race::WingedBeast];

        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->race(Operator::In, $races)
            ->paginate(30)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);

        foreach ($races as $race) {
            self::assertNotEmpty(
                \array_filter(
                    $response->data,
                    fn (Card $card) => $card->race === $race
                )
            );
        }
    }

    public function testFindOneByNameEquals(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->name(Operator::Equals, 'Dark Magician')
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertEquals('Dark Magician', $response->data[0]->name);
    }

    public function testGetAllContainingName(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->name(Operator::Contains, 'magician')
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertStringContainsString('magician', \strtolower($response->data[0]->name));
    }

    public function testGetAllByLinkValue(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->link(Operator::Equals, 2)
            ->paginate(4)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertEquals(2, $response->data[0]->link);
        self::assertEquals(2, $response->data[\array_key_last($response->data)]->link);
        self::assertNotEmpty($response->data[0]->linkMarkers);
        self::assertNotEmpty($response->data[\array_key_last($response->data)]->linkMarkers);
        self::assertEquals(Type::Link, $response->data[0]->type);
        self::assertEquals(Type::Link, $response->data[\array_key_last($response->data)]->type);
    }

    public function testGetAllByUniqueLinkMarkerValue(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->linkMarker(Operator::Equals, LinkMarker::Bottom)
            ->paginate(4)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertContains(LinkMarker::Bottom, $response->data[0]->linkMarkers);
        self::assertContains(LinkMarker::Bottom, $response->data[\array_key_last($response->data)]->linkMarkers);
        self::assertEquals(Type::Link, $response->data[0]->type);
        self::assertEquals(Type::Link, $response->data[\array_key_last($response->data)]->type);
    }

    public function testGetAllByAllLinkMarkers(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->linkMarker(Operator::All, [LinkMarker::Bottom, LinkMarker::Top])
            ->paginate(4)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertEquals(Type::Link, $response->data[0]->type);
        self::assertEquals(Type::Link, $response->data[\array_key_last($response->data)]->type);
        self::assertContains(LinkMarker::Bottom, $response->data[0]->linkMarkers);
        self::assertContains(LinkMarker::Bottom, $response->data[\array_key_last($response->data)]->linkMarkers);
        self::assertContains(LinkMarker::Top, $response->data[0]->linkMarkers);
        self::assertContains(LinkMarker::Top, $response->data[\array_key_last($response->data)]->linkMarkers);
    }

    public function testGetAllByPendulumScale(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->scale(Operator::Equals, 2)
            ->paginate(4)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertEquals(2, $response->data[0]->scale);
        self::assertEquals(2, $response->data[\array_key_last($response->data)]->scale);
    }

    public function testInvalidOperator(): void
    {
        $this->expectException(InvalidOperatorException::class);

        $this->client->cards->getAllMatchingExpr(Expr::build()
            ->attribute(Operator::Greater, Attribute::Wind)
            ->paginate(4)
        );
    }

    public function testInvalidValueType(): void
    {
        $this->expectException(InvalidValueTypeException::class);

        $this->client->cards->getAllMatchingExpr(Expr::build()
            ->attribute(Operator::In, Attribute::Wind)
            ->paginate(4)
        );
    }

    public function testGetAllWithPagination(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->paginate(4)
        );

        self::assertInstanceOf(Pagination::class, $response->pagination);
        self::assertEquals(4, $response->pagination->currentRows);
        self::assertEquals(4, $response->pagination->nextPageOffset);
    }

    public function testGetAllWithPaginationFurtherPage(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->paginate(4, 3)
        );

        self::assertInstanceOf(Pagination::class, $response->pagination);
        self::assertEquals(4, $response->pagination->currentRows);
        self::assertEquals(12, $response->pagination->nextPageOffset);
    }

    public function testGetAllWithoutPagination(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->atk(Operator::Equals, 2000)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertNull($response->pagination);
    }

    public function testGetAllSpellsWithoutRace(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->spells()
            ->paginate(4)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertEquals(Type::Spell, $response->data[0]->type);
        self::assertEquals(Type::Spell, $response->data[\array_key_last($response->data)]->type);
    }

    public function testGetAllSpellsOfRace(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->spells(SpellType::Continuous)
            ->paginate(4)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertEquals(Type::Spell, $response->data[0]->type);
        self::assertEquals(Type::Spell, $response->data[\array_key_last($response->data)]->type);
        self::assertEquals(SpellType::Continuous, $response->data[0]->race);
        self::assertEquals(SpellType::Continuous, $response->data[\array_key_last($response->data)]->race);
    }

    public function testGetAllTrapsWithoutRace(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->traps()
            ->paginate(4)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertEquals(Type::Trap, $response->data[0]->type);
        self::assertEquals(Type::Trap, $response->data[\array_key_last($response->data)]->type);
    }

    public function testGetAllTrapsOfRace(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->traps(TrapType::Continuous)
            ->paginate(4)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertEquals(Type::Trap, $response->data[0]->type);
        self::assertEquals(Type::Trap, $response->data[\array_key_last($response->data)]->type);
        self::assertEquals(TrapType::Continuous, $response->data[0]->race);
        self::assertEquals(TrapType::Continuous, $response->data[\array_key_last($response->data)]->race);
    }

    public function testGetAllMonstersWithoutTypeOrRace(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->monsters()
            ->paginate(4)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertNotContains($response->data[0]->type, [Type::Spell, Type::Trap, Type::Skill]);
        self::assertNotContains($response->data[\array_key_last($response->data)]->type, [Type::Spell, Type::Trap, Type::Skill]);
    }

    public function testGetAllMonstersWithUniqueType(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->monsters(Type::Fusion)
            ->paginate(4)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertEquals(Type::Fusion, $response->data[0]->type);
        self::assertEquals(Type::Fusion, $response->data[\array_key_last($response->data)]->type);
    }

    public function testGetAllMonstersWithSeveralTypes(): void
    {
        $types = [Type::Fusion, Type::Normal];

        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->monsters($types)
            ->paginate(4)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);

        foreach ($types as $type) {
            self::assertNotEmpty(
                \array_filter(
                    $response->data,
                    fn (Card $card) => $card->type === $type
                )
            );
        }
    }

    public function testGetAllWithInvalidType(): void
    {
        $this->expectException(InvalidMonsterTypeException::class);

        $this->client->cards->getAllMatchingExpr(Expr::build()
            ->monsters(Type::Spell)
            ->paginate(4)
        );
    }

    public function testGetAllMonstersWithUniqueRace(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->monsters(races: Race::Dinosaur)
            ->paginate(4)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertEquals(Race::Dinosaur, $response->data[0]->race);
        self::assertEquals(Race::Dinosaur, $response->data[\array_key_last($response->data)]->race);
    }

    public function testGetAllMonstersWithSeveralRaces(): void
    {
        $races = [Race::Dragon, Race::WingedBeast];

        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->monsters(races: $races)
            ->paginate(30)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);

        foreach ($races as $race) {
            self::assertNotEmpty(
                \array_filter(
                    $response->data,
                    fn (Card $card) => $card->race === $race
                )
            );
        }
    }

    public function testGetAllMonstersWithTypeAndRace(): void
    {
        $response = $this->client->cards->getAllMatchingExpr(Expr::build()
            ->monsters(Type::Fusion, Race::Dinosaur)
            ->paginate(4)
        );

        self::assertNotEmpty($response->data);
        self::assertContainsOnlyInstancesOf(Card::class, $response->data);
        self::assertEquals(Type::Fusion, $response->data[0]->type);
        self::assertEquals(Race::Dinosaur, $response->data[0]->race);
        self::assertEquals(Type::Fusion, $response->data[\array_key_last($response->data)]->type);
        self::assertEquals(Race::Dinosaur, $response->data[\array_key_last($response->data)]->race);
    }
}