<?php

namespace Src\Helper\Builder\impl;

use Src\DB\IDatabase;
use Src\Helper\Builder\IBuilder;
use Src\Helper\Trimmer\impl\RequestTrimmer;

class SqlBuilder implements IBuilder
{
    private string $query;
    private array $placeholders;
    private IDatabase $database;

    private array $balcklist = [
        'DROP', 'UPDATE', 'INSERT', 'TABLE', 'SELECT'
    ];

    public function __construct(IDatabase $database)
    {
        $this->database = $database;
        $this->query = '';
        $this->placeholders = [];
    }

    public function clearQuery() {
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

    public function leftJoinLateral() {
        $this->query .= "LEFT JOIN LATERAL ";
        return $this;
    }

    public function tableAlias(string $alias)
    {
        $this->query .= " as $alias ";
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

    public function whereIn(string $column, array $values)
    {
        // Prepare placeholders and values for binding
        $placeholders = array_map(
            function($index) {
                return ":placeholder$index";
        }, range(1, count($values)));

        $bindings = array_combine($placeholders, $values);

        $this->placeholders += $bindings;

        $placeholderString = implode(', ', array_keys($bindings));

        $this->query .= " WHERE $column IN ($placeholderString) ";

        return $this;
    }

    public function whereLikeInner(string $column, string $placeholder, string $value) {
        // Add the placeholder and value to the placeholders array
        $p2 = $placeholder.'_1';
        $p3 = $placeholder.'_2';
        $this->placeholders += [
            $placeholder => "%$value%",
            $p2 => "%$value",
            $p3 => "$value%"
        ];
        // Concatenate the placeholder properly in the query string
        $this->query .= " WHERE $column LIKE $placeholder OR
                                $column LIKE $p2 OR 
                                $column LIKE $p3 ";
        return $this;
    }

    public function andNotEmpty(string $column) {
        $this->query .= " AND $column != '' ";
        return $this;
    }

    public function whereNotNull(string $column) {
        $this->query .= "WHERE $column IS NOT NULL ";
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
    public function andNotEqual(string $column, string $placeholder, string $value) {
        $this->placeholders += [
            $placeholder => $value
        ];
        $this->query .= "AND $column != $placeholder ";
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

    public function or() {
        $this->query .= "OR ";
        return $this;
    }
    public function and() {
        $this->query .= "AND ";
        return $this;
    }

    public function lessEqual(string $column, string $placeholder, string $value) {
        $this->placeholders += [
            $placeholder => $value
        ];
        $this->query .= "$column <= $placeholder ";
        return $this;
    }
    public function less(string $column, string $placeholder, string $value) {
        $this->placeholders += [
            $placeholder => $value
        ];
        $this->query .= "$column < $placeholder ";
        return $this;
    }

    public function equal(string $column, string $placeholder, string $value) {
        $this->placeholders += [
            $placeholder => $value
        ];
        $this->query .= "$column = $placeholder ";
        return $this;
    }

    public function greater(string $column, string $placeholder, string $value) {
        $this->placeholders += [
            $placeholder => $value
        ];
        $this->query .= "$column > $placeholder ";
        return $this;
    }

    public function greaterEqual(string $column, string $placeholder, string $value) {
        $this->placeholders += [
            $placeholder => $value
        ];
        $this->query .= "$column >= $placeholder ";
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

    public function delete($table = '') {
        $this->query .= "DELETE $table ";
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

    public function orderBy(
        string $fieldValue, string $directionValue
    ) {
        $trimmed = $this->clearOrderBy($fieldValue, $directionValue);

        $this->query .= "ORDER BY {$trimmed['field']} {$trimmed['dir']} ";

        return $this;
    }

    public function clearOrderBy(
        string $fieldValue, string $directionValue
    ){
        $trimmedField = strtoupper(htmlspecialchars(trim($fieldValue)));

        /**
         * asc or ASC
         * desc or DESC
         */
        $trimmedDirection = strtoupper(htmlspecialchars(trim($directionValue)));

        /**
         * If name of the column contains whitespaces
         * or is one of the reserved words, just set it equal to 'id'
         */
        if(str_contains($trimmedField, ' ') || in_array($trimmedField, $this->balcklist)) {
            $trimmedField = 'id';
        }

        /**
         * If invalid direction has been provided,
         * just set it equal to ASC
         */
        if($trimmedDirection !== 'ASC' && $trimmedDirection !== 'DESC')
        {
            $trimmedDirection = 'ASC';
        }

        return [
            'field' => $trimmedField,
            'dir' => $trimmedDirection
        ];
    }

    public function limit(int $value, string $placeholder = ':limit_') {
        $this->query .= "LIMIT $placeholder ";
        $this->placeholders += [
            $placeholder => $value
        ];
        return $this;
    }

    public function offset(int $value, string $placeholder = ':offset_') {
        $this->query .= "OFFSET $placeholder ";
        $this->placeholders += [
            $placeholder => $value
        ];
        return $this;
    }

    public function build() {
        //var_dump($this->query);
        $this->database->query($this->query);
        foreach ($this->placeholders as $placeholder => $value) {
            $this->database->bind($placeholder, $value);
        }
        $this->clearQuery();
    }
}