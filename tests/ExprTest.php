<?php declare(strict_types=1);

namespace YgoProDeckClient\Tests;

use PHPUnit\Framework\TestCase;
use YgoProDeckClient\Enum\Race;
use YgoProDeckClient\Enum\SpellType;
use YgoProDeckClient\Enum\TrapType;
use YgoProDeckClient\Enum\Type;
use YgoProDeckClient\Exception\InvalidMonsterTypeException;
use YgoProDeckClient\Exception\InvalidOperatorException;
use YgoProDeckClient\Exception\InvalidValueTypeException;
use YgoProDeckClient\Expression\Expr;
use YgoProDeckClient\Expression\Operator;
use YgoProDeckClient\Model\Archetype;

final class ExprTest extends TestCase
{
    public function testSimpleExpressionWithAtk(): void
    {
        $expr = Expr::build()->atk(Operator::Equals, 2000);
        $result = $expr->toArray();

        self::assertArrayHasKey('atk', $result);
        self::assertEquals(2000, $result['atk']);
    }

    public function testExpressionWithAtkGreaterOrEquals(): void
    {
        $expr = Expr::build()->atk(Operator::GreaterOrEquals, 2000);
        $result = $expr->toArray();

        self::assertArrayHasKey('atk', $result);
        self::assertEquals('gte2000', $result['atk']);
    }

    public function testExpressionWithAtkGreater(): void
    {
        $expr = Expr::build()->atk(Operator::Greater, 2000);
        $result = $expr->toArray();

        self::assertArrayHasKey('atk', $result);
        self::assertEquals('gt2000', $result['atk']);
    }

    public function testExpressionWithAtkLighterOrEquals(): void
    {
        $expr = Expr::build()->atk(Operator::LighterOrEquals, 2000);
        $result = $expr->toArray();

        self::assertArrayHasKey('atk', $result);
        self::assertEquals('lte2000', $result['atk']);
    }

    public function testExpressionWithAtkLighter(): void
    {
        $expr = Expr::build()->atk(Operator::Lighter, 2000);
        $result = $expr->toArray();

        self::assertArrayHasKey('atk', $result);
        self::assertEquals('lt2000', $result['atk']);
    }

    public function testExpressionWithNotAllowedOperator(): void
    {
        $this->expectException(InvalidOperatorException::class);

        $expr = Expr::build()->atk(Operator::Contains, 2000);
        $expr->toArray();
    }

    public function testExpressionWithEnumCriteria(): void
    {
        $expr = Expr::build()->race(Operator::Equals, Race::Dragon);
        $result = $expr->toArray();

        self::assertArrayHasKey('race', $result);
        self::assertEquals(Race::Dragon->value, $result['race']);
    }

    public function testExpressionWithFname(): void
    {
        $expr = Expr::build()->name(Operator::Contains, 'test');
        $result = $expr->toArray();

        self::assertArrayHasKey('fname', $result);
        self::assertEquals('test', $result['fname']);
    }

    public function testPaginateWithNoPageSet(): void
    {
        $expr = Expr::build()->paginate(20);
        $result = $expr->toArray();

        self::assertArrayHasKey('num', $result);
        self::assertEquals(20, $result['num']);

        self::assertArrayHasKey('offset', $result);
        self::assertEquals(0, $result['offset']);
    }

    public function testPaginateWithPage(): void
    {
        $expr = Expr::build()->paginate(20, 2);
        $result = $expr->toArray();

        self::assertArrayHasKey('num', $result);
        self::assertEquals(20, $result['num']);

        self::assertArrayHasKey('offset', $result);
        self::assertEquals(20, $result['offset']);
    }

    public function testArchetypeWithInstance(): void
    {
        $archetype = new Archetype(
            name: 'test'
        );

        $expr = Expr::build()->archetype(Operator::Equals, $archetype);
        $result = $expr->toArray();

        self::assertArrayHasKey('archetype', $result);
        self::assertEquals('test', $result['archetype']);
    }

    public function testExpressionSpellsNoRace(): void
    {
        $expr = Expr::build()->spells();
        $result = $expr->toArray();

        self::assertArrayHasKey('type', $result);
        self::assertEquals(Type::Spell->value, $result['type']);
        self::assertArrayNotHasKey('race', $result);
    }

    public function testExpressionSpellsWithRace(): void
    {
        $expr = Expr::build()->spells(SpellType::Field);
        $result = $expr->toArray();

        self::assertArrayHasKey('type', $result);
        self::assertEquals(Type::Spell->value, $result['type']);
        self::assertArrayHasKey('race', $result);
        self::assertEquals(SpellType::Field->value, $result['race']);
    }

    public function testExpressionTrapsNoRace(): void
    {
        $expr = Expr::build()->traps();
        $result = $expr->toArray();

        self::assertArrayHasKey('type', $result);
        self::assertEquals(Type::Trap->value, $result['type']);
        self::assertArrayNotHasKey('race', $result);
    }

    public function testExpressionTrapsWithRace(): void
    {
        $expr = Expr::build()->traps(TrapType::Normal);
        $result = $expr->toArray();

        self::assertArrayHasKey('type', $result);
        self::assertEquals(Type::Trap->value, $result['type']);
        self::assertArrayHasKey('race', $result);
        self::assertEquals(TrapType::Normal->value, $result['race']);
    }

    public function testExpressionWithInvalidValueType(): void
    {
        $this->expectException(InvalidValueTypeException::class);

        $expr = Expr::build()->atk(Operator::In, 2000);
        $expr->toArray();
    }

    public function testExpressionWithEnumArray(): void
    {
        $expr = Expr::build()->race(Operator::In, [Race::Dragon, Race::WingedBeast]);
        $result = $expr->toArray();

        self::assertArrayHasKey('race', $result);
        self::assertEquals(\join(',', [Race::Dragon->value, Race::WingedBeast->value]), $result['race']);
    }

    public function testExpressionWithMonstersOnly(): void
    {
        $expr = Expr::build()->monsters();
        $result = $expr->toArray();

        self::assertArrayHasKey('type', $result);

        $types = \explode(',', $result['type']);

        self::assertNotContains(Type::Spell->value, $types);
        self::assertNotContains(Type::Trap->value, $types);
        self::assertNotContains(Type::Skill->value, $types);

        // Test arbitrary monster type
        self::assertContains(Type::Normal->value, $types);
    }

    public function testExpressionWithMonstersOfOneType(): void
    {
        $expr = Expr::build()->monsters(Type::Fusion);
        $result = $expr->toArray();

        self::assertArrayHasKey('type', $result);

        $types = \explode(',', $result['type']);

        self::assertContains(Type::Fusion->value, $types);
        // Ensure spells is not in values
        self::assertNotContains(Type::Spell->value, $types);
        // Ensures another type is not in values
        self::assertNotContains(Type::Effect->value, $types);
    }

    public function testExpressionWithMonstersOfSeveralTypes(): void
    {
        $expr = Expr::build()->monsters([Type::Fusion, Type::Normal]);
        $result = $expr->toArray();

        self::assertArrayHasKey('type', $result);

        $types = \explode(',', $result['type']);

        self::assertContains(Type::Fusion->value, $types);
        self::assertContains(Type::Normal->value, $types);
        // Ensure spells is not in values
        self::assertNotContains(Type::Spell->value, $types);
        // Ensures another type is not in values
        self::assertNotContains(Type::Effect->value, $types);
    }

    public function testExpressionWithMonstersOfInvalidType(): void
    {
        $this->expectException(InvalidMonsterTypeException::class);

        $expr = Expr::build()->monsters(Type::Spell);
        $expr->toArray();
    }

    public function testExpressionWithMonstersOfOneRace(): void
    {
        $expr = Expr::build()->monsters(races: Race::Dragon);
        $result = $expr->toArray();

        self::assertArrayHasKey('race', $result);

        $races = \explode(',', $result['race']);

        self::assertContains(Race::Dragon->value, $races);
    }

    public function testExpressionWithMonstersOfSeveralRaces(): void
    {
        $expr = Expr::build()->monsters(races: [Race::Dragon, Race::Beast]);
        $result = $expr->toArray();

        self::assertArrayHasKey('race', $result);

        $races = \explode(',', $result['race']);

        self::assertContains(Race::Dragon->value, $races);
        self::assertContains(Race::Beast->value, $races);
    }
}