<?php

declare(strict_types=1);

namespace Magma\LiquidOrm\DataMapper;

interface DataMapperInterface
{
    /**
     * Prepare the query string.
     * 
     * @param string $sqlQuery 
     * @return DataMapperInterface 
     */
    public function prepare(string $sqlQuery): self;

    /**
     * Explicit dat type for the parameter using the PDO::PARAM_* constants.
     * 
     * @param mixed $value 
     * @return mixed 
     */
    public function bind($value); // 'mixed' datatype not available in PHP 7.4

    /**
     * Combination method which combines both methods above (prepare() and bind()), 
     * one of which is optimized for binding search queries once the second 
     * argument $type is set to search.
     * 
     * @param array $fields 
     * @param bool $isSearch 
     * @return mixed 
     */
    public function bindParameters(array $fields, bool $isSearch = false): self;

    /**
     * Returns the number of rows affected by a DELETE, INSERT, or
     * UPDATE statement.
     * 
     * @return int 
     */
    public function numRows(): int;

    /**
     * Execute function which will execute the prepared statement.
     * 
     * @return void 
     */
    public function execute(): void;

    /**
     * Returns a single database row as an object.
     * 
     * @return object 
     */
    public function result(): object;

    /**
     * Returns all the rows within the database array.
     * 
     * @return array 
     */
    public function results(): array;

    /**
     * Returns the last inserted row ID from the database table.
     * 
     * @return int 
     * @throws Throwable
     */
    public function getLastId(): int;
}
