<?php
declare(strict_types=1);

namespace RestApi\Tests\Sms\MessageBuilder;

use Faker\Factory;
use MessageBird\Objects\Message;
use PHPUnit\Framework\TestCase;
use RestApi\MessageRequest;
use RestApi\Sms\MessageBuilder\ConcatenatedMessageBuilder;

class ConcatenatedMessageBuilderTest extends TestCase
{

    public function testCreatesFewMessagesFromLargeOne(): void
    {
        $faker = Factory::create();

        $request = new MessageRequest(
            $faker->name,
            $faker->e164PhoneNumber,
            $faker->text(1000)
        );

        $creator = new ConcatenatedMessageBuilder();
        $result = $creator->build($request);

        foreach ($result as $message) {
            $this->assertInstanceOf(Message::class, $message);
            $this->assertEquals($request->getOriginator(), $message->originator);
            $this->assertEquals([$request->getRecipient()], $message->recipients);
        }
    }

    /**
     * @expectedException \LogicException
     */
    public function testIgnoresSmallMessages(): void
    {
        $faker = Factory::create();

        $request = new MessageRequest(
            $faker->name,
            $faker->e164PhoneNumber,
            $faker->text(5)
        );

        $creator = new ConcatenatedMessageBuilder();
        $creator->build($request);
    }

}
