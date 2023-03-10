<?php

declare(strict_types=1);

namespace Magma\DatabaseConnection;

use PDO;

interface DatabaseConnectionInterface
{
    /**
     * Creates a new database connection.
     * 
     * @return PDO 
     */
    public function open(): PDO;

    /**
     * Closes database connection.
     */
    public function close(): void;
}
