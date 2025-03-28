<?php

namespace Axerve\Payment\Model\Payload;

/**
 * Classe che rappresenta il payload di una risposta di creazione pagamento
 */
class CreatePaymentPayload extends BasePayload
{
    /**
     * @var string|null Token di pagamento
     */
    private ?string $paymentToken = null;

    /**
     * @var string|null ID del pagamento
     */
    private ?string $paymentID = null;

    /**
     * @var array|null Informazioni sul redirect dell'utente
     */
    private ?array $userRedirect = null;

    /**
     * Ottiene il token di pagamento
     *
     * @return string|null
     */
    public function getPaymentToken(): ?string
    {
        return $this->paymentToken;
    }

    /**
     * Ottiene l'ID del pagamento
     *
     * @return string|null
     */
    public function getPaymentID(): ?string
    {
        return $this->paymentID;
    }

    /**
     * Ottiene le informazioni sul redirect dell'utente
     *
     * @return array|null
     */
    public function getUserRedirect(): ?array
    {
        return $this->userRedirect;
    }

    /**
     * Ottiene l'URL di redirect dell'utente
     *
     * @return string|null
     */
    public function getRedirectUrl(): ?string
    {
        return $this->userRedirect['href'] ?? null;
    }

    /**
     * Verifica se è presente un URL di redirect
     *
     * @return bool
     */
    public function hasRedirect(): bool
    {
        return isset($this->userRedirect) && isset($this->userRedirect['href']) && !empty($this->userRedirect['href']);
    }
} 