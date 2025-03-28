<?php

namespace Axerve\Payment\Exception;

use Throwable;

/**
 * Eccezione lanciata quando si verificano errori del server Axerve
 */
class ServerException extends ApiException
{
    /**
     * ServerException constructor.
     *
     * @param string $message
     * @param int $statusCode
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "Errore del server", int $statusCode = 500, Throwable $previous = null)
    {
        parent::__construct($message, $statusCode, $previous);
    }
} 