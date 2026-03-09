<?php

require_once __DIR__ . '/..' . '/config.php';

/**
 * Forma 1: Campos diretos + customer_id
 * Usado quando não há plano pré-cadastrado.
 * Os campos frequency, interval e amount são obrigatórios neste caso.
 */
$subscription = new \Ipag\Sdk\Model\SubscriptionV2\SubscriptionV2(
    [
        'profile_id'    => 'SUB_V2_001',
        'description'   => 'Plano mensal premium',
        'starting_date' => '2026-07-01',
        'callback_url'  => 'https://minhaloja.com/callback',
        'card_token'    => '6a64c8c5-1249-4845-b34a-111b54b1beb2',
        'is_active'     => true,
        'frequency'     => 'monthly',
        'interval'      => 1,
        'amount'        => 199.90,
        'cycles'        => 12,
        'customer_id'   => 100022,
    ]
);

try {

    $responseSubscription = $ipagClient->subscriptionV2()->create($subscription);
    $data = $responseSubscription->getData();

    $statusSubscription = $responseSubscription->getParsedPath('attributes.status');

    echo "Status da Assinatura: {$statusSubscription}" . PHP_EOL;

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
