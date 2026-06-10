<?php

namespace Ipag\Sdk\Tests\Model;

use Ipag\Sdk\Model\PaymentLinkV2\CheckoutSettings;
use Ipag\Sdk\Model\PaymentLinkV2\PaymentLinkV2;
use Ipag\Sdk\Model\Schema\Exception\MutatorAttributeException;
use PHPUnit\Framework\TestCase;

class PaymentLinkV2Test extends TestCase
{
    public function testShouldCreatePaymentLinkV2SuccessfullyViaConstructor()
    {
        $paymentLink = new PaymentLinkV2([
            'external_code'  => 'PL_V2_001',
            'amount'         => 150.00,
            'description'    => 'Link de pagamento V2',
            'expires_at'     => '05/02/2026 13:35:00',
            'additional_info' => 'Informações adicionais',
            'stock_limit'    => 100,
        ]);

        $this->assertEquals('PL_V2_001', $paymentLink->getExternalCode());
        $this->assertEquals(150.00, $paymentLink->getAmount());
        $this->assertEquals('Link de pagamento V2', $paymentLink->getDescription());
        $this->assertEquals('05/02/2026 13:35:00', $paymentLink->getExpiresAt());
        $this->assertEquals('Informações adicionais', $paymentLink->getAdditionalInfo());
        $this->assertEquals(100, $paymentLink->getStockLimit());
        $this->assertNull($paymentLink->getCheckoutSettings());
    }

    public function testShouldCreatePaymentLinkV2WithCheckoutSettingsSuccessfully()
    {
        $paymentLink = new PaymentLinkV2([
            'external_code' => 'PL_V2_002',
            'amount'        => 250.00,
            'description'   => 'Link com checkout settings',
            'expires_at'    => '31/12/2026 23:59:59',
            'checkout_settings' => [
                'max_installments'           => 12,
                'min_installment_value'      => 10.00,
                'interest'                   => 1.99,
                'interest_free_installments' => 3,
                'fixed_installment'          => 0,
                'payment_method'             => 'all',
            ],
        ]);

        $this->assertEquals('PL_V2_002', $paymentLink->getExternalCode());
        $this->assertEquals(250.00, $paymentLink->getAmount());
        $this->assertEquals('Link com checkout settings', $paymentLink->getDescription());
        $this->assertEquals('31/12/2026 23:59:59', $paymentLink->getExpiresAt());

        $this->assertInstanceOf(CheckoutSettings::class, $paymentLink->getCheckoutSettings());
        $this->assertEquals(12, $paymentLink->getCheckoutSettings()->getMaxInstallments());
        $this->assertEquals(10.00, $paymentLink->getCheckoutSettings()->getMinInstallmentValue());
        $this->assertEquals(1.99, $paymentLink->getCheckoutSettings()->getInterest());
        $this->assertEquals(3, $paymentLink->getCheckoutSettings()->getInterestFreeInstallments());
        $this->assertEquals(0, $paymentLink->getCheckoutSettings()->getFixedInstallment());
        $this->assertEquals('all', $paymentLink->getCheckoutSettings()->getPaymentMethod());

        $this->assertNull($paymentLink->getAdditionalInfo());
        $this->assertNull($paymentLink->getStockLimit());
    }

    public function testShouldCreatePaymentLinkV2AndSetTheValuesSuccessfully()
    {
        $checkoutSettings = new CheckoutSettings([
            'max_installments'           => 6,
            'min_installment_value'      => 20.00,
            'interest'                   => 2.50,
            'interest_free_installments' => 2,
            'fixed_installment'          => 0,
            'payment_method'             => 'creditcard',
        ]);

        $paymentLink = (new PaymentLinkV2())
            ->setExternalCode('PL_V2_003')
            ->setAmount(300.00)
            ->setDescription('Link via setters')
            ->setExpiresAt('15/06/2026 18:00:00')
            ->setAdditionalInfo('Detalhes do produto')
            ->setStockLimit(25)
            ->setCheckoutSettings($checkoutSettings);

        $this->assertEquals('PL_V2_003', $paymentLink->getExternalCode());
        $this->assertEquals(300.00, $paymentLink->getAmount());
        $this->assertEquals('Link via setters', $paymentLink->getDescription());
        $this->assertEquals('15/06/2026 18:00:00', $paymentLink->getExpiresAt());
        $this->assertEquals('Detalhes do produto', $paymentLink->getAdditionalInfo());
        $this->assertEquals(25, $paymentLink->getStockLimit());

        $this->assertInstanceOf(CheckoutSettings::class, $paymentLink->getCheckoutSettings());
        $this->assertEquals(6, $paymentLink->getCheckoutSettings()->getMaxInstallments());
        $this->assertEquals('creditcard', $paymentLink->getCheckoutSettings()->getPaymentMethod());
    }

    public function testShouldCreateEmptyPaymentLinkV2ObjectSuccessfully()
    {
        $paymentLink = new PaymentLinkV2();

        $this->assertNull($paymentLink->getExternalCode());
        $this->assertNull($paymentLink->getAmount());
        $this->assertNull($paymentLink->getDescription());
        $this->assertNull($paymentLink->getExpiresAt());
        $this->assertNull($paymentLink->getAdditionalInfo());
        $this->assertNull($paymentLink->getStockLimit());
        $this->assertNull($paymentLink->getCheckoutSettings());
    }

    public function testShouldThrowAMutatorExceptionOnThePaymentLinkV2ExpiresAtPropertyInvalidFormat()
    {
        $paymentLink = new PaymentLinkV2();

        $this->expectException(MutatorAttributeException::class);

        $paymentLink->setExpiresAt('2026-02-05 13:35:00');
    }

    public function testShouldThrowAMutatorExceptionOnThePaymentLinkV2ExpiresAtPropertyDateOnly()
    {
        $paymentLink = new PaymentLinkV2();

        $this->expectException(MutatorAttributeException::class);

        $paymentLink->setExpiresAt('05/02/2026');
    }

    public function testShouldThrowATypeExceptionOnThePaymentLinkV2StockLimitProperty()
    {
        $paymentLink = new PaymentLinkV2();

        $this->expectException(\TypeError::class);

        $paymentLink->setStockLimit('não sou inteiro');
    }

    public function testShouldSerializeOnlyNonNullFieldsSuccessfully()
    {
        $paymentLink = (new PaymentLinkV2())
            ->setExternalCode('PL_V2_004')
            ->setAmount(99.90)
            ->setDescription('Link parcial');

        $serialized = $paymentLink->jsonSerialize();

        $this->assertArrayHasKey('external_code', $serialized);
        $this->assertArrayHasKey('amount', $serialized);
        $this->assertArrayHasKey('description', $serialized);

        $this->assertArrayNotHasKey('expires_at', $serialized);
        $this->assertArrayNotHasKey('additional_info', $serialized);
        $this->assertArrayNotHasKey('stock_limit', $serialized);
        $this->assertArrayNotHasKey('checkout_settings', $serialized);
    }

    public function testShouldSerializeCheckoutSettingsWhenPresent()
    {
        $paymentLink = (new PaymentLinkV2())
            ->setExternalCode('PL_V2_005')
            ->setAmount(199.00)
            ->setDescription('Com checkout')
            ->setExpiresAt('01/01/2027 00:00:00')
            ->setCheckoutSettings(new CheckoutSettings([
                'max_installments' => 12,
                'payment_method'   => 'pix',
            ]));

        $serialized = $paymentLink->jsonSerialize();

        $this->assertArrayHasKey('checkout_settings', $serialized);
        $this->assertEquals(12, $serialized['checkout_settings']['max_installments']);
        $this->assertEquals('pix', $serialized['checkout_settings']['payment_method']);
    }

    public function testShouldAcceptNullExpiresAt()
    {
        $paymentLink = new PaymentLinkV2(['expires_at' => null]);

        $this->assertNull($paymentLink->getExpiresAt());
    }
}
