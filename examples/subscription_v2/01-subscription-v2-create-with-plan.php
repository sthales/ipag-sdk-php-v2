<?php

require_once __DIR__ . '/..' . '/config.php';

/**
 * Forma 3: Plano embutido (plan object) + customer_id
 * Usado quando o plano é definido inline na requisição.
 * Os campos description, frequency, interval e amount são obrigatórios dentro do plan.
 */
$subscription = new \Ipag\Sdk\Model\SubscriptionV2\SubscriptionV2(
    [
        'profile_id'    => 'SUB_V2_003',
        'starting_date' => '2026-07-01',
        'callback_url'  => 'https://minhaloja.com/callback',
        'card_token'    => '6a64c8c5-1249-4845-b34a-111b54b1beb2',
        'is_active'     => true,
        'customer_id'   => 100022,
        'plan'          => new \Ipag\Sdk\Model\SubscriptionV2\Plan([
            'name'             => 'Plano Semestral',
            'description'      => 'Acesso completo por 6 meses',
            'amount'           => 99.90,
            'frequency'        => 'monthly',
            'interval'         => 1,
            'cycles'           => 6,
            'best_day'         => true,
            'pro_rated_charge' => false,
            'grace_period'     => 0,
            'callback_url'     => 'https://minhaloja.com/callback',
            'installments'     => 1,
            'trial'            => new \Ipag\Sdk\Model\SubscriptionV2\PlanTrial([
                'amount' => 0,
                'cycles' => 1,
            ]),
        ]),
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
