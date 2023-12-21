<?php

namespace YgoProDeckClient\Client;

use YgoProDeckClient\Exception\HttpClientResponseException;
use YgoProDeckClient\Http\Response;
use YgoProDeckClient\Model\CardSet;

class CardSetClient extends BaseClient
{
    /**
     * @return Response<CardSet>
     * @throws HttpClientResponseException
     */
    public function getAll(): Response
    {
        return $this->httpClient->request('cardsets.php', CardSet::class);
    }

    public function findOneByCode(string $code): ?CardSet
    {
        /** @var CardSet|null $response */
        $response = $this->httpClient->request(
            uri: 'cardsetsinfo.php',
            objectClass: CardSet::class,
            query: ['setcode' => $code],
            deserializationContext: ['as_collection' => false]
        );

        return $response;
    }
}