<?php

declare(strict_types=1);

namespace Cw\DirAsTable\Sys\Storage;

use RuntimeException;

use function assert;
use function file_get_contents;
use function file_put_contents;
use function is_dir;
use function mkdir;
use function serialize;
use function sprintf;
use function unserialize;

final class DirectoryAsTable
{
    private const MKDIR_MODE = 0755;
    private const FILE_EXTENSION = '.dat';

    public function __construct(private readonly string $storagePath)
    {
        $this->makeDir($this->storagePath, true);
    }

    private function makeDir(string $path, bool $recursive = false): void
    {
        if (! mkdir($path, self::MKDIR_MODE, $recursive) || ! is_dir($this->storagePath)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $this->storagePath));
        }
    }

    public function makeTableAsDir(string $tableName): void
    {
        $this->makeDir($this->storagePath . '/' . $tableName);
    }

    public function put(AbstractTableDataDto $dto): false|int
    {
        $tableName = $dto::tableName();

        if (! is_dir($this->storagePath . '/' . $tableName)) {
            $this->makeTableAsDir($tableName);
        }

        return file_put_contents(
            $this->storagePath . '/' . $tableName . '/' . $dto->identity . self::FILE_EXTENSION,
            serialize($dto),
        );
    }

    public function get(string $tableName, string $identity): false|AbstractTableDataDto
    {
        $contents = file_get_contents(
            $this->storagePath . '/' . $tableName . '/' . $identity . self::FILE_EXTENSION
        );

        if ($contents) {
            $obj = unserialize($contents, [
                'allowed_classes' => true,
                'max_depth' => 1,
            ]);
            assert($obj instanceof AbstractTableDataDto);

            return $obj;
        }

        return false;
    }
}
