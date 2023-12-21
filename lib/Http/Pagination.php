<?php

namespace YgoProDeckClient\Http;

class Pagination
{
    public function __construct(
        public readonly int $currentRows,
        public readonly int $totalRows,
        public readonly int $rowsRemaining,
        public readonly int $totalPages,
        public readonly int $pagesRemaining,
        public readonly ?string $nextPage = null,
        public readonly ?int $nextPageOffset = null
    ) {
    }
}