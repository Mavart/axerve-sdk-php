<?php

namespace Axerve\Payment\Http;

use Axerve\Payment\Exception\ApiException;
use Axerve\Payment\Exception\AuthenticationException;
use Axerve\Payment\Exception\ValidationException;
use Axerve\Payment\Exception\ServerException;

/**
 * Client HTTP per effettuare richieste alle API Axerve utilizzando cURL
 */
class HttpClient
{
    /**
     * @var array Configurazione del client
     */
    private $config;

    /**
     * HttpClient constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Esegue una richiesta HTTP GET
     *
     * @param string $endpoint
     * @param array $params
     * @return array
     * @throws ApiException
     */
    public function get(string $endpoint, array $params = []): array
    {
        // Costruisci l'URL con i parametri della query
        $url = $this->buildUrl($endpoint);
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        return $this->request('GET', $url);
    }

    /**
     * Esegue una richiesta HTTP POST
     *
     * @param string $endpoint
     * @param array $data
     * @param array $options
     * @return array
     * @throws ApiException
     */
    public function post(string $endpoint, array $data = [], array $options = []): array
    {
        $url = $this->buildUrl($endpoint);
        
        // Aggiungi parametri della query se necessario
        if (isset($options['query']) && !empty($options['query'])) {
            $url .= '?' . http_build_query($options['query']);
        }
        
        // Aggiungi lo shopLogin se non è già presente
        if (!isset($data['shopLogin'])) {
            $data['shopLogin'] = $this->config['shopLogin'];
        }
        
        return $this->request('POST', $url, $data);
    }

    /**
     * Esegue una richiesta HTTP PUT
     *
     * @param string $endpoint
     * @param array $data
     * @return array
     * @throws ApiException
     */
    public function put(string $endpoint, array $data = []): array
    {
        $url = $this->buildUrl($endpoint);
        
        // Aggiungi lo shopLogin se non è già presente
        if (!isset($data['shopLogin'])) {
            $data['shopLogin'] = $this->config['shopLogin'];
        }
        
        return $this->request('PUT', $url, $data);
    }

    /**
     * Esegue una richiesta HTTP DELETE
     *
     * @param string $endpoint
     * @param array $params
     * @return array
     * @throws ApiException
     */
    public function delete(string $endpoint, array $params = []): array
    {
        // Costruisci l'URL con i parametri della query
        $url = $this->buildUrl($endpoint);
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        return $this->request('DELETE', $url);
    }

    /**
     * Costruisce l'URL completo per l'endpoint
     *
     * @param string $endpoint
     * @return string
     */
    private function buildUrl(string $endpoint): string
    {
        // Assicuriamoci che l'endpoint inizi con /
        if (strpos($endpoint, '/') !== 0) {
            $endpoint = '/' . $endpoint;
        }
        
        return $this->config['baseUrl'] . '/' . $this->config['version'] . $endpoint;
    }

    /**
     * Effettua una richiesta HTTP usando cURL
     *
     * @param string $method Metodo HTTP
     * @param string $url URL completo
     * @param array $data Dati per la richiesta (opzionali)
     * @return array
     * @throws ApiException
     */
    private function request(string $method, string $url, array $data = []): array
    {
        // Inizializza cURL
        $curl = curl_init();
        
        // Imposta le opzioni di base di cURL
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->config['timeout']);
        curl_setopt($curl, CURLOPT_HEADER, true);
        
        // Imposta il metodo HTTP
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        
        // Imposta gli header
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: apikey ' . $this->config['apiKey'],
            'User-Agent: AxervePaymentSDK-PHP/' . $this->config['userAgent']
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        
        // Aggiungi dati alla richiesta per metodi POST, PUT
        if (in_array($method, ['POST', 'PUT']) && !empty($data)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        // Esegui la richiesta
        $response = curl_exec($curl);
        
        // Verifica se ci sono errori
        if ($response === false) {
            $error = curl_error($curl);
            $errorCode = curl_errno($curl);
            curl_close($curl);
            throw new ApiException('Errore cURL: ' . $error, $errorCode);
        }
        
        // Estrai il codice di stato HTTP e il corpo della risposta
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $body = substr($response, $headerSize);
        
        // Chiudi la connessione cURL
        curl_close($curl);
        
        // Analizza la risposta
        $responseData = $this->parseResponse($body, $statusCode);
        
        // Gestisci gli errori basati sul codice di stato
        if ($statusCode >= 400) {
            $this->handleErrorResponse($statusCode, $responseData);
        }
        return $responseData;
    }

    /**
     * Gestisce le risposte di errore
     *
     * @param int $statusCode
     * @param array $responseData
     * @throws ApiException
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ServerException
     */
    private function handleErrorResponse(int $statusCode, array $responseData): void
    {
        $errorMessage = $responseData['error']['description'] ?? 'Errore sconosciuto';
        
        if ($statusCode === 401 || $statusCode === 403) {
            throw new AuthenticationException($errorMessage, $statusCode);
        } elseif ($statusCode === 422 || $statusCode === 400) {
            throw new ValidationException($errorMessage, $statusCode, null, $responseData['error'] ?? []);
        } elseif ($statusCode >= 500) {
            throw new ServerException('Errore del server Axerve: ' . $errorMessage, $statusCode);
        } else {
            throw new ApiException('Errore API: ' . $errorMessage, $statusCode);
        }
    }

    /**
     * Analizza la risposta e la converte in array
     *
     * @param string $body
     * @param int $statusCode
     * @return array
     * @throws ApiException
     */
    private function parseResponse(string $body, int $statusCode): array
    {
        $data = json_decode($body, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ApiException(
                'Impossibile analizzare la risposta JSON: ' . json_last_error_msg(),
                $statusCode
            );
        }
        
        return $data;
    }

    /**
     * Ottiene lo shopLogin dalla configurazione
     *
     * @return string
     */
    public function getShopLogin(): string
    {
        return $this->config['shopLogin'];
    }
} 