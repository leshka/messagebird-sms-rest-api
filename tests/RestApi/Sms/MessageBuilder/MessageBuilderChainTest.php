<?php
declare(strict_types=1);

namespace RestApi\Tests\Sms\MessageBuilder;

use Faker\Factory;
use InvalidArgumentException;
use MessageBird\Objects\Message;
use Mockery;
use PHPUnit\Framework\TestCase;
use RestApi\MessageRequest;
use RestApi\Sms\MessageBuilder\MessageBuilder;
use RestApi\Sms\MessageBuilder\MessageBuilderChain;

class MessageBuilderChainTest extends TestCase
{

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidatesInputClass(): void
    {
        new MessageBuilderChain([new \DateTime()]);
    }

    public function testCallsChain(): void
    {
        $faker = Factory::create();

        $mock = Mockery::mock(MessageBuilder::class);
        $mock->shouldReceive('build')
            ->andReturn([ new Message() ])
            ->times(1);

        $chain = new MessageBuilderChain([ $mock ]);
        $result = $chain->build(new MessageRequest(
            $faker->name,
            $faker->e164PhoneNumber,
            $faker->text
        ));

        $this->assertNotEmpty($result);
    }

}
