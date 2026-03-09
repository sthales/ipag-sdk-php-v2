<?php

namespace Ipag\Sdk\Model\SubscriptionV2;

use Ipag\Sdk\Model\Model;
use Ipag\Sdk\Model\Schema\Schema;
use Ipag\Sdk\Model\Schema\SchemaBuilder;

class Plan extends Model
{
    public function __construct(?array $data = [])
    {
        parent::__construct($data);
    }

    protected function schema(SchemaBuilder $schema): Schema
    {
        $schema->string('name')->limit(255)->nullable();
        $schema->string('description')->limit(1000)->nullable();
        $schema->float('amount')->nullable();
        $schema->string('frequency')->limit(20)->nullable();
        $schema->int('interval')->nullable();
        $schema->int('cycles')->nullable();
        $schema->bool('best_day')->nullable();
        $schema->bool('pro_rated_charge')->nullable();
        $schema->int('grace_period')->nullable();
        $schema->string('callback_url')->nullable();
        $schema->int('installments')->nullable();
        $schema->has('trial', PlanTrial::class)->nullable();

        return $schema->build();
    }

    public function jsonSerialize(): array
    {
        return array_filter(parent::jsonSerialize(), fn($v) => !is_null($v));
    }

    public function getNome(): ?string
    {
        return $this->get('name');
    }

    public function setName(?string $name): self
    {
        $this->set('name', $name);
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->get('description');
    }

    public function setDescription(?string $description): self
    {
        $this->set('description', $description);
        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->get('amount');
    }

    public function setAmount(?float $amount): self
    {
        $this->set('amount', $amount);
        return $this;
    }

    public function getFrequency(): ?string
    {
        return $this->get('frequency');
    }

    public function setFrequency(?string $frequency): self
    {
        $this->set('frequency', $frequency);
        return $this;
    }

    public function getInterval(): ?int
    {
        return $this->get('interval');
    }

    public function setInterval(?int $interval): self
    {
        $this->set('interval', $interval);
        return $this;
    }

    public function getCycles(): ?int
    {
        return $this->get('cycles');
    }

    public function setCycles(?int $cycles): self
    {
        $this->set('cycles', $cycles);
        return $this;
    }

    public function getBestDay(): ?bool
    {
        return $this->get('best_day');
    }

    public function setBestDay(?bool $bestDay): self
    {
        $this->set('best_day', $bestDay);
        return $this;
    }

    public function getProRatedCharge(): ?bool
    {
        return $this->get('pro_rated_charge');
    }

    public function setProRatedCharge(?bool $proRatedCharge): self
    {
        $this->set('pro_rated_charge', $proRatedCharge);
        return $this;
    }

    public function getGracePeriod(): ?int
    {
        return $this->get('grace_period');
    }

    public function setGracePeriod(?int $gracePeriod): self
    {
        $this->set('grace_period', $gracePeriod);
        return $this;
    }

    public function getCallbackUrl(): ?string
    {
        return $this->get('callback_url');
    }

    public function setCallbackUrl(?string $callbackUrl): self
    {
        $this->set('callback_url', $callbackUrl);
        return $this;
    }

    public function getInstallments(): ?int
    {
        return $this->get('installments');
    }

    public function setInstallments(?int $installments): self
    {
        $this->set('installments', $installments);
        return $this;
    }

    public function getTrial(): ?PlanTrial
    {
        return $this->get('trial');
    }

    public function setTrial(?PlanTrial $trial): self
    {
        $this->set('trial', $trial);
        return $this;
    }
}
