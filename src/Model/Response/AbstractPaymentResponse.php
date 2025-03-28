<?php

namespace Axerve\Payment\Model\Response;

use Axerve\Payment\Model\Payload\BasePayload;

/**
 * Classe base astratta che rappresenta una risposta dell'API di pagamento
 * 
 * Le proprietà del payload possono essere accedute direttamente dalla risposta
 * utilizzando i magic method, ad esempio:
 * - $response->paymentID invece di $response->getPayload()->getPaymentID()
 * - $response->paymentMethod invece di $response->getPayload()->getPaymentMethod()
 * 
 * Questo funziona accedendo al metodo getter corrispondente nel payload,
 * quindi assicurati che per ogni proprietà che vuoi accedere esista un metodo
 * getter nel payload (per esempio: getPropertyName() per la proprietà propertyName).
 */
abstract class AbstractPaymentResponse
{
    /**
     * @var array Dati della risposta
     */
    protected $data;

    /**
     * @var BasePayload|null Payload tipizzato della risposta
     */
    protected $payload = null;

    /**
     * Constructor.
     *
     * @param array $data Dati della risposta
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        
        // Inizializza il payload tipizzato se presente
        if (isset($data['payload']) && is_array($data['payload'])) {
            $this->initializePayload($data['payload']);
        }
    }
    
    /**
     * Inizializza il payload specifico
     * 
     * @param array $payloadData Dati del payload
     * @return void
     */
    abstract protected function initializePayload(array $payloadData): void;

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
    abstract public function isSuccessful(): bool;

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
    abstract public function getPaymentId(): ?string;

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
    public function getBasePayload(): ?BasePayload
    {
        return $this->payload;
    }

    /**
     * Ottiene i dati del payload come array
     *
     * @return array|null
     */
    public function getPayloadAsArray(): ?array
    {
        return $this->data['payload'] ?? null;
    }

    /**
     * Magic method per accedere direttamente alle proprietà del payload
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