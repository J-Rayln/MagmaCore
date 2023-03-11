<?php

declare(strict_types=1);

namespace Magma\LiquidOrm\DataMapper;

use Magma\DatabaseConnection\DatabaseConnectionInterface;
use Magma\LiquidOrm\DataMapper\Exception\DataMapperException;
use PDOStatement;
use Throwable;
use PDO;

class DataMapper implements DataMapperInterface
{
    /** @var DatabaseConnectionInterface */
    private DatabaseConnectionInterface $dbh;

    /** @var PDOStatement */
    private PDOStatement $statement;

    /**
     * Main DataMapper constructor.
     * 
     * @param DatabaseConnectionInterface $dbh 
     * @return void 
     */
    public function __construct(DatabaseConnectionInterface $dbh)
    {
        $this->dbh = $dbh;
    }

    /**
     * Check that the incoming $value is not empty else thrown an exception.
     * 
     * @param mixed $value 
     * @param string|null $errorMessage 
     * @return void 
     * @throws DataMapperException 
     */
    private function isEmpty($value, string $errorMessage = null)
    {
        if (empty($value)) {
            throw new DataMapperException($errorMessage);
        }
    }

    /**
     * Check that the incoming $value is an array else thrown an exception.
     * 
     * @param mixed $value 
     * @return void 
     * @throws DataMapperException 
     */
    private function isArray($value)
    {
        if (!is_array($value)) {
            throw new DataMapperException('Your argument needs to be an array');
        }
    }

    /**
     * @inheritDoc
     */
    public function prepare(string $sqlQuery): self
    {
        $this->statement = $this->dbh->open()->prepare($sqlQuery);
        return $this;
    }

    /**
     * @inheritDoc
     * 
     * @param mixed $value 
     * @return mixed 
     * @throws DataMapperException 
     */
    public function bind($value)
    {
        try {
            switch ($value) {
                case is_bool($value):
                case intval($value):
                    $dataType = PDO::PARAM_INT;
                    break;
                case is_null($value):
                    $dataType = PDO::PARAM_NULL;
                    break;
                default:
                    $dataType = PDO::PARAM_STR;
                    break;
            }
            return $dataType;
        } catch (DataMapperException $exception) {
            throw $exception;
        }
    }

    /**
     * @inheritDoc
     * 
     * @param array $fields 
     * @param bool $isSearch 
     * @return DataMapper 
     */
    public function bindParameters(array $fields, bool $isSearch = false): self
    {
        if (is_array($fields)) {
            $type = ($isSearch === false) ? $this->bindValues($fields) : $this->bindSearchValues($fields);
            if ($type) {
                return $this;
            }
        }
        return false;
    }

    /**
     * Binds a value to a corresponding name or question mark placeholder in
     * the SQL statement that was used to prepare the statement.
     * 
     * @param array $fields 
     * @return PDOStatement 
     * @throws DataMapperException 
     */
    protected function bindValues(array $fields)
    {
        $this->isArray($fields);
        foreach ($fields as $key => $value) {
            $this->statement->bindValue(':' . $key, $value, $this->bind($value));
        }
        return $this->statement;
    }

    /**
     * Binds a value to a corresponding name or question mark placeholder in
     * the SQL statement that was used to prepare the statement.  SImilar to 
     * above but optimized for search queries.
     * 
     * @param array $fields 
     * @return PDOStatement 
     * @throws DataMapperException 
     */
    protected function bindSearchValues(array $fields)
    {
        $this->isArray($fields);
        foreach ($fields as $key => $value) {
            $this->statement->bindValue(':' . $key, '%' . $value . '%', $this->bind($value));
        }
        return $this->statement;
    }

    /**
     * @inheritDoc
     * 
     * @return void 
     */
    public function execute(): void
    {
        if ($this->statement) return $this->statement->execute();
    }

    /**
     * @inheritDoc
     * 
     * @return int 
     */
    public function numRows(): int
    {
        if ($this->statement) return $this->statement->rowCount();
    }

    /**
     * @inheritDoc
     * 
     * @return object 
     */
    public function result(): object
    {
        if ($this->statement) return $this->statement->fetch(PDO::FETCH_OBJ);
    }

    /**
     * @inheritDoc
     * 
     * @return array 
     */
    public function results(): array
    {
        if ($this->statement) return $this->statement->fetchAll();
    }

    /**
     * @inheritDoc
     * 
     * @return int 
     * @throws Throwable 
     */
    public function getLastId(): int
    {
        try {
            if ($this->dbh->open()) {
                $lastId = $this->dbh->open()->lastInsertId();
                if (!empty($lastId)) {
                    return intval($lastId);
                }
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }
    /**
     * Returns the query condition merged with the query parameters.
     * 
     * @param array $conditions 
     * @param array $parameters 
     * @return array 
     */
    public function buildQueryParameters(array $conditions = [], array $parameters = []): array
    {
        return (!empty($parameters) || (!empty($conditions)) ? array_merge($conditions, $parameters) : $parameters);
    }

    /**
     * Persist queries to the database.
     * 
     * @param string $sqlQuery 
     * @param array $parameters 
     * @return void 
     * @throws Throwable 
     */
    public function persist(string $sqlQuery, array $parameters)
    {
        try {
            return $this->prepare($sqlQuery)->bindParameters($parameters)->execute();
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }
}
