<?php

namespace Axerve\Payment\Api;

use Axerve\Payment\Exception\ApiException;
use Axerve\Payment\Http\HttpClient;

/**
 * Classe per interagire con l'API di shop di Axerve
 */
class ShopApi
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * ShopApi constructor.
     *
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Ottiene i metodi di pagamento disponibili per uno shop
     *
     * @param string $shopLogin Login dello shop
     * @return array
     * @throws ApiException
     */
    public function getMethods(string $shopLogin): array
    {
        $endpoint = "/shop/methods/{$shopLogin}";
        
        return $this->httpClient->get($endpoint);
    }
} 