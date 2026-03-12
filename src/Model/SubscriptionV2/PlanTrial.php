<?php

namespace Ipag\Sdk\Model\SubscriptionV2;

use Ipag\Sdk\Model\Model;
use Ipag\Sdk\Model\Schema\Schema;
use Ipag\Sdk\Model\Schema\SchemaBuilder;

class PlanTrial extends Model
{
    public function __construct(?array $data = [])
    {
        parent::__construct($data);
    }

    protected function schema(SchemaBuilder $schema): Schema
    {
        $schema->float('amount')->nullable();
        $schema->int('cycles')->nullable();

        return $schema->build();
    }

    public function jsonSerialize(): array
    {
        return array_filter(parent::jsonSerialize(), fn($v) => !is_null($v));
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

    public function getCycles(): ?int
    {
        return $this->get('cycles');
    }

    public function setCycles(?int $cycles): self
    {
        $this->set('cycles', $cycles);
        return $this;
    }
}
