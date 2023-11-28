<?php

namespace Src\Helper\Builder\impl;

use Src\DB\IDatabase;
use Src\Helper\Builder\IBuilder;

class SqlBuilder implements IBuilder
{
    private string $query;
    private array $placeholders;
    private IDatabase $database;

    public function __construct(IDatabase $database)
    {
        $this->database = $database;
        $this->query = '';
        $this->placeholders = [];
    }


    public function select(array $columns, array $as = []) {
        $this->query .= "SELECT ";
        foreach ($columns as $column) {
            if(isset($as[$column])) {
                $this->query .= "$column AS {$as[$column]}, ";
            } else {
                $this->query .= "$column, ";
            }
        }
        $this->query = rtrim($this->query, ', ');
        return $this;
    }

    public function selectCount(string $column, string $as) {
        $this->query .= "SELECT COUNT($column) AS $as ";
        return $this;
    }

    public function from(string $table) {
        $this->query .= " FROM $table ";
        return $this;
    }

    public function innerJoin(
        string $table,
    ) {
        $this->query .= "INNER JOIN $table ";
        return $this;
    }

    public function leftJoin(
        string $table,
    ) {
        $this->query .= "LEFT JOIN $table ";
        return $this;
    }

    public function rightJoin(
        string $table,
    ) {
        $this->query .= "INNER JOIN $table ";
        return $this;
    }

    public function on(string $columnTableLeft, string $columnTableRight) {
        $this->query .= "ON $columnTableLeft = $columnTableRight ";
        return $this;
    }

    public function whereEqual(string $column, string $placeholder, string $value) {
        $this->placeholders += [
            $placeholder => $value
        ];
        $this->query .= "WHERE $column = $placeholder ";
        return $this;
    }

    public function whereLess(string $column, string $placeholder, string $value, bool $andE) {
        $this->placeholders += [
            $placeholder => $value
        ];
        $this->query .= "WHERE $column < $placeholder ";
        return $this;
    }

    public function whereLessEqual(string $column, string $placeholder, string $value, bool $andE) {
        $this->placeholders += [
            $placeholder => $value
        ];
        $this->query .= "WHERE $column <= $placeholder ";
        return $this;
    }

    public function whereGreater(string $column, string $placeholder, string $value) {
        $this->placeholders += [
            $placeholder => $value
        ];
        $this->query .= "WHERE $column > $placeholder ";
        return $this;
    }

    public function whereGreaterEqual(string $column, string $placeholder, string $value) {
        $this->placeholders += [
            $placeholder => $value
        ];
        $this->query .= "WHERE $column >= $placeholder ";
        return $this;
    }

    public function andOn(string $columnLeft, string $columnRight) {
        $this->query .= "AND $columnLeft = $columnRight ";
        return $this;
    }

    public function andEqual(string $column, string $placeholder, string $value) {
        $this->placeholders += [
            $placeholder => $value
        ];
        $this->query .= "AND $column = $placeholder ";
        return $this;
    }
    public function andIsNull(string $column) {
        $this->query .= "AND $column IS NULL ";
        return $this;
    }
    public function andLess(string $column, string $placeholder, string $value) {
        $this->placeholders += [
            $placeholder => $value
        ];
        $this->query .= "AND $column < $placeholder ";
        return $this;
    }
    public function andLessEqual(string $column, string $placeholder, string $value) {
        $this->placeholders += [
            $placeholder => $value
        ];
        $this->query .= "AND $column <= $placeholder ";
        return $this;
    }
    public function andGreater(string $column, string $placeholder, string $value) {
        $this->placeholders += [
            $placeholder => $value
        ];
        $this->query .= "AND $column > $placeholder ";
        return $this;
    }
    public function andGreaterEqual(string $column, string $placeholder, string $value) {
        $this->placeholders += [
            $placeholder => $value
        ];
        $this->query .= "AND $column >= $placeholder ";
        return $this;
    }

    public function insertInto(
        string $table, array $columns
    ) {
        $this->query .= "INSERT INTO $table (";
        $size = count($columns);
        for ($i = 0; $i < $size; $i++) {
            $this->query .= "$columns[$i], ";
        }
        /**
        * trim last comma
        */
        $this->query = rtrim($this->query, ', ');
        $this->query .= ") ";
        return $this;
    }

    private function _populateQueryPlaceholders(
        array $columnPlaceholders, array $columnValues
    ) {
        $size = count($columnPlaceholders);
        for ($i = 0; $i < $size; $i++) {
            $this->query .= "$columnPlaceholders[$i], ";
            $this->placeholders += [
                $columnPlaceholders[$i] => $columnValues[$i]
            ];
        }
    }

    public function values(array $columnPlaceholders, array $columnValues, bool $withSubquery = false) {
        $this->query .= "VALUES (";
        $this->_populateQueryPlaceholders($columnPlaceholders, $columnValues);
        /**
         * trim last comma
         */
        if($withSubquery === false) {
            $this->query = rtrim($this->query, ', ');
            $this->query .= ") ";
        }
        return $this;
    }

    public function update(string $table) {
        $this->query .= "UPDATE $table ";
        return $this;
    }

    public function set(string $column, string $placeholder, $value) {
        $this->query .= "SET $column = $placeholder ";
        $this->placeholders += [
            $placeholder => $value
        ];
        return $this;
    }

    public function setNull(string $column) {
        $this->query .= "SET $column = NULL ";
        return $this;
    }

    public function andSet(string $column, string $placeholder, $value) {
        $this->query .= ", $column = $placeholder ";
        $this->placeholders += [
            $placeholder => $value
        ];
        return $this;
    }

    public function andSetNull(string $column) {
        $this->query .= ", $column = NULL ";
        return $this;
    }

    public function delete() {
        $this->query .= "DELETE ";
        return $this;
    }

    public function subqueryBegin() {
        $this->query .= '( ';
        return $this;
    }

    public function subqueryEnd() {
        $this->query .= ') ';
        return $this;
    }

    public function queryEnd() {
        $this->query .= ') ';
        return $this;
    }

    public function orderBy(string $column, string $order) {
        $this->query .= "ORDER BY $column $order ";
        return $this;
    }

    public function limit(int $limit) {
        $this->query .= "LIMIT $limit ";
        return $this;
    }

    public function offset(int $offset) {
        $this->query .= "OFFSET $offset ";
        return $this;
    }

    public function build() {
        //var_dump($this->query);
        $this->database->query($this->query);
        foreach ($this->placeholders as $placeholder => $value) {
            $this->database->bind($placeholder, $value);
        }
    }
}