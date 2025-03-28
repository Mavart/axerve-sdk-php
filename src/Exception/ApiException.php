<?php

namespace Axerve\Payment\Exception;

use Exception;
use Throwable;

/**
 * Eccezione base per tutte le eccezioni API
 */
class ApiException extends Exception
{
    /**
     * @var int Codice di stato HTTP
     */
    protected $statusCode;

    /**
     * ApiException constructor.
     *
     * @param string $message
     * @param int $statusCode
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", int $statusCode = 0, Throwable $previous = null)
    {
        $this->statusCode = $statusCode;
        parent::__construct($message, $statusCode, $previous);
    }

    /**
     * Ottiene il codice di stato HTTP
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
} 