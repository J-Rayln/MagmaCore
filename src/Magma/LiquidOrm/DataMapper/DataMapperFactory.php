<?php

declare(strict_types=1);

namespace Magma\LiquidOrm\DataMapper;

use Magma\DatabaseConnection\DatabaseConnectionInterface;
use Magma\LiquidOrm\DataMapper\Exception\DataMapperException;

class DataMapperFactory
{
    /**
     * Main constructor class.
     * 
     * @return void 
     */
    public function __construct()
    {
    }

    public function create(string $databaseConnectionString, string $dataMapperEnvironmentConfiguration): DataMapperInterface
    {
        $credentials = (new DataMapperEnvironmentConfiguration([]))->getDatabaseCredentials('mysql');
        $databaseConnectionObject = new $databaseConnectionString($credentials);
        if (!$databaseConnectionObject instanceof DatabaseConnectionInterface) {
            throw new DataMapperException($databaseConnectionString . ' is not a valid database connection object.');
        }
        return new DataMapper($databaseConnectionObject);
    }
}
