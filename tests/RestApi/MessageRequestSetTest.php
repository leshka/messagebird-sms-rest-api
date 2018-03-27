<?php
declare(strict_types=1);

namespace RestApi\Tests;

use PHPUnit\Framework\TestCase;
use RestApi\MessageRequest;
use RestApi\MessageRequestSet;

class MessageRequestSetTest extends TestCase
{

    public function testBasicOperations(): void
    {
        $set = new MessageRequestSet(sys_get_temp_dir());
        $this->assertNull($set->pop());

        $message = new MessageRequest(
            'originator', '841683741722', 'body'
        );
        $set->add($message);

        $newMessage = $set->pop();
        $this->assertInstanceOf(MessageRequest::class, $newMessage);
        $this->assertEquals($message->getOriginator(), $newMessage->getOriginator());
        $this->assertEquals($message->getRecipient(), $newMessage->getRecipient());
        $this->assertEquals($message->getBody(), $newMessage->getBody());
    }

}
