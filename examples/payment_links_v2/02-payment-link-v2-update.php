<?php

require_once __DIR__ . '/..' . '/config.php';

/**
 * Atualizar Payment Link V2
 * Apenas os campos a serem modificados precisam ser informados.
 */
$paymentLink = new \Ipag\Sdk\Model\PaymentLinkV2\PaymentLinkV2(
    [
        'amount'      => 199.90,
        'description' => 'Link atualizado',
        'expires_at'  => '31/12/2026 23:59:59',
    ]
);

$paymentLinkId = 42;

try {

    $responsePaymentLink = $ipagClient->paymentLinksV2()->update($paymentLink, $paymentLinkId);
    $data = $responsePaymentLink->getData();

    echo "<pre>" . PHP_EOL;
    print_r($data);
    echo "</pre>" . PHP_EOL;
} catch (Ipag\Sdk\Exception\HttpException $e) {
    $code = $e->getResponse()->getStatusCode();
    $errors = $e->getErrors();

    echo "<pre>" . PHP_EOL;
    var_dump($code, $errors);
    echo "</pre>" . PHP_EOL;
} catch (Exception $e) {
    $error = $e->getMessage();

    echo "<pre>" . PHP_EOL;
    var_dump($error);
    echo "</pre>" . PHP_EOL;
}
