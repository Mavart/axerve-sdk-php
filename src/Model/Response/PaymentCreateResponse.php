<?php

namespace Axerve\Payment\Model\Response;

use Axerve\Payment\Model\Payload\CreatePaymentPayload;

/**
 * Classe che rappresenta una risposta dell'API di creazione pagamento
 */
class PaymentCreateResponse extends AbstractPaymentResponse
{
    /**
     * @var CreatePaymentPayload|null Payload tipizzato della risposta
     * @phpstan-var CreatePaymentPayload|null
     */
    protected $payload = null;

    /**
     * Inizializza il payload specifico
     * 
     * @param array $payloadData Dati del payload
     * @return void
     */
    protected function initializePayload(array $payloadData): void
    {
        $this->payload = new CreatePaymentPayload($payloadData);
    }

    /**
     * Verifica se la risposta è riuscita
     * Per la creazione pagamento, è considerata riuscita se non ci sono errori
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return !$this->hasError() && $this->payload !== null;
    }

    /**
     * Verifica se la risposta richiede un redirect
     *
     * @return bool
     */
    public function isRedirect(): bool
    {
        return !$this->hasError() && 
               $this->payload !== null &&
               $this->payload->hasRedirect();
    }

    /**
     * Ottiene l'URL di redirect
     *
     * @return string|null
     */
    public function getRedirectUrl(): ?string
    {
        if ($this->isRedirect()) {
            return $this->payload->getRedirectUrl();
        }
        
        return null;
    }

    /**
     * Ottiene l'ID del pagamento
     *
     * @return string|null
     */
    public function getPaymentId(): ?string
    {
        return $this->payload?->getPaymentID();
    }

    /**
     * Ottiene il token di pagamento
     *
     * @return string|null
     */
    public function getPaymentToken(): ?string
    {
        return $this->payload?->getPaymentToken();
    }

    /**
     * Ottiene il payload tipizzato come CreatePaymentPayload
     *
     * @return CreatePaymentPayload|null
     */
    public function getPayload(): ?CreatePaymentPayload
    {
        return $this->payload;
    }
} 