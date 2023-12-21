<?php

namespace YgoProDeckClient\Http;

/**
 * @template T
 */
class Response implements \IteratorAggregate, \Countable
{
    public function __construct(
        public readonly array $data = [],
        public readonly ?Pagination $pagination = null
    ) {
    }

    /**
     * @return \ArrayIterator<array-key, T>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->data);
    }

    public function count(): int
    {
        return \count($this->data);
    }
}