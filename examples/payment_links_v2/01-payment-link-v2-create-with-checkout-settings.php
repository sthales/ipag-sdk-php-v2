<?php

require_once __DIR__ . '/..' . '/config.php';

/**
 * Criar Payment Link V2 com checkout_settings
 */
$paymentLink = new \Ipag\Sdk\Model\PaymentLinkV2\PaymentLinkV2(
    [
        'external_code'  => 'PL_V2_002',
        'amount'         => 250.00,
        'description'    => 'Link de pagamento com configurações de checkout',
        'expires_at'     => '05/02/2026 23:59:59',
        'additional_info' => 'Produto X - edição limitada',
        'stock_limit'    => 50,
        'checkout_settings' => [
            'max_installments'          => 12,
            'min_installment_value'     => 10.00,
            'interest'                  => 1.99,
            'interest_free_installments' => 3,
            'fixed_installment'         => 0,
            'payment_method'            => 'all',
        ],
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
