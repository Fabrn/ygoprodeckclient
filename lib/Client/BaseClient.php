<?php

namespace YgoProDeckClient\Client;

use YgoProDeckClient\Client;
use YgoProDeckClient\Http\CurlHttpClient;

class BaseClient
{
    protected CurlHttpClient $httpClient;

    public function __construct(
        protected array $options
    ) {
        $this->httpClient = new CurlHttpClient([
            CurlHttpClient::OPTION_BASE_URI => Client::BASE_URI . '/' . $this->options[Client::PARAM_API_VERSION]
        ]);
    }
}