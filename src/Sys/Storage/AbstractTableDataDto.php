<?php

declare(strict_types=1);

namespace Cw\DirAsTable\Sys\Storage;

abstract class AbstractTableDataDto
{
    public readonly string $identity;   // @phpstan-ignore-line
    abstract public static function tableName(): string;
}
