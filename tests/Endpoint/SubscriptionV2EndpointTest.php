<?php

namespace Ipag\Sdk\Tests\Endpoint;

use GuzzleHttp\Psr7\Response;
use Ipag\Sdk\Exception\HttpException;
use Ipag\Sdk\Model\SubscriptionV2\Plan;
use Ipag\Sdk\Model\SubscriptionV2\PlanTrial;
use Ipag\Sdk\Model\SubscriptionV2\SubscriptionV2;
use Ipag\Sdk\Tests\IpagClient;

class SubscriptionV2EndpointTest extends IpagClient
{
    private function mockResponseCreate(): array
    {
        return [
            "id" => 275,
            "resource" => "subscriptions",
            "attributes" => [
                "is_active" => true,
                "status" => "pending",
                "description" => "Plano para a ralé mensal",
                "profile_id" => "fcf688dd-33a3-4225-9fe8-558fe43266ed",
                "amount" => 200,
                "frequency" => "monthly",
                "interval" => 5,
                "starting_date" => "2026-07-10",
                "callback_url" => "https://minhaloja.com/callback",
                "plan" => null,
                "customer" => [
                    "id" => 100022,
                    "resource" => "customers",
                    "attributes" => [
                        "name" => "Gabriel César Brusarrosco",
                        "cpf_cnpj" => "077.588.670-06",
                        "email" => "gabriel.brusarrosco@ipag.com.br",
                        "phone" => "18997512082",
                    ]
                ],
            ]
        ];
    }

    // =========================================================================
    // CREATE — Forma 1: campos diretos + customer_id
    // =========================================================================

    public function testShouldCreateSubscriptionV2WithDirectFieldsSuccessfully()
    {
        $this->instanceClient([
            new Response(201, [], json_encode($this->mockResponseCreate()))
        ]);

        $subscription = new SubscriptionV2([
            'profile_id'    => 'SUB_V2_001',
            'description'   => 'Plano mensal premium',
            'starting_date' => '2026-07-10',
            'callback_url'  => 'https://minhaloja.com/callback',
            'card_token'    => '6a64c8c5-1249-4845-b34a-111b54b1beb2',
            'is_active'     => true,
            'frequency'     => 'monthly',
            'interval'      => 5,
            'amount'        => 200.00,
            'cycles'        => 0,
            'customer_id'   => 100022,
        ]);

        $response = $this->client->subscriptionV2()->create($subscription);

        $this->assertIsObject($response);
        $this->assertSame(275, $response->getParsedPath('id'));
        $this->assertSame('subscriptions', $response->getParsedPath('resource'));
        $this->assertSame('pending', $response->getParsedPath('attributes.status'));
    }

    // =========================================================================
    // CREATE — Forma 2: plan_id + customer_id
    // =========================================================================

    public function testShouldCreateSubscriptionV2WithPlanIdSuccessfully()
    {
        $this->instanceClient([
            new Response(201, [], json_encode($this->mockResponseCreate()))
        ]);

        $subscription = new SubscriptionV2([
            'profile_id'    => 'SUB_V2_002',
            'starting_date' => '2026-07-10',
            'callback_url'  => 'https://minhaloja.com/callback',
            'card_token'    => '6a64c8c5-1249-4845-b34a-111b54b1beb2',
            'is_active'     => true,
            'plan_id'       => 2,
            'customer_id'   => 100022,
        ]);

        $response = $this->client->subscriptionV2()->create($subscription);

        $this->assertIsObject($response);
        $this->assertSame(275, $response->getParsedPath('id'));
        $this->assertSame('subscriptions', $response->getParsedPath('resource'));
    }

    // =========================================================================
    // CREATE — Forma 3: plan object inline + customer_id
    // =========================================================================

    public function testShouldCreateSubscriptionV2WithPlanObjectSuccessfully()
    {
        $this->instanceClient([
            new Response(201, [], json_encode($this->mockResponseCreate()))
        ]);

        $subscription = new SubscriptionV2([
            'profile_id'    => 'SUB_V2_003',
            'starting_date' => '2026-07-10',
            'callback_url'  => 'https://minhaloja.com/callback',
            'card_token'    => '6a64c8c5-1249-4845-b34a-111b54b1beb2',
            'is_active'     => true,
            'customer_id'   => 100022,
            'plan'          => new Plan([
                'name'             => 'Plano da Lojinha Básico',
                'description'      => 'Plano para a ralé mensal',
                'amount'           => 200.00,
                'frequency'        => 'monthly',
                'interval'         => 5,
                'cycles'           => 0,
                'best_day'         => true,
                'pro_rated_charge' => true,
                'grace_period'     => 0,
                'callback_url'     => 'https://minhaloja.com/callback',
                'installments'     => 1,
                'trial'            => new PlanTrial([
                    'amount' => 10.00,
                    'cycles' => 1,
                ]),
            ]),
        ]);

        $response = $this->client->subscriptionV2()->create($subscription);

        $this->assertIsObject($response);
        $this->assertSame(275, $response->getParsedPath('id'));
        $this->assertSame('subscriptions', $response->getParsedPath('resource'));
    }

    // =========================================================================
    // UPDATE
    // =========================================================================

    public function testShouldUpdateSubscriptionV2Successfully()
    {
        $this->instanceClient([
            new Response(200, [], json_encode(array_merge(
                $this->mockResponseCreate(),
                ['attributes' => array_merge(
                    $this->mockResponseCreate()['attributes'],
                    ['is_active' => false, 'amount' => 249.90]
                )]
            )))
        ]);

        $subscription = new SubscriptionV2([
            'is_active' => false,
            'amount'    => 249.90,
        ]);

        $response = $this->client->subscriptionV2()->update($subscription, 275);

        $this->assertIsObject($response);
        $this->assertSame(275, $response->getParsedPath('id'));
        $this->assertFalse($response->getParsedPath('attributes.is_active'));
    }

    // =========================================================================
    // Erros HTTP
    // =========================================================================

    public function testShouldResponseFailUnprocessableDataClient()
    {
        $this->instanceClient([
            new Response(
                406,
                [],
                json_encode((object) [
                    "code" => "406",
                    "message" => [
                        "profile_id" => ["Profile Id is required"]
                    ]
                ])
            )
        ]);

        try {
            $this->client->subscriptionV2()->create(new SubscriptionV2());
        } catch (\Throwable $th) {
            $this->assertInstanceOf(HttpException::class, $th);
            $this->assertSame(406, $th->getCode());
        }
    }

    public function testShouldResponseFailUnauthenticatedClient()
    {
        $this->instanceClient([
            new Response(
                401,
                [],
                json_encode((object) [
                    "code" => 401,
                    "message" => "Unauthorized",
                    "resource" => "authorization"
                ])
            )
        ]);

        try {
            $this->client->subscriptionV2()->create(new SubscriptionV2());
        } catch (\Throwable $th) {
            $this->assertInstanceOf(HttpException::class, $th);
            $this->assertSame(401, $th->getCode());
        }
    }

    public function testShouldResponseFailUnauthorizedClient()
    {
        $this->instanceClient([
            new Response(
                403,
                [],
                json_encode((object) [
                    "code" => 403,
                    "message" => "Not Authorized",
                    "resource" => "subscriptions"
                ])
            )
        ]);

        try {
            $this->client->subscriptionV2()->create(new SubscriptionV2());
        } catch (\Throwable $th) {
            $this->assertInstanceOf(HttpException::class, $th);
            $this->assertSame(403, $th->getCode());
        }
    }
}
