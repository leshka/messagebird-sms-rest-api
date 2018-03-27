<?php
declare(strict_types=1);

namespace RestApi\Tests\Sms\MessageBuilder;

use Faker\Factory;
use MessageBird\Objects\Message;
use PHPUnit\Framework\TestCase;
use RestApi\MessageRequest;
use RestApi\Sms\MessageBuilder\ShortMessageBuilder;

class ShortMessageBuilderTest extends TestCase
{

    public function testCreatesNotEmptyArrayWithSameData(): void
    {
        $faker = Factory::create();

        $request = new MessageRequest(
            $faker->name,
            $faker->e164PhoneNumber,
            $faker->text(100)
        );

        $creator = new ShortMessageBuilder();
        $result = $creator->build($request);
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(Message::class, $result[0]);

        $mbMessage = $result[0];
        $this->assertEquals($request->getOriginator(), $mbMessage->originator);
        $this->assertEquals([$request->getRecipient()], $mbMessage->recipients);
        $this->assertEquals($request->getBody(), $mbMessage->body);
    }

    /**
     * @expectedException \LogicException
     */
    public function testDoestAcceptLargeMessages(): void
    {
        $faker = Factory::create();

        $request = new MessageRequest(
            $faker->name,
            $faker->e164PhoneNumber,
            $faker->text(1000)
        );

        $creator = new ShortMessageBuilder();
        $creator->build($request);
    }

}
