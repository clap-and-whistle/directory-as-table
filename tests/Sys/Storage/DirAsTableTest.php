<?php

declare(strict_types=1);

namespace Cw\DirAsTable\Sys\Storage;

use Cw\DirAsTable\Dto\User;
use Cw\DirAsTable\traits\DelTree;
use PHPUnit\Framework\TestCase;

use function count;
use function dirname;
use function explode;

class DirAsTableTest extends TestCase
{
    use DelTree;

    protected string $storageTestPath;

    protected function setUp(): void
    {
        // NOTE: namespaceまたいでファイル移動するようなときにコード変えなくて済むように
        $currentDepth = count(explode('\\', __NAMESPACE__)) - 1;
        $rootPath = dirname(__DIR__, $currentDepth);

        $this->storageTestPath = $rootPath . '/var/tmp/tests/tables';
    }

    protected function tearDown(): void
    {
        self::delTree($this->storageTestPath);
    }

    /** @test */
    public function makeTableAsDir(): void
    {
        // 準備
        $tableName = 'dummy';

        // 実行
        $target = new DirectoryAsTable($this->storageTestPath);
        $target->makeTableAsDir($tableName);

        // 検証
        $this->assertDirectoryExists($this->storageTestPath . '/' . $tableName);
    }

    /**
     * @noinspection NonAsciiCharacters
     * @test
     */
    public function Dtoをputしてget(): void
    {
        // 準備
        $expectedIdentity = 'hoge';
        $expectedDisplayName = 'ほげ';
        $expectedPassword = 'hogehoge';
        $dto = new User($expectedIdentity, $expectedDisplayName, $expectedPassword);

        // 実行
        $target = new DirectoryAsTable($this->storageTestPath);
        $target->makeTableAsDir($dto::tableName());
        $putResult = $target->put($dto);
        $getResult = $target->get($dto::tableName(), $dto->identity);

        // 検証
        $this->assertFileExists($this->storageTestPath . '/' . $dto::tableName() . '/' . $dto->identity . '.dat');
        $this->assertIsInt($putResult);

        $this->assertInstanceOf(User::class, $getResult);
        $this->assertSame($expectedIdentity, $getResult->identity);
        $this->assertSame($expectedDisplayName, $getResult->displayName);
        $this->assertSame($expectedPassword, $getResult->password);
    }
}
