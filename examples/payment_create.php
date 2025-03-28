<?php 
require_once __DIR__ . '/../vendor/autoload.php';

use Axerve\Payment\AxerveClient;
use Axerve\Payment\Exception\ApiException;
use Axerve\Payment\Model\Response\PaymentCreateResponse;

// Inizializza il client con le tue credenziali
// Le tue credenziali Axerve
$apiKey = 'YOUR_API_KEY';
$shopLogin = 'YOUR_SHOP_LOGIN';

// Inizializza il client Axerve
$client = new AxerveClient($apiKey, $shopLogin, true); // true per utilizzare l'ambiente sandbox

// Esempio 1: Creazione di un pagamento e utilizzo del payload tipizzato
try {
    // Crea un nuovo pagamento
    $paymentRequest = [
        'amount' => '10.00',
        'currency' => 'EUR',
        'shopTransactionID' => 'order-' . time(),
        'buyerName' => 'Mario Rossi',
        'buyerEmail' => 'mario.rossi@example.com',
    ];

   
    $response = $client->payment->create($paymentRequest);
    
    // Verifica che non ci siano errori
    if ($response->hasError()) {
        echo "Errore: " . $response->getErrorMessage() . "\n";
        exit;
    }
    
    // Accesso diretto al payload dalla risposta specifica
    echo "=== Dettagli Creazione Pagamento ===\n";
    echo "Payment ID: " . $response->getPaymentId() . "\n";
    echo "Token: " . $response->getPaymentToken() . "\n";
    
    // Verifica se è richiesto il redirect
    if ($response->isRedirect()) {
        echo "URL di redirect: " . $response->getRedirectUrl() . "\n";
    } else {
        echo "Nessun redirect richiesto.\n";
    }
    
    // Accesso diretto alle proprietà attraverso magic methods
    echo "\n=== Accesso diretto alle proprietà ===\n";
    echo "Payment ID: " . $response->paymentID . "\n";
    echo "Token: " . $response->paymentToken . "\n";
    
} catch (ApiException $e) {
    echo "Errore durante la creazione del pagamento: " . $e->getMessage() . "\n";
}
