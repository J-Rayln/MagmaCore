<?php

declare(strict_types=1);

namespace Magma\LiquidOrm\EntityManager;

interface CrudInterface
{
    /**
     * Returns the storage schema name as a string.
     * 
     * @return string 
     */
    public function getSchema(): string;

    /**
     * Returns the primary key for the storage schema.
     * 
     * @return string 
     */
    public function getSchemaID(): string;

    /**
     * Returns the last inserted ID.
     * 
     * @return int 
     */
    public function lastID(): int;

    /**
     * Inserts data within a storage table.
     * 
     * @param array $fields 
     * @return bool 
     */
    public function create(array $fields = []): bool;

    /**
     * Returns an array of database rows based on the individual supplied
     * arguments.
     * 
     * @param array $selectors 
     * @param array $conditions 
     * @param array $parameters 
     * @param array $optional 
     * @return array 
     */
    public function read(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array;

    /**
     * Updates 1 or more rows of data within the storage table.
     * 
     * @param array $fields 
     * @param string $primaryKey 
     * @return bool 
     */
    public function update(array $fields = [], string $primaryKey): bool;

    /**
     * Permanently deletes a row from the storage table.
     * 
     * @param array $conditions 
     * @return bool 
     */
    public function delete(array $conditions = []): bool;

    /**
     * Returns queried search results.
     * 
     * @param array $selectors 
     * @param array $conditions 
     * @return null|array 
     */
    public function search(array $selectors = [], array $conditions = []): array;

    /**
     * Returns a custom query string.
     * 
     * @param string $rawQuery 
     * @param array $conditions Assigns an associative array of conditions
     *                          for the query string.
     * @return mixed 
     */
    public function rawQuery(string $rawQuery, array $conditions = []): mixed;
}
