<?php

namespace Ipag\Sdk\Model\PaymentLinkV2;

use Ipag\Sdk\Model\Model;
use Ipag\Sdk\Model\Schema\Schema;
use Ipag\Sdk\Model\Schema\SchemaBuilder;

class CheckoutSettings extends Model
{
    public function __construct(?array $data = [])
    {
        parent::__construct($data);
    }

    protected function schema(SchemaBuilder $schema): Schema
    {
        $schema->int('max_installments')->nullable();
        $schema->float('min_installment_value')->nullable();
        $schema->float('interest')->nullable();
        $schema->int('interest_free_installments')->nullable();
        $schema->int('fixed_installment')->nullable();
        $schema->string('payment_method')->nullable();

        return $schema->build();
    }

    public function getMaxInstallments(): ?int
    {
        return $this->get('max_installments');
    }

    public function setMaxInstallments(?int $maxInstallments): self
    {
        $this->set('max_installments', $maxInstallments);
        return $this;
    }

    public function getMinInstallmentValue(): ?float
    {
        return $this->get('min_installment_value');
    }

    public function setMinInstallmentValue(?float $minInstallmentValue): self
    {
        $this->set('min_installment_value', $minInstallmentValue);
        return $this;
    }

    public function getInterest(): ?float
    {
        return $this->get('interest');
    }

    public function setInterest(?float $interest): self
    {
        $this->set('interest', $interest);
        return $this;
    }

    public function getInterestFreeInstallments(): ?int
    {
        return $this->get('interest_free_installments');
    }

    public function setInterestFreeInstallments(?int $interestFreeInstallments): self
    {
        $this->set('interest_free_installments', $interestFreeInstallments);
        return $this;
    }

    public function getFixedInstallment(): ?int
    {
        return $this->get('fixed_installment');
    }

    public function setFixedInstallment(?int $fixedInstallment): self
    {
        $this->set('fixed_installment', $fixedInstallment);
        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->get('payment_method');
    }

    public function setPaymentMethod(?string $paymentMethod): self
    {
        $this->set('payment_method', $paymentMethod);
        return $this;
    }
}
