<?php

namespace Axerve\Payment\Exception;

use Throwable;

/**
 * Eccezione lanciata quando si verificano errori di validazione
 */
class ValidationException extends ApiException
{
    /**
     * @var array Errori di validazione
     */
    protected $errors;

    /**
     * ValidationException constructor.
     *
     * @param string $message
     * @param int $statusCode
     * @param Throwable|null $previous
     * @param array $errors
     */
    public function __construct(string $message = "Errori di validazione", int $statusCode = 422, Throwable $previous = null, array $errors = [])
    {
        $this->errors = $errors;
        parent::__construct($message, $statusCode, $previous);
    }

    /**
     * Ottiene gli errori di validazione
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
} 