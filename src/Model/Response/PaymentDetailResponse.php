<?php

namespace Axerve\Payment\Model\Response;

use Axerve\Payment\Model\Payload\DetailPaymentPayload;

/**
 * Classe che rappresenta una risposta dell'API di dettaglio pagamento
 * @property DetailPaymentPayload $payload
 * @property string|null $paymentMethod
 * @property string|null $shopTransactionID
 * @property string|null $bankTransactionID
 * @property string|null $amount
 * @property string|null $currency
 * @property string|null $transactionResult
 * @property array|null $cardData
 * @property array|null $events
 * @property array|null $customInfo
 * @property string|null $transactionState
 * @property string|null $authorizationCode
 */
class PaymentDetailResponse extends AbstractPaymentResponse
{
    /**
     * @var DetailPaymentPayload|null Payload tipizzato della risposta
     * @phpstan-var DetailPaymentPayload|null
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
        $this->payload = new DetailPaymentPayload($payloadData);
    }

    /**
     * Verifica se la risposta è riuscita
     * Per il dettaglio pagamento, è considerata riuscita se non ci sono errori
     * e il risultato della transazione è 'OK'
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return !$this->hasError() && 
               $this->payload instanceof DetailPaymentPayload &&
               $this->payload->isSuccessful();
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
     * Ottiene l'ID della transazione bancaria
     *
     * @return string|null
     */
    public function getBankTransactionId(): ?string
    {
        return $this->payload?->getBankTransactionID();
    }

    /**
     * Ottiene l'ID della transazione del negozio
     *
     * @return string|null
     */
    public function getShopTransactionId(): ?string
    {
        return $this->payload?->getShopTransactionID();
    }

    /**
     * Ottiene il metodo di pagamento
     *
     * @return string|null
     */
    public function getPaymentMethod(): ?string
    {
        return $this->payload?->getPaymentMethod();
    }

    /**
     * Ottiene l'importo della transazione
     *
     * @return string|null
     */
    public function getAmount(): ?string
    {
        return $this->payload?->getAmount();
    }

    /**
     * Ottiene la valuta utilizzata
     *
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->payload?->getCurrency();
    }

    /**
     * Ottiene il risultato della transazione
     *
     * @return string|null
     */
    public function getTransactionResult(): ?string
    {
        return $this->payload?->getTransactionResult();
    }

    /**
     * Ottiene i dati della carta
     *
     * @return array|null
     */
    public function getCardData(): ?array
    {
        return $this->payload?->getCardData();
    }

    /**
     * Ottiene gli eventi del pagamento
     *
     * @return array|null
     */
    public function getEvents(): ?array
    {
        return $this->payload?->getEvents();
    }

    /**
     * Ottiene le informazioni personalizzate
     *
     * @return array|null
     */
    public function getCustomInfo(): ?array
    {
        return $this->payload?->getCustomInfo();
    }

    /**
     * Ottiene il payload tipizzato come DetailPaymentPayload
     *
     * @return DetailPaymentPayload|null
     */
    public function getPayload(): ?DetailPaymentPayload
    {
        return $this->payload;
    }
} 