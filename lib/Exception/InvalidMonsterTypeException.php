<?php

namespace YgoProDeckClient\Exception;

use YgoProDeckClient\Enum\Type;

class InvalidMonsterTypeException extends \RuntimeException
{
    public function __construct(array $nonMonsterTypes)
    {
        parent::__construct(\sprintf('You may not pass types %s to this method',
            \join(', ', \array_map(fn (Type $type) => $type->value, $nonMonsterTypes))
        ), 500);
    }
}