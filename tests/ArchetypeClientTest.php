<?php declare(strict_types=1);

namespace YgoProDeckClient\Tests;

use PHPUnit\Framework\TestCase;
use YgoProDeckClient\Client;
use YgoProDeckClient\Model\Archetype;

class ArchetypeClientTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = new Client();
    }

    public function testGetAllArchetypes(): void
    {
        $response = $this->client->archetypes->getAll();

        self::assertNotEmpty($response->data);
        self::assertInstanceOf(Archetype::class, $response->data[0]);
    }
}