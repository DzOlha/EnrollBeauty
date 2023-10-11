<?php

namespace DB\Database;

use DB\IDatabase;
use Helper\Logger\ILogger;
use Helper\Logger\impl\MyLogger;

class MySql implements IDatabase
{
    private string $dbHost = DB_HOST;
    private string $dbUser = DB_USER;
    private string $dbPass = DB_PASS;
    private string $dbName = DB_NAME;

    private string $connection;
    private \PDOStatement $statement;
    private \PDO $dbHandler;
    private string $error;
    private static ?MySql $instance = null;
    private ILogger $logger;

    private function __construct()
    {
        $this->logger = MyLogger::getInstance();
        $this->connection = 'mysql:host=' . $this->dbHost . ';dbname=' . $this->dbName . ';charset=utf8';
        $options = [
            \PDO::ATTR_PERSISTENT => true,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ];
        try {
            $this->dbHandler = new \PDO($this->connection, $this->dbUser, $this->dbPass, $options);
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
            $this->logger->error($this->error);
        }
    }

    public static function getInstance(): IDatabase//singleton
    {
        if (!self::$instance) {
            self::$instance = new MySql();
        }
        return self::$instance;
    }

    public function query($sql): void
    {
        $this->statement = $this->dbHandler->prepare($sql);
    }

    //Bind values
    public function bind($parameter, $value, $type = null): void
    {
        $type = match (is_null($type)) {
            is_int($value) => \PDO::PARAM_INT,
            is_bool($value) => \PDO::PARAM_BOOL,
            is_null($value) => \PDO::PARAM_NULL,
            default => \PDO::PARAM_STR,
        };
        $this->statement->bindValue($parameter, $value, $type);
    }

    //Execute prepared statement
    public function execute(): bool
    {
        try {
            return $this->statement->execute();
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
            $this->logger->error($this->error);
            return false;
        }
    }

    /**
     * work with transaction
     */
    public function beginTransaction(): void
    {
        //$this->disableAutoCommit();
        $this->dbHandler->beginTransaction();
    }

    public function rollBackTransaction(): void
    {
        $this->dbHandler->rollBack();
        $this->backAutoCommit();
    }

    public function commitTransaction(): void
    {
        $this->dbHandler->commit();
        $this->backAutoCommit();
    }

    public function backAutoCommit(): void
    {
        $this->dbHandler->setAttribute(\PDO::ATTR_AUTOCOMMIT, 1);
    }

    public function disableAutoCommit(): void
    {
        $this->dbHandler->setAttribute(\PDO::ATTR_AUTOCOMMIT, 0);
    }

    public function lastInsertedId(): false|string
    {
        return $this->dbHandler->lastInsertId();
    }

    /**
     * Return an array of rows each of them
     * is presented in the associative array format
     */
    public function manyRows(): array|false
    {
        $this->execute();
        return $this->statement->fetchALL(\PDO::FETCH_ASSOC);
    }

    /**
     * Returns a single row in the associative array format
     */

    public function singleRow(): array|false
    {
        $this->execute();
        return $this->statement->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Returns number of rows affected by the last SQL statement
     */
    public function affectedRowsCount(): int
    {
        $this->execute();
        return $this->statement->rowCount();
    }

    private function __clone() {}

    public function __wakeup() {}
}