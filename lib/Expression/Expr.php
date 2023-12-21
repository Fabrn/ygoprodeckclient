<?php

namespace YgoProDeckClient\Expression;

use YgoProDeckClient\Attribute\AvailableOperators;
use YgoProDeckClient\Enum\Attribute;
use YgoProDeckClient\Enum\CardSort;
use YgoProDeckClient\Enum\LinkMarker;
use YgoProDeckClient\Enum\Race;
use YgoProDeckClient\Enum\SpellType;
use YgoProDeckClient\Enum\TrapType;
use YgoProDeckClient\Enum\Type;
use YgoProDeckClient\Exception\InvalidMonsterTypeException;
use YgoProDeckClient\Exception\InvalidOperatorException;
use YgoProDeckClient\Exception\InvalidValueTypeException;
use YgoProDeckClient\Model\Archetype;
use YgoProDeckClient\Model\CardSet;
use YgoProDeckClient\Model\Set;

class Expr
{
    /**
     * @var list<ExprPart>
     */
    private array $parts = [];

    private \ReflectionEnum $criteriaReflection;

    public function __construct()
    {
        $this->criteriaReflection = new \ReflectionEnum(ExpressionCriteria::class);
    }

    public static function build(): self
    {
        return new self();
    }

    public function toArray(): array
    {
        $result = [];

        foreach ($this->parts as $part) {
            // Validates that the operator can be used
            $this->validateExprPart($part);

            $value = $part->value;

            if ($value instanceof \BackedEnum) {
                $value = $value->value;
            }
            elseif (\is_array($value)) {
                // If the array is an array of BackedEnum, gets values from them
                if ($value[0] instanceof \BackedEnum) {
                    $value = \array_map(fn (\BackedEnum $enum) => $enum->value, $value);
                }

                $value = \join(',', $value);
            }

            if (\in_array($part->operator, [Operator::Greater, Operator::GreaterOrEquals, Operator::Lighter, Operator::LighterOrEquals])) {
                $value = $part->operator->value . $value;
            }

            // Getting parameter name
            // By default, is simply the enum value. But, for the purpose of searching by name, the "name" criteria
            // must be renamed into "fname"
            $paramName = $part->criteria->value;

            if (ExpressionCriteria::Name === $part->criteria && Operator::Contains === $part->operator) {
                $paramName = 'fname';
            }

            $result[$paramName] = $value;
        }

        return $result;
    }

    public function atk(Operator $operator, int $value): self
    {
        $this->parts[] = new ExprPart(
            criteria: ExpressionCriteria::Atk,
            operator: $operator,
            value: $value
        );
        return $this;
    }

    public function def(Operator $operator, int $value): self
    {
        $this->parts[] = new ExprPart(
            criteria: ExpressionCriteria::Def,
            operator: $operator,
            value: $value
        );
        return $this;
    }

    public function level(Operator $operator, int $value): self
    {
        $this->parts[] = new ExprPart(
            criteria: ExpressionCriteria::Level,
            operator: $operator,
            value: $value
        );
        return $this;
    }

    public function race(Operator $operator, Race|SpellType|TrapType|array $value): self
    {
        $this->parts[] = new ExprPart(
            criteria: ExpressionCriteria::Race,
            operator: $operator,
            value: $value
        );
        return $this;
    }

    public function type(Operator $operator, Type|array $value): self
    {
        $this->parts[] = new ExprPart(
            criteria: ExpressionCriteria::Type,
            operator: $operator,
            value: $value
        );
        return $this;
    }

    public function attribute(Operator $operator, Attribute|array $value): self
    {
        $this->parts[] = new ExprPart(
            criteria: ExpressionCriteria::Attribute,
            operator: $operator,
            value: $value
        );
        return $this;
    }

    public function name(Operator $operator, string $value): self
    {
        $this->parts[] = new ExprPart(
            criteria: ExpressionCriteria::Name,
            operator: $operator,
            value: $value
        );
        return $this;
    }

    public function link(Operator $operator, int $value): self
    {
        $this->parts[] = new ExprPart(
            criteria: ExpressionCriteria::Link,
            operator: $operator,
            value: $value
        );
        return $this;
    }

    public function linkMarker(Operator $operator, LinkMarker|array $value): self
    {
        $this->parts[] = new ExprPart(
            criteria: ExpressionCriteria::LinkMarker,
            operator: $operator,
            value: $value
        );
        return $this;
    }

    public function archetype(Operator $operator, Archetype|string $value): self
    {
        $this->parts[] = new ExprPart(
            criteria: ExpressionCriteria::Archetype,
            operator: $operator,
            value: $value instanceof Archetype ? $value->name : $value
        );
        return $this;
    }

    public function scale(Operator $operator, int $value): self
    {
        $this->parts[] = new ExprPart(
            criteria: ExpressionCriteria::Scale,
            operator: $operator,
            value: $value
        );
        return $this;
    }

    public function set(Operator $operator, CardSet|Set|string $value): self
    {
        $this->parts[] = new ExprPart(
            criteria: ExpressionCriteria::Scale,
            operator: $operator,
            value: $value instanceof CardSet ? $value->set->name : ( $value instanceof Set ? $value->name : $value )
        );
        return $this;
    }

    public function spells(?SpellType $type = null): self
    {
        $this->type(Operator::Equals, Type::Spell);

        if (null !== $type) {
            $this->race(Operator::Equals, $type);
        }

        return $this;
    }

    public function traps(?TrapType $type = null): self
    {
        $this->type(Operator::Equals, Type::Trap);

        if (null !== $type) {
            $this->race(Operator::Equals, $type);
        }

        return $this;
    }

    public function monsters(Type|array $types = [], Race|array $races = []): self
    {
        // Pendulum Ritual does not seem to work as a filter
        $nonMonsterTypes = [Type::Spell, Type::Trap, Type::PendulumRitual, Type::Skill];

        if ($types instanceof Type) {
            $types = [$types];
        }

        if ($races instanceof Race) {
            $races = [$races];
        }

        // If no types set, uses all monster types
        if (0 === \count($types)) {
            $allowedTypes = [];

            foreach (Type::cases() as $type) {
                if (!\in_array($type, $nonMonsterTypes)) {
                    $allowedTypes[] = $type;
                }
            }

            $this->type(Operator::In, $allowedTypes);
        } else {
            foreach ($types as $type) {
                if (\in_array($type, $nonMonsterTypes)) {
                    throw new InvalidMonsterTypeException($nonMonsterTypes);
                }
            }

            $this->type(Operator::In, $types);
        }

        if (0 < \count($races)) {
            $this->race(Operator::In, $races);
        }

        return $this;
    }

    public function sort(CardSort $value): self
    {
        $this->parts[] = new ExprPart(
            criteria: ExpressionCriteria::Sort,
            operator: Operator::Equals,
            value: $value
        );
        return $this;
    }

    public function paginate(int $maxResults, int $page = 1): self
    {
        $this->parts[] = new ExprPart(
            criteria: ExpressionCriteria::Num,
            operator: Operator::Equals,
            value: $maxResults
        );

        $this->parts[] = new ExprPart(
            criteria: ExpressionCriteria::Offset,
            operator: Operator::Equals,
            value: $maxResults * ( $page - 1 )
        );

        return $this;
    }

    private function validateExprPart(ExprPart $part): void
    {
        // Validates Operator IN and ALL has an array as value
        if (\in_array($part->operator, [Operator::All, Operator::In]) && !\is_array($part->value)) {
            throw new InvalidValueTypeException($part->operator, $part->criteria, $part->value);
        }

        $criteriaCase = $this->criteriaReflection->getCase($part->criteria->name);

        /** @var AvailableOperators $availableOperatorsAttribute */
        $availableOperatorsAttribute = $criteriaCase->getAttributes(AvailableOperators::class)[0]->newInstance();

        $availableOperators = '*' === $availableOperatorsAttribute->operators ? Operator::cases() : $availableOperatorsAttribute->operators;

        if (!\in_array($part->operator, $availableOperators)) {
            throw new InvalidOperatorException($part->operator, $part->criteria, $availableOperators);
        }
    }
}