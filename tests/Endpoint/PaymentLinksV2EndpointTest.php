<?php

namespace Ipag\Sdk\Tests\Endpoint;

use GuzzleHttp\Psr7\Response;
use Ipag\Sdk\Exception\HttpException;
use Ipag\Sdk\Model\PaymentLinkV2\PaymentLinkV2;
use Ipag\Sdk\Model\PaymentLinkV2\CheckoutSettings;
use Ipag\Sdk\Tests\IpagClient;

class PaymentLinksV2EndpointTest extends IpagClient
{
    private function mockResponseCreate(): array
    {
        return [
            "id" => 36,
            "resource" => "payment_links",
            "attributes" => [
                "uuid" => "177238cc-d5fe-4817-99e5-4419bd835ed1",
                "external_code" => "PL_V2_001",
                "is_active" => true,
                "stock_limit" => 100,
                "has_stock_limit" => true,
                "amount" => 150.00,
                "description" => "Link de pagamento V2",
                "additional_info" => "Informações adicionais",
                "expires_at" => "05/02/2026 13:35:00",
                "created_at" => "2026-03-09 10:00:00",
                "updated_at" => "2026-03-09 10:00:00",
            ],
            "checkout_settings" => null,
            "links" => [
                "payment" => "https://api.ipag.test/link?t=177238cc-d5fe-4817-99e5-4419bd835ed1"
            ]
        ];
    }

    private function mockResponseList(): array
    {
        return [
            "data" => [
                [
                    "id" => 27,
                    "resource" => "payment_links",
                    "attributes" => [
                        "uuid" => "177238cc-d5fe-4817-99e5-4419bd835ed1",
                        "external_code" => "",
                        "is_active" => false,
                        "stock_limit" => 0,
                        "has_stock_limit" => true,
                        "amount" => 100,
                        "description" => "",
                        "additional_info" => "",
                        "expires_at" => null,
                        "created_at" => "2025-12-02 11:46:48",
                        "updated_at" => "2025-12-02 11:46:48",
                    ],
                    "checkout_settings" => null,
                    "links" => [
                        "payment" => "https://api.ipag.test/link?t=177238cc-d5fe-4817-99e5-4419bd835ed1"
                    ]
                ],
                [
                    "id" => 26,
                    "resource" => "payment_links",
                    "attributes" => [
                        "uuid" => "4ba11da2-1bd9-43ee-84bd-f9a673c7e385",
                        "external_code" => "",
                        "is_active" => false,
                        "stock_limit" => 0,
                        "has_stock_limit" => true,
                        "amount" => 100,
                        "description" => "",
                        "additional_info" => "",
                        "expires_at" => null,
                        "created_at" => "2025-12-02 11:43:41",
                        "updated_at" => "2025-12-02 11:43:41",
                    ],
                    "checkout_settings" => null,
                    "links" => [
                        "payment" => "https://api.ipag.test/link?t=4ba11da2-1bd9-43ee-84bd-f9a673c7e385"
                    ]
                ],
            ],
            "links" => [
                "first" => "https://api.ipag.test/service/v2/payment_links?page=1",
                "last"  => "https://api.ipag.test/service/v2/payment_links?page=1",
                "prev"  => null,
                "next"  => null,
            ],
            "meta" => [
                "current_page" => 1,
                "last_page"    => 1,
                "from"         => 1,
                "to"           => 2,
                "per_page"     => 15,
                "total"        => 2,
            ],
        ];
    }

    // =========================================================================
    // CREATE
    // =========================================================================

    public function testShouldCreatePaymentLinkV2Successfully()
    {
        $this->instanceClient([
            new Response(201, [], json_encode($this->mockResponseCreate()))
        ]);

        $paymentLink = new PaymentLinkV2([
            'external_code'  => 'PL_V2_001',
            'amount'         => 150.00,
            'description'    => 'Link de pagamento V2',
            'expires_at'     => '05/02/2026 13:35:00',
            'additional_info' => 'Informações adicionais',
            'stock_limit'    => 100,
        ]);

        $response = $this->client->paymentLinksV2()->create($paymentLink);

        $this->assertIsObject($response);
        $this->assertSame(36, $response->getParsedPath('id'));
        $this->assertSame('payment_links', $response->getParsedPath('resource'));
        $this->assertSame('PL_V2_001', $response->getParsedPath('attributes.external_code'));
        $this->assertSame(
            'https://api.ipag.test/link?t=177238cc-d5fe-4817-99e5-4419bd835ed1',
            $response->getParsedPath('links.payment')
        );
    }

    public function testShouldCreatePaymentLinkV2WithCheckoutSettingsSuccessfully()
    {
        $mockResponse = $this->mockResponseCreate();
        $mockResponse['checkout_settings'] = [
            'max_installments'           => 12,
            'min_installment_value'      => 10.00,
            'interest'                   => 1.99,
            'interest_free_installments' => 3,
            'fixed_installment'          => 0,
            'payment_method'             => 'all',
        ];

        $this->instanceClient([
            new Response(201, [], json_encode($mockResponse))
        ]);

        $paymentLink = new PaymentLinkV2([
            'external_code'  => 'PL_V2_002',
            'amount'         => 250.00,
            'description'    => 'Link com checkout',
            'expires_at'     => '31/12/2026 23:59:59',
            'checkout_settings' => new CheckoutSettings([
                'max_installments'           => 12,
                'min_installment_value'      => 10.00,
                'interest'                   => 1.99,
                'interest_free_installments' => 3,
                'fixed_installment'          => 0,
                'payment_method'             => 'all',
            ]),
        ]);

        $response = $this->client->paymentLinksV2()->create($paymentLink);

        $this->assertIsObject($response);
        $this->assertSame(36, $response->getParsedPath('id'));
        $this->assertSame(12, $response->getParsedPath('checkout_settings.max_installments'));
        $this->assertSame('all', $response->getParsedPath('checkout_settings.payment_method'));
    }

    // =========================================================================
    // UPDATE
    // =========================================================================

    public function testShouldUpdatePaymentLinkV2Successfully()
    {
        $mockResponse = $this->mockResponseCreate();
        $mockResponse['attributes']['amount'] = 199.90;
        $mockResponse['attributes']['description'] = 'Link atualizado';
        $mockResponse['checkout_settings'] = [
            'max_installments'           => 11,
            'min_installment_value'      => 10.00,
            'interest'                   => 20.00,
            'interest_free_installments' => 2,
            'fixed_installment'          => 5,
            'payment_method'             => 'creditcard',
        ];

        $this->instanceClient([
            new Response(200, [], json_encode($mockResponse))
        ]);

        $paymentLink = new PaymentLinkV2([
            'checkout_settings' => [
                'max_installments'           => 11,
                'min_installment_value'      => 10.00,
                'interest'                   => 20.00,
                'interest_free_installments' => 2,
                'fixed_installment'          => 5,
                'payment_method'             => 'creditcard',
            ],
        ]);

        $response = $this->client->paymentLinksV2()->update($paymentLink, 36);

        $this->assertIsObject($response);
        $this->assertSame(36, $response->getParsedPath('id'));
        $this->assertSame('creditcard', $response->getParsedPath('checkout_settings.payment_method'));
        $this->assertSame(11, $response->getParsedPath('checkout_settings.max_installments'));
    }

    // =========================================================================
    // GET
    // =========================================================================

    public function testShouldGetPaymentLinkV2ByIdSuccessfully()
    {
        $this->instanceClient([
            new Response(200, [], json_encode($this->mockResponseCreate()))
        ]);

        $response = $this->client->paymentLinksV2()->get(36);

        $this->assertIsObject($response);
        $this->assertSame(36, $response->getParsedPath('id'));
        $this->assertSame('payment_links', $response->getParsedPath('resource'));
    }

    // =========================================================================
    // LIST
    // =========================================================================

    public function testShouldListPaymentLinksV2Successfully()
    {
        $this->instanceClient([
            new Response(200, [], json_encode($this->mockResponseList()))
        ]);

        $response = $this->client->paymentLinksV2()->list();
        $data = $response->getData();

        $this->assertIsObject($response);
        $this->assertArrayHasKey('data', $data);
        $this->assertCount(2, $data['data']);
        $this->assertSame(27, $data['data'][0]['id']);
        $this->assertSame(26, $data['data'][1]['id']);
    }

    public function testShouldListPaymentLinksV2WithMetaSuccessfully()
    {
        $this->instanceClient([
            new Response(200, [], json_encode($this->mockResponseList()))
        ]);

        $response = $this->client->paymentLinksV2()->list(['page' => 1]);
        $data = $response->getData();

        $this->assertArrayHasKey('meta', $data);
        $this->assertSame(1, $data['meta']['current_page']);
        $this->assertSame(2, $data['meta']['total']);
        $this->assertSame(15, $data['meta']['per_page']);
    }

    public function testShouldListPaymentLinksV2WithPaginationLinksSuccessfully()
    {
        $this->instanceClient([
            new Response(200, [], json_encode($this->mockResponseList()))
        ]);

        $response = $this->client->paymentLinksV2()->list();
        $data = $response->getData();

        $this->assertArrayHasKey('links', $data);
        $this->assertNotNull($data['links']['first']);
        $this->assertNotNull($data['links']['last']);
        $this->assertNull($data['links']['prev']);
        $this->assertNull($data['links']['next']);
    }

    // =========================================================================
    // LIST TRANSACTIONS
    // =========================================================================

    public function testShouldListPaymentLinkV2TransactionsSuccessfully()
    {
        $mockTransactions = [
            "data" => [
                ["id" => 1, "resource" => "transactions"],
                ["id" => 2, "resource" => "transactions"],
            ],
            "meta" => [
                "current_page" => 1,
                "total"        => 2,
            ],
        ];

        $this->instanceClient([
            new Response(200, [], json_encode($mockTransactions))
        ]);

        $response = $this->client->paymentLinksV2()->listTransactions(36);
        $data = $response->getData();

        $this->assertIsObject($response);
        $this->assertArrayHasKey('data', $data);
        $this->assertCount(2, $data['data']);
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
                        "external_code" => ["External Code is required"],
                    ]
                ])
            )
        ]);

        try {
            $this->client->paymentLinksV2()->create(new PaymentLinkV2());
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
            $this->client->paymentLinksV2()->create(new PaymentLinkV2());
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
                    "resource" => "payment_links"
                ])
            )
        ]);

        try {
            $this->client->paymentLinksV2()->create(new PaymentLinkV2());
        } catch (\Throwable $th) {
            $this->assertInstanceOf(HttpException::class, $th);
            $this->assertSame(403, $th->getCode());
        }
    }
}
