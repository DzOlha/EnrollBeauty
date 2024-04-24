<?php

namespace Src\DB;

interface IDatabase
{
    public function query($sql): void;
    public function bind($parameter, $value, $type = null): void;
    public function bindAll(array $params);
    public function execute(): bool;
    public function beginTransaction(): void;
    public function rollBackTransaction(): void;
    public function commitTransaction(): void;
    public function backAutoCommit(): void;
    public function disableAutoCommit(): void;
    public function lastInsertedId(): false|string;
    public function manyRows(): array|false;
    public function singleRow(): array|false;
    public function affectedRowsCount(): int;
}