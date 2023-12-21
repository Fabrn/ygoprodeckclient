<?php

namespace YgoProDeckClient\Exception;

use YgoProDeckClient\Expression\ExpressionCriteria;
use YgoProDeckClient\Expression\Operator;

class InvalidValueTypeException extends \RuntimeException
{
    public function __construct(Operator $operator, ExpressionCriteria $criteria, mixed $value)
    {
        parent::__construct(\sprintf('Cannot build expression : operator %s used for criteria %s require the value to be an array. %s given.',
            $operator->name,
            $criteria->name,
            \gettype($value)
        ), 500);
    }
}