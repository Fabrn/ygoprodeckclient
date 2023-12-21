<?php

namespace YgoProDeckClient\Client;

use YgoProDeckClient\Model\Card;

class RandomCardClient extends BaseClient
{
    public function generate(): Card
    {
        /** @var Card $response */
        $response = $this->httpClient->request(
            uri: 'randomcard.php',
            objectClass: Card::class,
            deserializationContext: ['as_collection' => false]
        );

        return $response;
    }
}