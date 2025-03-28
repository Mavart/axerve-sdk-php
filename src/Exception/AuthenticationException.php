<?php

namespace Axerve\Payment\Exception;

use Throwable;

/**
 * Eccezione lanciata quando si verificano errori di autenticazione
 */
class AuthenticationException extends ApiException
{
    /**
     * AuthenticationException constructor.
     *
     * @param string $message
     * @param int $statusCode
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "Errore di autenticazione", int $statusCode = 401, Throwable $previous = null)
    {
        parent::__construct($message, $statusCode, $previous);
    }
} 