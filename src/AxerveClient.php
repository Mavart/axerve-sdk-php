<?php

namespace Axerve\Payment;

use Axerve\Payment\Api\PaymentApi;
use Axerve\Payment\Api\CheckApi;
use Axerve\Payment\Api\ShopApi;
use Axerve\Payment\Http\HttpClient;
use InvalidArgumentException;

/**
 * Client principale per l'SDK Axerve Payment
 */
class AxerveClient
{
    /**
     * Versione dell'SDK
     */
    const VERSION = '1.0.0';

    /**
     * URL dell'endpoint di produzione
     */
    const PRODUCTION_URL = 'https://ecomms2s.sella.it/api';

    /**
     * URL dell'endpoint di test (sandbox)
     */
    const SANDBOX_URL = 'https://sandbox.gestpay.net/api';

    /**
     * @var array Configurazione del client
     */
    private $config;

    /**
     * @var HttpClient Client HTTP
     */
    private $httpClient;

    /**
     * @var PaymentApi API per i pagamenti
     */
    public $payment;

    /**
     * @var CheckApi API per le verifiche
     */
    public $check;

    /**
     * @var ShopApi API per lo shop
     */
    public $shop;

    /**
     * Crea una nuova istanza del client Axerve
     *
     * @param string $apiKey Chiave API per l'autenticazione
     * @param string $shopLogin ID del negozio
     * @param bool $useSandbox Se true, utilizza l'ambiente sandbox, altrimenti utilizza l'ambiente di produzione
     */
    public function __construct(string $apiKey, string $shopLogin, bool $useSandbox = false)
    {
        if (empty($apiKey)) {
            throw new InvalidArgumentException('apiKey è obbligatorio');
        }

        if (empty($shopLogin)) {
            throw new InvalidArgumentException('shopLogin è obbligatorio');
        }
        
        // Prepara la configurazione con i parametri forniti
        $config = [
            'apiKey' => $apiKey,
            'shopLogin' => $shopLogin
        ];
        
        $this->config = $this->prepareConfig($config, $useSandbox);
        $this->httpClient = new HttpClient($this->config);
        
        // Inizializza le API
        $this->payment = new PaymentApi($this->httpClient);
        $this->check = new CheckApi($this->httpClient);
        $this->shop = new ShopApi($this->httpClient);
    }

    /**
     * Prepara la configurazione con i valori di default
     *
     * @param array $config
     * @param bool $useSandbox Se true, utilizza l'ambiente sandbox
     * @return array
     */
    private function prepareConfig(array $config, bool $useSandbox): array
    {
        $defaults = [
            'version' => 'v1',
            'timeout' => 30,
            'userAgent' => 'AxervePaymentSDK-PHP/' . self::VERSION
        ];

        $config = array_merge($defaults, $config);
        
        // Determina l'URL di base in base al parametro useSandbox
        $config['baseUrl'] = $useSandbox ? self::SANDBOX_URL : self::PRODUCTION_URL;
        $config['environment'] = $useSandbox ? 'sandbox' : 'production';
        
        return $config;
    }

    /**
     * Ottiene l'URL di base per le richieste API
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->config['baseUrl'];
    }

    /**
     * Ottiene la versione API configurata
     *
     * @return string
     */
    public function getApiVersion(): string
    {
        return $this->config['version'];
    }

    /**
     * Ottiene lo shopLogin configurato
     *
     * @return string
     */
    public function getShopLogin(): string
    {
        return $this->config['shopLogin'];
    }
}
