<?php

namespace YgoProDeckClient\Expression;

class ExprPart
{
    public function __construct(
        public readonly ExpressionCriteria $criteria,
        public readonly Operator           $operator,
        public mixed                       $value
    ) {
    }
}