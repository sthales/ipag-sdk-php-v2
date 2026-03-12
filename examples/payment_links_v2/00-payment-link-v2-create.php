<?php

require_once __DIR__ . '/..' . '/config.php';

/**
 * Criar Payment Link V2 (básico, sem checkout_settings)
 */
$paymentLink = new \Ipag\Sdk\Model\PaymentLinkV2\PaymentLinkV2(
    [
        'external_code'  => 'PL_V2_001',
        'amount'         => 150.00,
        'description'    => 'Link de pagamento V2',
        'expires_at'     => '05/02/2026 13:35:00',
        'additional_info' => 'Informações adicionais do link',
        'stock_limit'    => 100,
    ]
);

try {

    $responsePaymentLink = $ipagClient->paymentLinksV2()->create($paymentLink);
    $data = $responsePaymentLink->getData();

    $link = $responsePaymentLink->getParsedPath('links.payment');

    echo "Link de Pagamento: {$link}" . PHP_EOL;

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
