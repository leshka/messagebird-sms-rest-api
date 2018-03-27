<?php
declare(strict_types=1);

namespace RestApi\Tests\Sms\Client;

use MessageBird\Client;
use MessageBird\Exceptions\HttpException;
use MessageBird\Objects\Message;
use MessageBird\Resources\Messages;
use Mockery;
use PHPUnit\Framework\TestCase;
use RestApi\Logger\Handler\NullHandler;
use RestApi\Logger\Logger;
use RestApi\Sms\Client\RemoteClient;

class RemoteClientTest extends TestCase
{

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @expectedException \RestApi\Exception\HttpException
     */
    public function testTriesToAttempt(): void
    {
        $clientMock = Mockery::mock(Client::class);
        $clientMock->messages = Mockery::mock(Messages::class);
        $clientMock->messages->shouldReceive('create')
            ->andThrow(HttpException::class)
            ->times(5);

        $msgMock = Mockery::mock(Message::class);

        $remoteClient = new RemoteClient(
            $clientMock,
            new Logger([new NullHandler()]),
            5
        );
        $remoteClient->send($msgMock);
    }

}
