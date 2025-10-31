<?php

namespace Ipag\Sdk\Tests\Model;

use PHPUnit\Framework\TestCase;
use Ipag\Sdk\Model\Schema\Exception\MutatorAttributeException;

class SplitRulesTest extends TestCase
{
    public function testShouldCreateSplitRulesObjectWithConstructorSuccessfully()
    {
        $splitRules = new \Ipag\Sdk\Model\SplitRules([
            "seller_id" => "1000000",
            "percentage" => 10.00,
            "amount" => 9.99,
            "charge_processing_fee" => false,
            "hold_receivables" => true
        ]);

        $this->assertEquals("1000000", $splitRules->getSellerId());
        $this->assertEquals(10.00, $splitRules->getPercentage());
        $this->assertEquals(9.99, $splitRules->getAmount());
        $this->assertEquals(false, $splitRules->getChargeProcessingFee());
        $this->assertEquals(true, $splitRules->getHoldReceivables());

    }

    public function testShouldCreateSplitRulesObjectAndSetTheValuesSuccessfully()
    {
        $splitRules = (new \Ipag\Sdk\Model\SplitRules())
            ->setSellerId("1000000")
            ->setPercentage(10.00)
            ->setAmount(9.99)
            ->setChargeProcessingFee(false)
            ->setHoldReceivables(true);

        $this->assertEquals("1000000", $splitRules->getSellerId());
        $this->assertEquals(10.00, $splitRules->getPercentage());
        $this->assertEquals(9.99, $splitRules->getAmount());
        $this->assertEquals(false, $splitRules->getChargeProcessingFee());
        $this->assertEquals(true, $splitRules->getHoldReceivables());

    }

    public function testShouldCreateEmptySplitRulesObjectSuccessfully()
    {
        $splitRules = new \Ipag\Sdk\Model\SplitRules();

        $this->assertEmpty($splitRules->getSellerId());
        $this->assertEmpty($splitRules->getPercentage());
        $this->assertEmpty($splitRules->getAmount());
        $this->assertEmpty($splitRules->getChargeProcessingFee());
        $this->assertEmpty($splitRules->getHoldReceivables());

    }

    public function testCreateAndSetEmptyPropertiesSplitRulesObjectSuccessfully()
    {
        $splitRules = new \Ipag\Sdk\Model\SplitRules([
            "seller_id" => "1000000",
            "percentage" => 10.00,
            "amount" => 9.99,
            "charge_processing_fee" => false,
            "hold_receivables" => true
        ]);

        $splitRules
            ->setSellerId(null)
            ->setPercentage(null)
            ->setAmount(null)
            ->setChargeProcessingFee(null)
            ->setHoldReceivables(null);

        $this->assertEmpty($splitRules->getSellerId());
        $this->assertEmpty($splitRules->getPercentage());
        $this->assertEmpty($splitRules->getAmount());
        $this->assertEmpty($splitRules->getChargeProcessingFee());
        $this->assertEmpty($splitRules->getHoldReceivables());

    }

    public function testShouldThrowAValidationExceptionOnTheSplitRulesAmountProperty()
    {
        $splitRules = new \Ipag\Sdk\Model\SplitRules();

        $this->expectException(MutatorAttributeException::class);

        $splitRules->setAmount(-1);
    }

    public function testShouldThrowAValidationExceptionOnTheSplitRulesPercentageProperty()
    {
        $splitRules = new \Ipag\Sdk\Model\SplitRules();

        $this->expectException(MutatorAttributeException::class);

        $splitRules->setPercentage(-1);
    }

}
