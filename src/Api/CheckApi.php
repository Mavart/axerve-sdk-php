<?php

namespace Axerve\Payment\Api;

use Axerve\Payment\Exception\ApiException;
use Axerve\Payment\Http\HttpClient;
use Axerve\Payment\Model\PaymentResponse;

/**
 * Classe per interagire con l'API di verifica carte di Axerve
 */
class CheckApi
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * CheckApi constructor.
     *
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Verifica i dettagli di una carta di credito
     *
     * @param array $data Dati della carta di credito
     * @return PaymentResponse
     * @throws ApiException
     */
    public function creditCard(array $data): PaymentResponse
    {
        $endpoint = '/check/creditCard';
        $response = $this->httpClient->post($endpoint, $data);
        
        return new PaymentResponse($response);
    }
} 