<?php
declare(strict_types=1);

namespace RestApi\Sms\Client;

use MessageBird\Objects\Message;

/**
 * Proxy class for remote object
 * Postpones api calls when called too often (for given throughput).
 *
 * Class LimitedThroughputClient
 * @package RestApi\Sms\Client
 */
final class LimitedThroughputClient implements ClientInterface
{

    private const MICROSECONDS_IN_SEC = 1000000;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var int
     */
    private $requestThroughputSec;

    /**
     * @var int|null
     */
    private $lastRequestTime;

    /**
     * LimitedThroughputClient constructor.
     * @param ClientInterface $client
     * @param int $requestThroughputSec
     */
    public function __construct(ClientInterface $client, $requestThroughputSec = 1)
    {
        $this->client = $client;
        $this->requestThroughputSec = $requestThroughputSec;
    }

    /**
     * @param Message $msg
     * @return mixed|void
     */
    public function send(Message $msg)
    {
        if (null !== $this->lastRequestTime) {
            $timeDiff = microtime(true) - $this->lastRequestTime;
            if ($timeDiff < $this->requestThroughputSec) {
                usleep((int)($this->requestThroughputSec - $timeDiff) * self::MICROSECONDS_IN_SEC);
            }
        }
        $this->lastRequestTime = microtime(true);
        $this->client->send($msg);
    }
}
