<?php

namespace Axerve\Payment\Api;

use Axerve\Payment\Exception\ApiException;
use Axerve\Payment\Http\HttpClient;
use Axerve\Payment\Model\Response\PaymentCreateResponse;
use Axerve\Payment\Model\Response\PaymentDetailResponse;

/**
 * Classe per interagire con l'API di pagamento Axerve
 */
class PaymentApi
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * PaymentApi constructor.
     *
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Crea una nuova transazione di pagamento
     *
     * @param array $data Dati del pagamento
     * @return PaymentCreateResponse
     * @throws ApiException
     */
    public function create(array $data): PaymentCreateResponse
    {
        $endpoint = '/payment/create';
        $response = $this->httpClient->post($endpoint, $data);
        
        return new PaymentCreateResponse($response);
    }

    /**
     * Invia una transazione di pagamento
     *
     * @param array $data Dati del pagamento
     * @param string|null $paymentToken Token opzionale per il pagamento
     * @return PaymentDetailResponse
     * @throws ApiException
     */
    public function submit(array $data, ?string $paymentToken = null): PaymentDetailResponse
    {
        $endpoint = '/payment/submit';
        
        // Se il token di pagamento è fornito, aggiungerlo come parametro nella query
        $options = [];
        if ($paymentToken) {
            $options['query'] = ['paymentToken' => $paymentToken];
        }
        
        $response = $this->httpClient->post($endpoint, $data, $options);
        
        return new PaymentDetailResponse($response);
    }

    /**
     * Aggiorna i dettagli di un pagamento esistente
     *
     * @param array $data Dati del pagamento da aggiornare
     * @return PaymentDetailResponse
     * @throws ApiException
     */
    public function update(array $data): PaymentDetailResponse
    {
        $endpoint = '/payment/update';
        $response = $this->httpClient->post($endpoint, $data);
        
        return new PaymentDetailResponse($response);
    }

    /**
     * Cattura un pagamento precedentemente autorizzato
     *
     * @param array $data Dati per la cattura del pagamento
     * @return PaymentDetailResponse
     * @throws ApiException
     */
    public function capture(array $data): PaymentDetailResponse
    {
        $endpoint = '/payment/capture';
        $response = $this->httpClient->post($endpoint, $data);
        
        return new PaymentDetailResponse($response);
    }

    /**
     * Annulla un pagamento
     *
     * @param array $data Dati per l'annullamento del pagamento
     * @return PaymentDetailResponse
     * @throws ApiException
     */
    public function cancel(array $data): PaymentDetailResponse
    {
        $endpoint = '/payment/cancel';
        $response = $this->httpClient->post($endpoint, $data);
        
        return new PaymentDetailResponse($response);
    }

    /**
     * Richiede un rimborso per un pagamento
     *
     * @param array $data Dati per il rimborso
     * @return PaymentDetailResponse
     * @throws ApiException
     */
    public function refund(array $data): PaymentDetailResponse
    {
        $endpoint = '/payment/refund';
        $response = $this->httpClient->post($endpoint, $data);
        
        return new PaymentDetailResponse($response);
    }

    /**
     * Ottiene i metodi di pagamento disponibili
     *
     * @param string $paymentId ID del pagamento
     * @param string $languageId ID della lingua
     * @param string|null $paymentToken Token opzionale per il pagamento
     * @return array
     * @throws ApiException
     */
    public function getMethods(string $paymentId, string $languageId, ?string $paymentToken = null): array
    {
        $endpoint = "/payment/methods/{$paymentId}/{$languageId}";
        
        $options = [];
        if ($paymentToken) {
            $options['query'] = ['paymentToken' => $paymentToken];
        }
        
        return $this->httpClient->get($endpoint, $options);
    }

    /**
     * Ottiene i dettagli di un pagamento utilizzando l'ID
     * Utilizza il metodo GET payment/detail/{paymentID}
     *
     * @param string $paymentId ID del pagamento
     * @param string|null $paymentToken Token opzionale per il pagamento
     * @return PaymentDetailResponse
     * @throws ApiException
     */
    public function getDetail(string $paymentId, ?string $paymentToken = null): PaymentDetailResponse
    {
        $endpoint = "/payment/detail/{$paymentId}";
        
        $options = [];
        if ($paymentToken) {
            $options['query'] = ['paymentToken' => $paymentToken];
        }
        
        $response = $this->httpClient->get($endpoint, $options);
        
        return new PaymentDetailResponse($response);
    }

    /**
     * Recupera i dettagli di un pagamento utilizzando vari parametri
     * Utilizza il metodo POST payment/detail
     *
     * @param array $data Dati per la ricerca del pagamento (almeno uno tra shopTransactionID, bankTransactionID, paymentID deve essere fornito)
     * @return PaymentDetailResponse
     * @throws ApiException
     */
    public function retrieveDetails(array $data): PaymentDetailResponse
    {
        // Verifica che almeno uno dei parametri richiesti sia presente
        if (empty($data['shopTransactionID']) && empty($data['bankTransactionID']) && empty($data['paymentID'])) {
            throw new ApiException('Devi fornire almeno uno dei seguenti parametri: shopTransactionID, bankTransactionID, paymentID');
        }

        // Assicurati che shopLogin sia presente nei dati
        if (!isset($data['shopLogin'])) {
            $data['shopLogin'] = $this->httpClient->getShopLogin();
        }
        
        $endpoint = '/payment/detail';
        $response = $this->httpClient->post($endpoint, $data);
        
        return new PaymentDetailResponse($response);
    }
} 