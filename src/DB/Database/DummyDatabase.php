<?php

namespace Src\DB\Database;

use Src\DB\IDatabase;

class DummyDatabase implements IDatabase
{

    private array $data;
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function query($sql): void
    {
        // TODO: Implement query() method.
    }

    public function bind($parameter, $value, $type = null): void
    {
        // TODO: Implement bind() method.
    }

    public function execute(): bool
    {
        // TODO: Implement execute() method.
        return true;
    }

    public function beginTransaction(): void
    {
        // TODO: Implement beginTransaction() method.
    }

    public function rollBackTransaction(): void
    {
        // TODO: Implement rollBackTransaction() method.
    }

    public function commitTransaction(): void
    {
        // TODO: Implement commitTransaction() method.
    }

    public function backAutoCommit(): void
    {
        // TODO: Implement backAutoCommit() method.
    }

    public function disableAutoCommit(): void
    {
        // TODO: Implement disableAutoCommit() method.
    }

    public function lastInsertedId(): false|string
    {
        // TODO: Implement lastInsertedId() method.
        return '';
    }

    public function manyRows(): array|false
    {
        // TODO: Implement manyRows() method.
        return [];
    }

    public function singleRow(): array|false
    {
        // TODO: Implement singleRow() method.
        return [];
    }

    public function affectedRowsCount(): int
    {
        // TODO: Implement affectedRowsCount() method.
        return 0;
    }

}