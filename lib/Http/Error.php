<?php

namespace YgoProDeckClient\Http;

class Error
{
    public function __construct(
        public readonly string $error
    ) {
    }
}