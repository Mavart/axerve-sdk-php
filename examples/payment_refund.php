<?php

// Questo esempio mostra come effettuare un rimborso per un pagamento già completato

require_once __DIR__ . '/../vendor/autoload.php';

use Axerve\Payment\AxerveClient;
use Axerve\Payment\Exception\ApiException;

// Le tue credenziali Axerve
$apiKey = 'YOUR_API_KEY';
$shopLogin = 'YOUR_SHOP_LOGIN';

// Inizializza il client Axerve
$client = new AxerveClient($apiKey, $shopLogin, true); // true per utilizzare l'ambiente sandbox

// ID della transazione originale da rimborsare
$shopTransactionId = 'order-123456';
$bankTransactionId = '98765432'; // Questo è l'ID ottenuto dalla risposta di pagamento
$amount = '10.00'; // Importo da rimborsare (può essere minore o uguale all'importo originale)
$currency = 'EUR'; // Valuta (deve corrispondere alla valuta originale)

// Prepara i dati per il rimborso
$refundData = [
    'shopLogin' => $client->getShopLogin(),
    'shopTransactionID' => $shopTransactionId,
    'bankTransactionID' => $bankTransactionId,
    'amount' => $amount,
    'currency' => $currency
];

try {
    // Effettua la richiesta di rimborso
    $response = $client->payment->refund($refundData);
    
    // Verifica se la risposta è valida
    if ($response->hasError()) {
        echo "Errore durante il rimborso: " . $response->getErrorMessage() . "\n";
        echo "Codice errore: " . $response->getErrorCode() . "\n";
    } else {
        // Controlla lo stato del rimborso
        if ($response->isSuccessful()) {
            echo "Rimborso completato con successo!\n";
            echo "ID Transazione Bancaria: " . $response->getBankTransactionId() . "\n";
            
            // In un'applicazione reale, aggiorneresti lo stato dell'ordine nel tuo database
            // per indicare che è stato rimborsato
            
        } else {
            echo "Rimborso non completato o in attesa di elaborazione.\n";
            echo "Stato transazione: " . $response->getTransactionResult() . "\n";
            
           
        }
        
        // Mostra alcuni dettagli della risposta
        echo "\nDettagli della risposta:\n";
       // echo "Tipo transazione: " . $response->getTransactionType() . "\n";
        
        // Mostra il payload completo per debug
        echo "\nPayload completo:\n";
        print_r($response->getPayload());
    }
    
} catch (ApiException $e) {
    echo "Errore API: " . $e->getMessage() . "\n";
    echo "Codice stato: " . $e->getStatusCode() . "\n";
} catch (Exception $e) {
    echo "Errore generico: " . $e->getMessage() . "\n";
} 