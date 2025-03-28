# Axerve SDK per PHP

Questo SDK PHP permette di integrare facilmente le API di pagamento di Axerve all'interno delle tue applicazioni PHP.

## Requisiti

- PHP 7.4 o superiore
- Estensione JSON di PHP
- Estensione cURL di PHP
- Composer

## Installazione

Per installare l'SDK, usa Composer:

```bash
composer require mavart/axerve-sdk-php
```

## Configurazione

Per iniziare ad utilizzare l'SDK, è necessario creare un'istanza del client con i tuoi dati di autenticazione:

```php
<?php

require_once 'vendor/autoload.php';

use Axerve\Payment\AxerveClient;

// Inizializza il client con le tue credenziali
$client = new AxerveClient(
    'LA_TUA_API_KEY',
    'IL_TUO_SHOP_LOGIN',
    false // false (o omesso) per l'ambiente di produzione, true per l'ambiente sandbox
);
```

L'SDK utilizza l'ambiente di produzione come impostazione predefinita. Se desideri utilizzare l'ambiente sandbox per i test, puoi specificare `true` come terzo parametro durante l'inizializzazione del client:

```php
// Inizializza il client in modalità sandbox
$client = new AxerveClient(
    'LA_TUA_API_KEY',
    'IL_TUO_SHOP_LOGIN',
    true
);
```
## Metodi

### Payment API

- `POST payment/create`
```php
$client->payment->create($data)
```

- `GET payment/detail`
```php
$client->payment->getDetail($paymentId)
```

- `POST payment/detail`
```php
$client->payment->retrieveDetails($data)
```

- `POST payment/update`
```php
$client->payment->update($data)
```

- `POST payment/submit`
```php
$client->payment->submit($data, $paymentToken)
```

- `POST payment/capture`
```php
$client->payment->capture($data)
```

- `POST payment/cancel`
```php
$client->payment->cancel($data)
```

- `POST payment/refund`
```php
$client->payment->refund($data)
```

- `GET payment/methods/{paymentId}/{languageId}`
```php
$client->payment->getMethods($paymentId, $languageId, $paymentToken)
```


### Check API

- `POST check/creditCard`
```php
$client->check->creditCard($data)
```

...altri metodi in aggiornamento

- `POST check/cvv`
- `POST check/email`
- `POST check/expirydate`
- `POST check/pan`
- `POST check/DCC`
- `POST check/token`

### Shop API

- `GET shop/paymentMethods`
```php
$client->shop->getMethods($shopLogin)
```
...altri metodi in aggiornamento

- `GET shop/environment`
- `GET shop/language`
- `POST shop/language`
- `GET shop/moto`
- `POST shop/moto`
- `POST shop/paymentMethods`
- `GET shop/paymentPage`
- `POST shop/paymentPage`
- `POST shop/token`
- `GET shop/version`
- `GET shop/token`
- `DELETE shop/token`

### Dashboard API
...in aggiornamento


### Accesso tipizzato alle risposte

L'SDK offre risposte e payload tipizzati in base al tipo di operazione, rendendo più intuitivo e sicuro l'accesso ai dati.

#### Tipi di risposta

- `PaymentCreateResponse`: Classe specifica per le risposte dell'endpoint `payment/create`
- `PaymentDetailResponse`: Classe specifica per le risposte degli endpoint `payment/detail` e altri operativi

#### Tipi di payload

- `CreatePaymentPayload`: Payload specifico per le risposte dell'endpoint `payment/create`
- `DetailPaymentPayload`: Payload specifico per le risposte degli endpoint `payment/detail` e altri

### Esempi di utilizzo

#### Creazione di un pagamento

```php
try {
    $response = $client->payment->create($paymentRequest);
    
    if (!$response->hasError()) {
        // Accesso diretto ai metodi della risposta tipizzata
        echo "Payment ID: " . $response->getPaymentId();
        echo "Token: " . $response->getPaymentToken();
        
        // Verifica se è richiesto un redirect e ottieni l'URL
        if ($response->isRedirect()) {
            header('Location: ' . $response->getRedirectUrl());
            exit;
        }
        
        // Accesso diretto alle proprietà con sintassi semplificata
        echo "Payment ID: " . $response->paymentID;
        echo "Token: " . $response->paymentToken;
    }
} catch (ApiException $e) {
    echo "Errore: " . $e->getMessage();
}
```

#### Dettaglio di un pagamento

```php
try {
    $response = $client->payment->getDetail($paymentId);
    
    if ($response->isSuccessful()) {
        // Accesso diretto ai metodi della risposta tipizzata
        echo "Payment ID: " . $response->getPaymentId();
        echo "Shop Transaction ID: " . $response->getShopTransactionId();
        echo "Bank Transaction ID: " . $response->getBankTransactionId();
        
        // Accesso diretto alle proprietà con sintassi semplificata
        echo "Metodo pagamento: " . $response->paymentMethod;
        echo "Importo: " . $response->amount . " " . $response->currency;
        
        // Accesso ad array complessi come eventi e dati carta
        if (isset($response->cardData)) {
            echo "Circuito: " . $response->cardData['circuit'];
        }
        
        if (isset($response->events)) {
            foreach ($response->events as $event) {
                echo "Evento: " . $event['eventType'] . " - " . $event['eventStatus'];
            }
        }
    }
} catch (ApiException $e) {
    echo "Errore: " . $e->getMessage();
}
```

## Gestione degli errori

L'SDK gestisce le eccezioni in modo strutturato. Tutte le eccezioni dell'API estendono la classe base `Axerve\Payment\Exception\ApiException`.

```php
try {
    // Chiamata API
    $result = $client->payment->create($paymentData);
} catch (Axerve\Payment\Exception\AuthenticationException $e) {
    // Errore di autenticazione
    echo "Errore di autenticazione: " . $e->getMessage();
} catch (Axerve\Payment\Exception\ValidationException $e) {
    // Errore di validazione
    echo "Errore di validazione: " . $e->getMessage();
    var_dump($e->getErrors()); // Ottieni i dettagli degli errori di validazione
} catch (Axerve\Payment\Exception\ApiException $e) {
    // Altri errori API
    echo "Errore API: " . $e->getMessage();
}
```

## Nota sull'implementazione

Questo SDK utilizza l'estensione cURL di PHP per effettuare le richieste HTTP alle API di Axerve, garantendo massima compatibilità e controllo.

## Documentazione completa

Per la documentazione completa delle API di Axerve, visita [https://api.axerve.com/](https://api.axerve.com/)
