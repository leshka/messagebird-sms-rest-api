<?php
declare(strict_types=1);

namespace RestApi\Tests;

use Faker\Factory;
use PHPUnit\Framework\TestCase;
use RestApi\MessageRequest;

class MessageRequestTest extends TestCase
{

    public function testCanCreateNormalObject(): void
    {
        $request = new MessageRequest('originator', '841683741722', 'test message');
        $this->assertEquals('originator', $request->getOriginator());
        $this->assertEquals('841683741722', $request->getRecipient());
        $this->assertEquals('test message', $request->getBody());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testOriginatorCannotBeEmpty(): void
    {
        $faker = Factory::create();
        new MessageRequest('', $faker->e164PhoneNumber, $faker->text);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRecipientCannotBeEmpty(): void
    {
        $faker = Factory::create();
        new MessageRequest($faker->name, '', $faker->text);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRecipientHasOnlyDigits(): void
    {
        $faker = Factory::create();
        new MessageRequest($faker->name, $faker->name, $faker->text);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testBodyNotEmpty(): void
    {
        $faker = Factory::create();
        new MessageRequest($faker->name, $faker->e164PhoneNumber, '');
    }

}
