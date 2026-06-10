<?php

namespace Ipag\Sdk\Tests\Model;

use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    public function testShouldCreateAddressObjectWithConstructorSuccessfully()
    {
        $address = new \Ipag\Sdk\Model\Address([
            'street' => 'Rua Agenor Vieira',
            'number' => 768,
            'district' => 'São Francisco',
            'city' => 'São Luís',
            'complement' => 'Sala 001',
            'state' => 'MA',
            'zipcode' => '65076-020',
            'country' => 'BR'
        ]);

        $this->assertEquals($address->getComplement(), 'Sala 001');
        $this->assertEquals($address->getStreet(), 'Rua Agenor Vieira');
        $this->assertEquals($address->getNumber(), '768');
        $this->assertEquals($address->getDistrict(), 'São Francisco');
        $this->assertEquals($address->getCity(), 'São Luís');
        $this->assertEquals($address->getState(), 'MA');
        $this->assertEquals($address->getZipcode(), '65076020');
        $this->assertEquals($address->getCountry(), 'BR');
    }

    public function testShouldCreateAddressObjectAndSetTheValuesSuccessfully()
    {
        $address = (new \Ipag\Sdk\Model\Address())
            ->setStreet('Rua Agenor Vieira')
            ->setNumber('768')
            ->setDistrict('São Francisco')
            ->setComplement('Sala 001')
            ->setCity('São Luís')
            ->setState('MA')
            ->setZipcode('65076020')
            ->setCountry('BR');

        $this->assertEquals($address->getComplement(), 'Sala 001');
        $this->assertEquals($address->getStreet(), 'Rua Agenor Vieira');
        $this->assertEquals($address->getNumber(), '768');
        $this->assertEquals($address->getDistrict(), 'São Francisco');
        $this->assertEquals($address->getCity(), 'São Luís');
        $this->assertEquals($address->getState(), 'MA');
        $this->assertEquals($address->getZipcode(), '65076020');
        $this->assertEquals($address->getCountry(), 'BR');
    }

    public function testShouldCreateEmptyAddressObjectSuccessfully()
    {
        $address = new \Ipag\Sdk\Model\Address();

        $this->assertEmpty($address->getComplement());
        $this->assertEmpty($address->getStreet());
        $this->assertEmpty($address->getNumber());
        $this->assertEmpty($address->getDistrict());
        $this->assertEmpty($address->getCity());
        $this->assertEmpty($address->getState());
        $this->assertEmpty($address->getZipcode());
        $this->assertEmpty($address->getComplement());
        $this->assertEmpty($address->getCountry());
    }

    public function testCreateAndSetEmptyPropertiesAddressObjectSuccessfully()
    {
        $address = new \Ipag\Sdk\Model\Address([
            'street' => 'Rua Agenor Vieira',
            'number' => 768,
            'district' => 'São Francisco',
            'city' => 'São Luís',
            'complement' => 'Sala 001',
            'state' => 'MA',
            'zipcode' => '65076-020',
            'country' => 'BR'
        ]);

        $address
            ->setStreet(null)
            ->setNumber(null)
            ->setDistrict(null)
            ->setCity(null)
            ->setState(null)
            ->setZipcode(null)
            ->setComplement(null)
            ->setCountry(null);

        $this->assertEmpty($address->getComplement());
        $this->assertEmpty($address->getStreet());
        $this->assertEmpty($address->getNumber());
        $this->assertEmpty($address->getDistrict());
        $this->assertEmpty($address->getCity());
        $this->assertEmpty($address->getState());
        $this->assertEmpty($address->getZipcode());
    }

    public function testShouldAcceptAddressNumberPropertyWithLetters()
    {
        $address = new \Ipag\Sdk\Model\Address();

        $address->setNumber('BR 163');

        $this->assertEquals('BR 163', $address->getNumber());
    }

    public function testShouldValidateCountryCodeLength()
    {
        $address = new \Ipag\Sdk\Model\Address();

        // Valid 2-character country code
        $address->setCountry('BR');
        $this->assertEquals('BR', $address->getCountry());

        // Invalid country code (more than 2 characters) should raise exception
        $this->expectException(\Ipag\Sdk\Model\Schema\Exception\MutatorAttributeException::class);
        $address->setCountry('BRAZIL');
    }

    public function testShouldValidateCountryCodeLengthOneCharacter()
    {
        $address = new \Ipag\Sdk\Model\Address();

        // Invalid country code (1 character) should raise exception
        $this->expectException(\Ipag\Sdk\Model\Schema\Exception\MutatorAttributeException::class);
        $address->setCountry('B');
    }
}
