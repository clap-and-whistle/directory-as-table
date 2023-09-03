<?php

declare(strict_types=1);

namespace Cw\DirAsTable\traits;

use function array_diff;
use function is_dir;
use function rmdir;
use function scandir;
use function unlink;

trait DelTree
{
    /** @see https://www.php.net/manual/ja/function.rmdir.php の User Contributed Notes */
    protected static function delTree(string $dir): bool
    {
        $dirArray = scandir($dir);
        $files = $dirArray ? array_diff($dirArray, ['.', '..']) : [];

        foreach ($files as $file) {
            is_dir("$dir/$file") ? self::delTree("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir);
    }
}
