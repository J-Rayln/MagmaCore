<?php

declare(strict_types=1);

namespace Magma\DatabaseConnection\Exception;

use PDOException;

class DatabaseConnectionException extends PDOException
{
    /**
     * Main constructor class which overrides the parent constructor and sets
     * the message and the code properties which are optional.
     * 
     * @param string|null $message 
     * @param int $code 
     * @return void 
     */
    public function __construct(string $message = null, $code = null)
    {
        $this->message = $message;
        $this->code = $code;
    }
}
