<?php

require_once __DIR__ . '/..' . '/config.php';

$subscription = new \Ipag\Sdk\Model\SubscriptionV2\SubscriptionV2(
    [
        'is_active'   => false,
        'amount'      => 249.90,
        'description' => 'Plano atualizado',
    ]
);

$subscriptionId = 275;

try {

    $responseSubscription = $ipagClient->subscriptionV2()->update($subscription, $subscriptionId);
    $data = $responseSubscription->getData();

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
