<?php declare(strict_types=1);

namespace YgoProDeckClient\Tests;

use PHPUnit\Framework\TestCase;
use YgoProDeckClient\Client;
use YgoProDeckClient\Model\Card;

class RandomCardClientTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = new Client();
    }

    public function testGetRandomCard(): void
    {
        $response = $this->client->randomCards->generate();

        self::assertInstanceOf(Card::class, $response);
    }
}