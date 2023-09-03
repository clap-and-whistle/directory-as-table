<?php

declare(strict_types=1);

namespace Cw\DirAsTable\Dto;

use Cw\DirAsTable\Sys\Storage\AbstractTableDataDto;

class User extends AbstractTableDataDto
{
    public function __construct(
        public readonly string $identity,
        public readonly string $displayName,
        public readonly string $password
    ) {
    }

    public static function tableName(): string
    {
        return 'userdata';
    }
}
