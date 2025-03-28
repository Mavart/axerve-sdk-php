<?php

namespace Axerve\Payment\Model;

use Axerve\Payment\Model\Payload\BasePayload;
use Axerve\Payment\Model\Payload\CreatePaymentPayload;
use Axerve\Payment\Model\Payload\DetailPaymentPayload;

/**
 * Classe che rappresenta una risposta dell'API di pagamento
 */
class PaymentResponse
{
    /**
     * @var array Dati della risposta
     */
    private $data;

    /**
     * @var BasePayload|null Payload tipizzato della risposta
     */
    private ?BasePayload $payload = null;
    
    /**
     * @var string Tipo di risposta
     */
    private string $responseType;

    /**
     * PaymentResponse constructor.
     *
     * @param array $data Dati della risposta
     * @param string $responseType Tipo di risposta ('create' o 'detail')
     */
    public function __construct(array $data, string $responseType = 'detail')
    {
        $this->data = $data;
        $this->responseType = $responseType;
        
        // Inizializza il payload tipizzato in base al tipo di risposta
        if (isset($data['payload']) && is_array($data['payload'])) {
            if ($responseType === 'create') {
                $this->payload = new CreatePaymentPayload($data['payload']);
            } else {
                $this->payload = new DetailPaymentPayload($data['payload']);
            }
        }
    }

    /**
     * Verifica se la risposta indica un errore
     *
     * @return bool
     */
    public function hasError(): bool
    {
        return isset($this->data['error']) && 
               is_array($this->data['error']) && 
               isset($this->data['error']['code']) && 
               $this->data['error']['code'] !== '0';
    }

    /**
     * Verifica se la risposta è riuscita
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        if ($this->hasError() || $this->payload === null) {
            return false;
        }
        
        if ($this->responseType === 'create') {
            // Per le risposte di creazione pagamento, è considerato successo se non c'è errore
            return true;
        } else {
            // Per le risposte di dettaglio pagamento, controlliamo transactionResult
            return $this->payload instanceof DetailPaymentPayload && 
                   $this->payload->isSuccessful();
        }
    }

    /**
     * Verifica se la risposta richiede un redirect
     *
     * @return bool
     */
    public function isRedirect(): bool
    {
        if ($this->hasError() || $this->payload === null) {
            return false;
        }
        
        if ($this->responseType === 'create') {
            return $this->payload instanceof CreatePaymentPayload && 
                   $this->payload->hasRedirect();
        }
        
        return false;
    }

    /**
     * Ottiene l'URL di redirect
     *
     * @return string|null
     */
    public function getRedirectUrl(): ?string
    {
        if ($this->isRedirect() && $this->payload instanceof CreatePaymentPayload) {
            return $this->payload->getRedirectUrl();
        }
        
        return null;
    }

    /**
     * Ottiene il codice di errore
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        if ($this->hasError()) {
            return $this->data['error']['code'];
        }
        
        return null;
    }

    /**
     * Ottiene il messaggio di errore
     *
     * @return string|null
     */
    public function getErrorMessage(): ?string
    {
        if ($this->hasError()) {
            return $this->data['error']['description'] ?? 'Errore sconosciuto';
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
        if ($this->payload instanceof CreatePaymentPayload) {
            return $this->payload->getPaymentID();
        } elseif ($this->payload instanceof DetailPaymentPayload) {
            return $this->payload->getPaymentID();
        }
        return null;
    }

    /**
     * Ottiene il token di pagamento (disponibile solo in CreatePaymentPayload)
     *
     * @return string|null
     */
    public function getPaymentToken(): ?string
    {
        if ($this->payload instanceof CreatePaymentPayload) {
            return $this->payload->getPaymentToken();
        }
        return null;
    }

    /**
     * Ottiene i dati completi della risposta
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Ottiene il payload tipizzato
     *
     * @return BasePayload|null
     */
    public function getPayload(): ?BasePayload
    {
        return $this->payload;
    }

    /**
     * Ottiene il payload tipizzato come CreatePaymentPayload
     *
     * @return CreatePaymentPayload|null
     */
    public function getCreatePayload(): ?CreatePaymentPayload
    {
        return ($this->payload instanceof CreatePaymentPayload) ? $this->payload : null;
    }

    /**
     * Ottiene il payload tipizzato come DetailPaymentPayload
     *
     * @return DetailPaymentPayload|null
     */
    public function getDetailPayload(): ?DetailPaymentPayload
    {
        return ($this->payload instanceof DetailPaymentPayload) ? $this->payload : null;
    }

    /**
     * Ottiene i dati del payload come array (per retrocompatibilità)
     *
     * @return array|null
     */
    public function getPayloadAsArray(): ?array
    {
        return $this->data['payload'] ?? null;
    }

    /**
     * Magic method per accedere direttamente alle proprietà del payload
     * Per esempio: $response->paymentID invece di $response->payload->paymentID
     *
     * @param string $name Nome della proprietà
     * @return mixed|null
     */
    public function __get(string $name)
    {
        if ($name === 'payload') {
            return $this->payload;
        }
        
        if ($this->payload !== null) {
            return $this->payload->$name;
        }
        
        return null;
    }

    /**
     * Verifica se una proprietà esiste
     *
     * @param string $name Nome della proprietà
     * @return bool
     */
    public function __isset(string $name): bool
    {
        if ($name === 'payload') {
            return $this->payload !== null;
        }
        
        return $this->payload !== null && isset($this->payload->$name);
    }
} 