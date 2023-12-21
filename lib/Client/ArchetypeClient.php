<?php

namespace YgoProDeckClient\Client;

use YgoProDeckClient\Http\Response;
use YgoProDeckClient\Model\Archetype;

class ArchetypeClient extends BaseClient
{
    public function getAll(): Response
    {
        return $this->httpClient->request('archetypes.php', Archetype::class);
    }
}