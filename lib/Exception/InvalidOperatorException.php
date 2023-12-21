<?php

namespace YgoProDeckClient\Exception;

use YgoProDeckClient\Expression\ExpressionCriteria;
use YgoProDeckClient\Expression\Operator;

class InvalidOperatorException extends \RuntimeException
{
    public function __construct(Operator $operator, ExpressionCriteria $criteria, array $availableOperators)
    {
        parent::__construct(\sprintf('Cannot build expression : operator %s used for criteria %s is not available. Available operators are : %s',
            $operator->name,
            $criteria->name,
            \join(', ', \array_map(fn (Operator $operator) => $operator->name, $availableOperators))
        ), 500);
    }
}