<?php

declare(strict_types=1);

namespace Magma\LiquidOrm\EntityManager;

use Magma\LiquidOrm\DataMapper\DataMapperInterface;
use Magma\LiquidOrm\QueryBuilder\QueryBuilderInterface;
use Magma\LiquidOrm\EntityManager\EntityManagerInterface;
use Magma\LiquidOrm\EntityManager\Exception\CrudException;

class EntityManagerFactory
{
    /** @var DataMapperInterface */
    protected DataMapperInterface $dataMapper;

    /** @var QueryBuilderInterface */
    protected QueryBuilderInterface $queryBuilder;

    /**
     * Main constructor.
     * 
     * @param DataMapperInterface $dataMapper 
     * @param QueryBuilderInterface $queryBuilder 
     * @return void 
     */
    public function __construct(DataMapperInterface $dataMapper, QueryBuilderInterface $queryBuilder)
    {
        $this->dataMapper = $dataMapper;
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * 
     * 
     * @param string $crudString 
     * @param string $tableSchema 
     * @param string $tableSchemaID 
     * @param array $options 
     * @return EntityManagerInterface 
     * @throws CrudException 
     */
    public function create(string $crudString, string $tableSchema, string $tableSchemaID, array $options = []): EntityManagerInterface
    {
        $crudObject = new $crudString($this->dataMapper, $this->queryBuilder, $tableSchema, $tableSchemaID);
        if (!$crudObject instanceof CrudInterface) {
            throw new CrudException($crudString . ' is not a valid CRUD object.');
        }
        return new EntityManager($crudObject);
    }
}
