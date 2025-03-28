<?php

namespace Axerve\Payment\Model\Payload;

/**
 * Classe che rappresenta il payload di una risposta di dettaglio pagamento
 */
class DetailPaymentPayload extends BasePayload
{
    /**
     * @var string|null ID del pagamento
     */
    private ?string $paymentID = null;

    /**
     * @var string|null ID della transazione del negozio
     */
    private ?string $shopTransactionID = null;

    /**
     * @var string|null ID della transazione bancaria
     */
    private ?string $bankTransactionID = null;

    /**
     * @var string|null Codice di autorizzazione
     */
    private ?string $authorizationCode = null;

    /**
     * @var string|null Metodo di pagamento
     */
    private ?string $paymentMethod = null;

    /**
     * @var string|null Token di pagamento
     */
    private ?string $token = null;

    /**
     * @var string|null Tipo di transazione
     */
    private ?string $transactionType = null;

    /**
     * @var string|null Risultato della transazione
     */
    private ?string $transactionResult = null;

    /**
     * @var string|null Codice di errore della transazione
     */
    private ?string $transactionErrorCode = null;

    /**
     * @var string|null Descrizione dell'errore della transazione
     */
    private ?string $transactionErrorDescription = null;

    /**
     * @var array|null Informazioni personalizzate
     */
    private ?array $customInfo = null;

    /**
     * @var string|null Valuta utilizzata
     */
    private ?string $currency = null;

    /**
     * @var string|null Importo della transazione
     */
    private ?string $amount = null;

    /**
     * @var array|null Eventi del pagamento
     */
    private ?array $events = null;

    /**
     * @var array|null Dati della carta
     */
    private ?array $cardData = null;

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
     * Ottiene l'ID della transazione del negozio
     *
     * @return string|null
     */
    public function getShopTransactionID(): ?string
    {
        return $this->shopTransactionID;
    }

    /**
     * Ottiene l'ID della transazione bancaria
     *
     * @return string|null
     */
    public function getBankTransactionID(): ?string
    {
        return $this->bankTransactionID;
    }

    /**
     * Ottiene il codice di autorizzazione
     *
     * @return string|null
     */
    public function getAuthorizationCode(): ?string
    {
        return $this->authorizationCode;
    }

    /**
     * Ottiene il metodo di pagamento
     *
     * @return string|null
     */
    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    /**
     * Ottiene il token di pagamento
     *
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Ottiene il tipo di transazione
     *
     * @return string|null
     */
    public function getTransactionType(): ?string
    {
        return $this->transactionType;
    }

    /**
     * Ottiene il risultato della transazione
     *
     * @return string|null
     */
    public function getTransactionResult(): ?string
    {
        return $this->transactionResult;
    }

    /**
     * Ottiene il codice di errore della transazione
     *
     * @return string|null
     */
    public function getTransactionErrorCode(): ?string
    {
        return $this->transactionErrorCode;
    }

    /**
     * Ottiene la descrizione dell'errore della transazione
     *
     * @return string|null
     */
    public function getTransactionErrorDescription(): ?string
    {
        return $this->transactionErrorDescription;
    }

    /**
     * Ottiene le informazioni personalizzate
     *
     * @return array|null
     */
    public function getCustomInfo(): ?array
    {
        return $this->customInfo;
    }

    /**
     * Ottiene la valuta utilizzata
     *
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * Ottiene l'importo della transazione
     *
     * @return string|null
     */
    public function getAmount(): ?string
    {
        return $this->amount;
    }

    /**
     * Ottiene gli eventi del pagamento
     *
     * @return array|null
     */
    public function getEvents(): ?array
    {
        return $this->events;
    }

    /**
     * Ottiene i dati della carta
     *
     * @return array|null
     */
    public function getCardData(): ?array
    {
        return $this->cardData;
    }
    
    /**
     * Verifica se la transazione è stata completata con successo
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->transactionResult === 'OK' || $this->transactionResult === 'APPROVED';
    }
} 