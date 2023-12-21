<?php

namespace YgoProDeckClient\Client;

use YgoProDeckClient\Enum\Attribute;
use YgoProDeckClient\Enum\CardSort;
use YgoProDeckClient\Enum\LinkMarker;
use YgoProDeckClient\Enum\Race;
use YgoProDeckClient\Enum\SpellType;
use YgoProDeckClient\Enum\TrapType;
use YgoProDeckClient\Enum\Type;
use YgoProDeckClient\Exception\HttpClientResponseException;
use YgoProDeckClient\Expression\Expr;
use YgoProDeckClient\Http\Response;
use YgoProDeckClient\Model\Archetype;
use YgoProDeckClient\Model\Card;
use YgoProDeckClient\Model\CardSet;
use YgoProDeckClient\Model\Set;

class CardClient extends BaseClient
{
    /**
     * @param array $filters
     * @param CardSort|null $sort
     * @param int|null $limit
     * @param int|null $page
     * @return Response<Card>
     * @throws HttpClientResponseException
     */
    public function getAll(array $filters = [], ?CardSort $sort = null, ?int $limit = null, ?int $page = null): Response
    {
        if (null !== $sort) {
            $filters['sort'] = $sort->value;
        }

        if (null !== $limit) {
            $filters['num'] = $limit;
            $filters['offset'] = $limit * ( ( $page ?? 1 ) - 1 );
        }

        return $this->httpClient->request('cardinfo.php', Card::class, $filters);
    }

    public function getAllMatchingExpr(Expr $expr): Response
    {
        return $this->getAll($expr->toArray());
    }

    public function getAllContainingName(string $name, ?CardSort $sort = null, ?int $limit = null, ?int $page = null): Response
    {
        return $this->getAll(['fname' => $name], $sort, $limit, $page);
    }

    public function getAllByName(string|array $name, ?CardSort $sort = null, ?int $limit = null, ?int $page = null): Response
    {
        if (\is_array($name)) {
            $name = \join('|', $name);
        }

        return $this->getAll(['name' => $name], $sort, $limit, $page);
    }

    public function findOneByName(string $name): ?Card
    {
        $cards = $this->getAllByName($name);

        if (0 === \count($cards)) {
            return null;
        }

        return $cards->data[0];
    }

    public function findOneById(int $id): ?Card
    {
        $cards = $this->getAll(['id' => $id]);

        if (0 === \count($cards)) {
            return null;
        }

        return $cards->data[0];
    }

    public function getAllByRace(Race $race, ?CardSort $sort = null, ?int $limit = null, ?int $page = null): Response
    {
        return $this->getAll(['race' => $race->value], $sort, $limit, $page);
    }

    public function getAllOfType(Type $type, ?CardSort $sort = null, ?int $limit = null, ?int $page = null): Response
    {
        return $this->getAll(['type' => $type->value], $sort, $limit, $page);
    }

    public function getAllByAttribute(Attribute $attribute, ?CardSort $sort = null, ?int $limit = null, ?int $page = null): Response
    {
        return $this->getAll(['attribute' => $attribute->value], $sort, $limit, $page);
    }

    public function getAllWithAtk(int $atk, ?CardSort $sort = null, ?int $limit = null, ?int $page = null): Response
    {
        return $this->getAll(['atk' => $atk], $sort, $limit, $page);
    }

    public function getAllWithDef(int $def, ?CardSort $sort = null, ?int $limit = null, ?int $page = null): Response
    {
        return $this->getAll(['def' => $def], $sort, $limit, $page);
    }

    public function getAllWithLevel(int $level, ?CardSort $sort = null, ?int $limit = null, ?int $page = null): Response
    {
        return $this->getAll(['level' => $level], $sort, $limit, $page);
    }

    public function getAllWithStats(int $atk, int $def, ?int $level = null, ?CardSort $sort = null, ?int $limit = null, ?int $page = null): Response
    {
        $params = ['atk' => $atk, 'def' => $def];

        if (null !== $level) {
            $params['level'] = $level;
        }

        return $this->getAll($params, $sort, $limit, $page);
    }

    public function getAllLinks(int $link, ?LinkMarker $marker = null, ?CardSort $sort = null, ?int $limit = null, ?int $page = null): Response
    {
        $params = ['link' => $link];

        if (null !== $marker) {
            $params['linkmarker'] = $marker->value;
        }

        return $this->getAll($params, $sort, $limit, $page);
    }

    public function getAllOfArchetype(Archetype|string $archetype, ?CardSort $sort = null, ?int $limit = null, ?int $page = null): Response
    {
        if ($archetype instanceof Archetype) {
            $archetype = $archetype->name;
        }

        return $this->getAll(['archetype' => $archetype], $sort, $limit, $page);
    }

    public function getAllOfCardSet(CardSet|Set|string $set, ?CardSort $sort = null, ?int $limit = null, ?int $page = null): Response
    {
        if ($set instanceof CardSet) {
            $set = $set->set->name;
        }
        elseif ($set instanceof Set) {
            $set = $set->name;
        }

        return $this->getAll(['cardset' => $set], $sort, $limit, $page);
    }

    public function getAllSpellsOfType(SpellType $type, ?CardSort $sort = null, ?int $limit = null, ?int $page = null): Response
    {
        return $this->getAll(
            filters: [
                'race' => $type->value,
                'type' => Type::Spell->value
            ],
            sort: $sort,
            limit: $limit,
            page: $page
        );
    }

    public function getAllTrapsOfType(TrapType $type, ?CardSort $sort = null, ?int $limit = null, ?int $page = null): Response
    {
        return $this->getAll(
            filters: [
                'race' => $type->value,
                'type' => Type::Trap->value
            ],
            sort: $sort,
            limit: $limit,
            page: $page
        );
    }
}