<?php

namespace YgoProDeckClient\Exception;

use YgoProDeckClient\Http\Error;

class HttpClientResponseException extends \Exception
{
    public function __construct(Error $error)
    {
        parent::__construct($error->error, 500);
    }
}