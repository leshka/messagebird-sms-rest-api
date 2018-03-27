<?php
declare(strict_types=1);

namespace RestApi\Tests\Sms\Client;

use MessageBird\Objects\Message;
use Mockery;
use PHPUnit\Framework\TestCase;
use RestApi\Sms\Client\ClientInterface;
use RestApi\Sms\Client\LimitedThroughputClient;

class LimitedThroughputClientTest extends TestCase
{

    public function tearDown()
    {
        Mockery::close();
    }

    public function testCheckCallsRemoteClientAndDoestWaitFirstCall(): void
    {
        $remoteClient = Mockery::mock(ClientInterface::class);
        $remoteClient->shouldReceive('send')
            ->once();

        $msgMock = Mockery::mock(Message::class);

        $timeStart = microtime(true);
        $throughputClient = new LimitedThroughputClient($remoteClient, 1);
        $throughputClient->send($msgMock);
        $timeEnd = microtime(true);
        $this->assertTrue($timeEnd - $timeStart < 1);

    }

}
