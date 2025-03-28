<?php

// Questo esempio mostra come ottenere i dettagli di un pagamento esistente

require_once __DIR__ . '/../vendor/autoload.php';

use Axerve\Payment\AxerveClient;
use Axerve\Payment\Exception\ApiException;

// Le tue credenziali Axerve
$apiKey = 'YOUR_API_KEY';
$shopLogin = 'YOUR_SHOP_LOGIN';

// Inizializza il client Axerve
$client = new AxerveClient($apiKey, $shopLogin, true); // true per utilizzare l'ambiente sandbox


$paymentId = '1453989699415'; 

try {
    // Ottieni dettagli del pagamento usando l'ID del pagamento
    $response = $client->payment->getDetail($paymentId);
    
    if ($response->hasError()) {
        echo "Errore: " . $response->getErrorMessage() . "\n";
        exit;
    }
    
    // Accesso diretto ai metodi della risposta tipizzata
    if ($response->isSuccessful()) {
        echo "\n=== Dettagli Pagamento ===\n";
        echo "ID Pagamento: " . $response->getPaymentId() . "\n";
        echo "shopTransactionID: " . $response->shopTransactionID . "\n";
        echo "bankTransactionID: " . $response->bankTransactionID . "\n";
        echo "customInfo: " . $response->customInfo . "\n";
        
        echo "amount: " . $response->getAmount() . "\n";
        echo "transactionResult: " . $response->getTransactionResult() . "\n";
        // Accesso diretto alle proprietà tramite i magic method
        echo "Metodo di pagamento: " . $response->paymentMethod . "\n";
        echo "Importo: " . $response->amount . " " . $response->currency . "\n";
        echo "Risultato transazione: " . $response->transactionResult . "\n";
        
        // Accesso ai dati della carta (se disponibili)
        if (isset($response->cardData)) {
            $cardData = $response->cardData;
            echo "\nInformazioni carta:\n";
            echo "Circuito: " . ($cardData['circuit'] ?? 'N/A') . "\n";
            echo "Ultimi 4 numeri: " . ($cardData['pan'] ?? 'N/A') . "\n";
            echo "Scadenza: " . ($cardData['expiryMonth'] ?? 'N/A') . "/" . ($cardData['expiryYear'] ?? 'N/A') . "\n";
        }
        
        // Accesso agli eventi (se disponibili)
        if (isset($response->events) && !empty($response->events)) {
            echo "\nEventi del pagamento:\n";
            foreach ($response->events as $event) {
                echo "- " . ($event['event']['eventdate'] ?? 'N/A') . ": " . 
                     ($event['event']['eventtype'] ?? 'N/A') . " - " . 
                     ($event['event']['eventStatus'] ?? 'N/A') . "\n";
            }
        }
    } else {
        echo "\nPagamento non riuscito o non trovato.\n";
    }
    
} catch (ApiException $e) {
    echo "Errore API: " . $e->getMessage() . "\n";
} 

/*
// METODO 2: Ottieni dettagli pagamento tramite ID transazione shop
$shopTransactionId = 'order-123456'; // ID della transazione nel tuo sistema

try {
    // Prepara i dati per la richiesta
    $detailsData = [
        'shopTransactionID' => $shopTransactionId
        // Puoi anche usare 'bankTransactionID' o 'paymentID' se preferisci
    ];
    
    // Ottieni i dettagli del pagamento
    $response = $client->payment->retrieveDetails($detailsData);
    
    // Verifica se la risposta è valida
    if ($response->hasError()) {
        echo "Errore durante la richiesta dettagli: " . $response->getErrorMessage() . "\n";
        echo "Codice errore: " . $response->getErrorCode() . "\n";
    } else {
        // Mostra i dettagli del pagamento
        echo "Dettagli pagamento (via retrieveDetails):\n";
        echo "ID Transazione: " . $response->paymentID . "\n";
        echo "ID Transazione negozio: " . $response->shopTransactionID . "\n";
        echo "ID Transazione banca: " . $response->bankTransactionID . "\n";
        echo "Codice Autorizzazione: " . $response->authorizationCode . "\n";
        echo "Valuta: " . $response->currency . "\n";
        echo "Stato transazione: " . $response->transactionState . "\n";
        echo "Risultato: " . $response->transactionResult . "\n";
        
        // Se il pagamento è con carta, mostra dettagli della carta
        if (isset($response->cardData) && !empty($response->cardData)) {
            echo "\nDettagli carta:\n";
            echo "PAN mascherato: " . $response->cardData['maskedPAN'] . "\n";
            echo "Circuito: " . $response->cardData['circuit'] . "\n";
            echo "Tipo carta: " . $response->cardData['productType'] . "\n";
        }
        
        // Mostra informazioni personalizzate se disponibili
        if (isset($response->customInfo) && !empty($response->customInfo)) {
            echo "\nInformazioni personalizzate:\n";
            foreach ($response->customInfo as $key => $value) {
                echo "- {$key}: {$value}\n";
            }
        }
    }
    
} catch (ApiException $e) {
    echo "Errore API: " . $e->getMessage() . "\n";
    echo "Codice stato: " . $e->getStatusCode() . "\n";
} catch (Exception $e) {
    echo "Errore generico: " . $e->getMessage() . "\n";
} 

*/