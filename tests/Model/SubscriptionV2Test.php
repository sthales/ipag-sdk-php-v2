<?php

namespace Ipag\Sdk\Tests\Model;

use Ipag\Sdk\Model\Customer;
use Ipag\Sdk\Model\SubscriptionV2\Plan;
use Ipag\Sdk\Model\SubscriptionV2\PlanTrial;
use Ipag\Sdk\Model\SubscriptionV2\SubscriptionV2;
use Ipag\Sdk\Model\Schema\Exception\MutatorAttributeException;
use PHPUnit\Framework\TestCase;

class SubscriptionV2Test extends TestCase
{
    /**
     * Cenário: customer_id + card_token + campos inline (sem plan_id, sem plan object, sem creditcard_token)
     */
    public function testShouldCreateSubscriptionV2WithCustomerIdAndCardTokenSuccessfully()
    {
        $subscription = new SubscriptionV2([
            'profile_id' => 'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
            'description' => 'Plano para a ralé mensal',
            'is_active' => true,
            'starting_date' => '2026-07-10',
            'callback_url' => 'https://minhaloja.com/callback',
            'card_token' => '6a64c8c5-1249-4845-b34a-111b54b1beb2',
            'amount' => 200.00,
            'frequency' => 'monthly',
            'interval' => 5,
            'cycles' => 0,
            'customer_id' => 100022,
        ]);

        $this->assertEquals('a1b2c3d4-e5f6-7890-abcd-ef1234567890', $subscription->getProfileId());
        $this->assertEquals('Plano para a ralé mensal', $subscription->getDescription());
        $this->assertEquals(true, $subscription->getIsActive());
        $this->assertEquals('https://minhaloja.com/callback', $subscription->getCallbackUrl());
        $this->assertEquals('6a64c8c5-1249-4845-b34a-111b54b1beb2', $subscription->getCardToken());
        $this->assertEquals(200.00, $subscription->getAmount());
        $this->assertEquals('monthly', $subscription->getFrequency());
        $this->assertEquals(5, $subscription->getInterval());
        $this->assertEquals(0, $subscription->getCycles());
        $this->assertEquals(100022, $subscription->getCustomerId());

        $this->assertEquals('2026-07-10', $subscription->getStartingDate());

        $this->assertNull($subscription->getCreditCardToken());
        $this->assertNull($subscription->getPlanId());
        $this->assertNull($subscription->getPlan());
        $this->assertNull($subscription->getCustomer());
    }

    /**
     * Cenário: plan_id + creditcard_token + customer_id (sem campos inline, sem plan object, sem card_token)
     */
    public function testShouldCreateSubscriptionV2WithPlanIdAndCreditcardTokenSuccessfully()
    {
        $subscription = new SubscriptionV2([
            'profile_id' => 'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
            'description' => 'Plano para a ralé mensal',
            'is_active' => true,
            'starting_date' => '2026-07-10',
            'callback_url' => 'https://minhaloja.com/callback',
            'creditcard_token' => '123abc',
            'plan_id' => 2,
            'customer_id' => 100022,
        ]);

        $this->assertEquals('a1b2c3d4-e5f6-7890-abcd-ef1234567890', $subscription->getProfileId());
        $this->assertEquals('Plano para a ralé mensal', $subscription->getDescription());
        $this->assertEquals(true, $subscription->getIsActive());
        $this->assertEquals('https://minhaloja.com/callback', $subscription->getCallbackUrl());
        $this->assertEquals('123abc', $subscription->getCreditCardToken());
        $this->assertEquals(2, $subscription->getPlanId());
        $this->assertEquals(100022, $subscription->getCustomerId());

        $this->assertEquals('2026-07-10', $subscription->getStartingDate());

        $this->assertNull($subscription->getCardToken());
        $this->assertNull($subscription->getPlan());
        $this->assertNull($subscription->getCustomer());
        $this->assertNull($subscription->getFrequency());
        $this->assertNull($subscription->getInterval());
        $this->assertNull($subscription->getAmount());
        $this->assertNull($subscription->getCycles());
    }

    /**
     * Cenário: plan object + card_token (sem plan_id, sem customer_id, sem creditcard_token)
     */
    public function testShouldCreateSubscriptionV2WithPlanObjectSuccessfully()
    {
        $subscription = new SubscriptionV2([
            'profile_id' => 'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
            'description' => 'Plano para a ralé mensal',
            'is_active' => true,
            'starting_date' => '2026-07-10',
            'callback_url' => 'https://minhaloja.com/callback',
            'card_token' => '6a64c8c5-1249-4845-b34a-111b54b1beb2',
            'customer_id' => 100022,
            'plan' => [
                'name' => 'Plano da Lojinha Básico',
                'description' => 'Plano para a ralé mensal',
                'amount' => 200.00,
                'frequency' => 'monthly',
                'interval' => 5,
                'cycles' => 0,
                'best_day' => true,
                'pro_rated_charge' => true,
                'grace_period' => 0,
                'callback_url' => 'https://minhaloja.com/callback',
                'trial' => [
                    'amount' => 10.00,
                    'cycles' => 1,
                ],
            ],
        ]);

        $this->assertEquals('a1b2c3d4-e5f6-7890-abcd-ef1234567890', $subscription->getProfileId());
        $this->assertEquals('Plano para a ralé mensal', $subscription->getDescription());
        $this->assertEquals(true, $subscription->getIsActive());
        $this->assertEquals('6a64c8c5-1249-4845-b34a-111b54b1beb2', $subscription->getCardToken());
        $this->assertEquals(100022, $subscription->getCustomerId());

        $this->assertInstanceOf(Plan::class, $subscription->getPlan());
        $this->assertEquals('Plano da Lojinha Básico', $subscription->getPlan()->getName());
        $this->assertEquals(200.00, $subscription->getPlan()->getAmount());
        $this->assertEquals('monthly', $subscription->getPlan()->getFrequency());
        $this->assertEquals(5, $subscription->getPlan()->getInterval());
        $this->assertEquals(0, $subscription->getPlan()->getCycles());
        $this->assertEquals(true, $subscription->getPlan()->getBestDay());
        $this->assertEquals(true, $subscription->getPlan()->getProRatedCharge());
        $this->assertEquals(0, $subscription->getPlan()->getGracePeriod());
        $this->assertEquals(10.00, $subscription->getPlan()->getTrial()->getAmount());
        $this->assertEquals(1, $subscription->getPlan()->getTrial()->getCycles());

        $this->assertNull($subscription->getCreditCardToken());
        $this->assertNull($subscription->getPlanId());
    }

    /**
     * Cenário: setters fluentes com customer_id + card_token + campos inline
     */
    public function testShouldCreateSubscriptionV2AndSetTheValuesSuccessfully()
    {
        $subscription = (new SubscriptionV2())
            ->setProfileId('a1b2c3d4-e5f6-7890-abcd-ef1234567890')
            ->setDescription('Plano para a ralé mensal')
            ->setIsActive(true)
            ->setStartingDate('2026-07-10')
            ->setCallbackUrl('https://minhaloja.com/callback')
            ->setCardToken('6a64c8c5-1249-4845-b34a-111b54b1beb2')
            ->setAmount(200.00)
            ->setFrequency('monthly')
            ->setInterval(5)
            ->setCycles(0)
            ->setCustomerId(100022);

        $this->assertEquals('a1b2c3d4-e5f6-7890-abcd-ef1234567890', $subscription->getProfileId());
        $this->assertEquals('Plano para a ralé mensal', $subscription->getDescription());
        $this->assertEquals(true, $subscription->getIsActive());
        $this->assertEquals('https://minhaloja.com/callback', $subscription->getCallbackUrl());
        $this->assertEquals('6a64c8c5-1249-4845-b34a-111b54b1beb2', $subscription->getCardToken());
        $this->assertEquals(200.00, $subscription->getAmount());
        $this->assertEquals('monthly', $subscription->getFrequency());
        $this->assertEquals(5, $subscription->getInterval());
        $this->assertEquals(0, $subscription->getCycles());
        $this->assertEquals(100022, $subscription->getCustomerId());

        $this->assertEquals('2026-07-10', $subscription->getStartingDate());
    }

    public function testShouldCreateEmptySubscriptionV2ObjectSuccessfully()
    {
        $subscription = new SubscriptionV2();

        $this->assertNull($subscription->getProfileId());
        $this->assertNull($subscription->getDescription());
        $this->assertNull($subscription->getIsActive());
        $this->assertNull($subscription->getStartingDate());
        $this->assertNull($subscription->getCallbackUrl());
        $this->assertNull($subscription->getCardToken());
        $this->assertNull($subscription->getCreditCardToken());
        $this->assertNull($subscription->getPlanId());
        $this->assertNull($subscription->getCustomerId());
        $this->assertNull($subscription->getPlan());
        $this->assertNull($subscription->getFrequency());
        $this->assertNull($subscription->getInterval());
        $this->assertNull($subscription->getAmount());
        $this->assertNull($subscription->getCycles());
        $this->assertNull($subscription->getCustomer());
    }

    public function testShouldThrowATypeExceptionOnTheSubscriptionV2PlanIdProperty()
    {
        $subscription = new SubscriptionV2();

        $this->expectException(\TypeError::class);

        $subscription->setPlanId('AAA');
    }

    public function testShouldThrowATypeExceptionOnTheSubscriptionV2CustomerIdProperty()
    {
        $subscription = new SubscriptionV2();

        $this->expectException(\TypeError::class);

        $subscription->setCustomerId('AAA');
    }

    public function testShouldThrowATypeExceptionOnTheSubscriptionV2IntervalProperty()
    {
        $subscription = new SubscriptionV2();

        $this->expectException(\TypeError::class);

        $subscription->setInterval('AAA');
    }

    public function testShouldThrowATypeExceptionOnTheSubscriptionV2CyclesProperty()
    {
        $subscription = new SubscriptionV2();

        $this->expectException(\TypeError::class);

        $subscription->setCycles('AAA');
    }

    public function testShouldThrowAMutatorExceptionOnTheSubscriptionV2StartingDateProperty()
    {
        $subscription = new SubscriptionV2();

        $this->expectException(MutatorAttributeException::class);

        $subscription->setStartingDate('10/07/2026');
    }

    public function testShouldSerializeOnlyNonNullFieldsSuccessfully()
    {
        $subscription = (new SubscriptionV2())
            ->setDescription('Plano para a ralé mensal')
            ->setCardToken('6a64c8c5-1249-4845-b34a-111b54b1beb2')
            ->setCustomerId(100022);

        $serialized = $subscription->jsonSerialize();

        $this->assertArrayHasKey('description', $serialized);
        $this->assertArrayHasKey('card_token', $serialized);
        $this->assertArrayHasKey('customer_id', $serialized);

        $this->assertArrayNotHasKey('profile_id', $serialized);
        $this->assertArrayNotHasKey('creditcard_token', $serialized);
        $this->assertArrayNotHasKey('plan_id', $serialized);
        $this->assertArrayNotHasKey('plan', $serialized);
        $this->assertArrayNotHasKey('customer', $serialized);
        $this->assertArrayNotHasKey('frequency', $serialized);
        $this->assertArrayNotHasKey('interval', $serialized);
        $this->assertArrayNotHasKey('amount', $serialized);
        $this->assertArrayNotHasKey('cycles', $serialized);
    }

    /**
     * Cenário: customer object via constructor (relação has)
     */
    public function testShouldCreateSubscriptionV2WithCustomerObjectSuccessfully()
    {
        $subscription = new SubscriptionV2([
            'profile_id' => 'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
            'description' => 'Assinatura com customer object',
            'card_token' => '6a64c8c5-1249-4845-b34a-111b54b1beb2',
            'customer' => [
                'name' => 'João da Silva',
                'email' => 'joao@email.com',
                'cpf_cnpj' => '12345678909',
                'phone' => '11999999999',
            ],
        ]);

        $this->assertInstanceOf(Customer::class, $subscription->getCustomer());
        $this->assertEquals('João da Silva', $subscription->getCustomer()->getName());
        $this->assertEquals('joao@email.com', $subscription->getCustomer()->getEmail());
        $this->assertEquals('12345678909', $subscription->getCustomer()->getCpfCnpj());
        $this->assertEquals('11999999999', $subscription->getCustomer()->getPhone());

        $this->assertNull($subscription->getCustomerId());
    }

    /**
     * Cenário: customer object via setter (relação has)
     */
    public function testShouldCreateSubscriptionV2WithCustomerObjectViaSetterSuccessfully()
    {
        $customer = new Customer([
            'name' => 'Maria Souza',
            'email' => 'maria@email.com',
            'cpf_cnpj' => '98765432100',
        ]);

        $subscription = (new SubscriptionV2())
            ->setDescription('Assinatura via setter')
            ->setCardToken('6a64c8c5-1249-4845-b34a-111b54b1beb2')
            ->setCustomer($customer);

        $this->assertInstanceOf(Customer::class, $subscription->getCustomer());
        $this->assertEquals('Maria Souza', $subscription->getCustomer()->getName());
        $this->assertEquals('maria@email.com', $subscription->getCustomer()->getEmail());
        $this->assertEquals('98765432100', $subscription->getCustomer()->getCpfCnpj());
    }

    /**
     * Cenário: plan object via setter (relação has)
     */
    public function testShouldCreateSubscriptionV2WithPlanObjectViaSetterSuccessfully()
    {
        $plan = new Plan([
            'name' => 'Plano Premium',
            'description' => 'Plano premium mensal',
            'amount' => 99.90,
            'frequency' => 'monthly',
            'interval' => 1,
            'cycles' => 12,
            'trial' => [
                'amount' => 0.00,
                'cycles' => 1,
            ],
        ]);

        $subscription = (new SubscriptionV2())
            ->setDescription('Assinatura com plano via setter')
            ->setCardToken('6a64c8c5-1249-4845-b34a-111b54b1beb2')
            ->setCustomerId(100022)
            ->setPlan($plan);

        $this->assertInstanceOf(Plan::class, $subscription->getPlan());
        $this->assertEquals('Plano Premium', $subscription->getPlan()->getNome());
        $this->assertEquals(99.90, $subscription->getPlan()->getAmount());
        $this->assertEquals('monthly', $subscription->getPlan()->getFrequency());
        $this->assertEquals(1, $subscription->getPlan()->getInterval());
        $this->assertEquals(12, $subscription->getPlan()->getCycles());
        $this->assertInstanceOf(PlanTrial::class, $subscription->getPlan()->getTrial());
        $this->assertEquals(0.00, $subscription->getPlan()->getTrial()->getAmount());
        $this->assertEquals(1, $subscription->getPlan()->getTrial()->getCycles());
    }

    /**
     * Cenário: customer object com address aninhado (relação has -> has)
     */
    public function testShouldCreateSubscriptionV2WithCustomerAndAddressObjectSuccessfully()
    {
        $subscription = new SubscriptionV2([
            'profile_id' => 'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
            'description' => 'Assinatura com customer e address',
            'starting_date' => '2026-07-10',
            'callback_url' => 'https://minhaloja.com/callback',
            'card_token' => '6a64c8c5-1249-4845-b34a-111b54b1beb2',
            'customer' => [
                'name' => 'Gabriel César Brusarrosco',
                'cpf_cnpj' => '51526092077',
                'email' => 'gabriel.brusarrosco@ipag.com.br',
                'phone' => '18997512082',
                'address' => [
                    'street' => 'Rua Gino Piron',
                    'number' => '697',
                    'district' => 'Jd. Vale do Sol',
                    'complement' => '',
                    'city' => 'Presidente Prudente',
                    'state' => 'SP',
                    'zipcode' => '19030-130',
                ],
            ],
        ]);

        $this->assertInstanceOf(Customer::class, $subscription->getCustomer());
        $this->assertEquals('Gabriel César Brusarrosco', $subscription->getCustomer()->getName());
        $this->assertEquals('51526092077', $subscription->getCustomer()->getCpfCnpj());
        $this->assertEquals('gabriel.brusarrosco@ipag.com.br', $subscription->getCustomer()->getEmail());
        $this->assertEquals('18997512082', $subscription->getCustomer()->getPhone());

        $this->assertNotNull($subscription->getCustomer()->getAddress());
        $this->assertEquals('Rua Gino Piron', $subscription->getCustomer()->getAddress()->getStreet());
        $this->assertEquals('697', $subscription->getCustomer()->getAddress()->getNumber());
        $this->assertEquals('Jd. Vale do Sol', $subscription->getCustomer()->getAddress()->getDistrict());
        $this->assertEquals('Presidente Prudente', $subscription->getCustomer()->getAddress()->getCity());
        $this->assertEquals('SP', $subscription->getCustomer()->getAddress()->getState());
        $this->assertEquals('19030130', $subscription->getCustomer()->getAddress()->getZipcode());
    }

    /**
     * Cenário: plan object com todos os campos do validator (installments, callback_url)
     */
    public function testShouldCreateSubscriptionV2WithFullPlanObjectSuccessfully()
    {
        $subscription = new SubscriptionV2([
            'profile_id' => 'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
            'starting_date' => '2026-07-10',
            'callback_url' => 'https://minhaloja.com/callback',
            'card_token' => '6a64c8c5-1249-4845-b34a-111b54b1beb2',
            'customer_id' => 100022,
            'plan' => [
                'name' => 'Plano Completo',
                'description' => 'Plano com todos os campos',
                'amount' => 500.00,
                'frequency' => 'monthly',
                'interval' => 1,
                'cycles' => 12,
                'best_day' => true,
                'pro_rated_charge' => false,
                'grace_period' => 5,
                'callback_url' => 'https://webhook.site/callback',
                'installments' => 3,
                'trial' => [
                    'amount' => 0.00,
                    'cycles' => 2,
                ],
            ],
        ]);

        $plan = $subscription->getPlan();
        $this->assertInstanceOf(Plan::class, $plan);
        $this->assertEquals('Plano Completo', $plan->getNome());
        $this->assertEquals('Plano com todos os campos', $plan->getDescription());
        $this->assertEquals(500.00, $plan->getAmount());
        $this->assertEquals('monthly', $plan->getFrequency());
        $this->assertEquals(1, $plan->getInterval());
        $this->assertEquals(12, $plan->getCycles());
        $this->assertEquals(true, $plan->getBestDay());
        $this->assertEquals(false, $plan->getProRatedCharge());
        $this->assertEquals(5, $plan->getGracePeriod());
        $this->assertEquals('https://webhook.site/callback', $plan->getCallbackUrl());
        $this->assertEquals(3, $plan->getInstallments());
        $this->assertInstanceOf(PlanTrial::class, $plan->getTrial());
        $this->assertEquals(0.00, $plan->getTrial()->getAmount());
        $this->assertEquals(2, $plan->getTrial()->getCycles());
    }

    /**
     * Cenário: preencher campos e depois setar null (nullable)
     */
    public function testShouldCreateAndSetNullPropertiesSubscriptionV2ObjectSuccessfully()
    {
        $subscription = new SubscriptionV2([
            'profile_id' => 'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
            'description' => 'Plano para a ralé mensal',
            'is_active' => true,
            'starting_date' => '2026-07-10',
            'callback_url' => 'https://minhaloja.com/callback',
            'card_token' => '6a64c8c5-1249-4845-b34a-111b54b1beb2',
            'amount' => 200.00,
            'frequency' => 'monthly',
            'interval' => 5,
            'cycles' => 0,
            'customer_id' => 100022,
        ]);

        $subscription
            ->setProfileId(null)
            ->setDescription(null)
            ->setIsActive(null)
            ->setStartingDate(null)
            ->setCallbackUrl(null)
            ->setCardToken(null)
            ->setCreditCardToken(null)
            ->setAmount(null)
            ->setFrequency(null)
            ->setInterval(null)
            ->setCycles(null)
            ->setCustomerId(null)
            ->setPlanId(null);

        $this->assertNull($subscription->getProfileId());
        $this->assertNull($subscription->getDescription());
        $this->assertNull($subscription->getIsActive());
        $this->assertNull($subscription->getStartingDate());
        $this->assertNull($subscription->getCallbackUrl());
        $this->assertNull($subscription->getCardToken());
        $this->assertNull($subscription->getCreditCardToken());
        $this->assertNull($subscription->getAmount());
        $this->assertNull($subscription->getFrequency());
        $this->assertNull($subscription->getInterval());
        $this->assertNull($subscription->getCycles());
        $this->assertNull($subscription->getCustomerId());
        $this->assertNull($subscription->getPlanId());
    }
}
