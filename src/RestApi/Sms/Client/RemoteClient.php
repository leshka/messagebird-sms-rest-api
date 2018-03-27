<?php
declare(strict_types=1);

namespace RestApi\Sms\Client;

use MessageBird\Client;
use MessageBird\Objects\Message;
use Psr\Log\LoggerInterface;
use RestApi\Exception\HttpException;

/**
 * Proxy class for messagebird sms service
 *
 * If delivery fails, tries to re-send (up to 5 times)
 *
 * Class RemoteClient
 * @package RestApi\Sms\Client
 */
final class RemoteClient implements ClientInterface
{

    /**
     * @var \MessageBird\Client
     */
    private $client;

    /**
     * @var int
     */
    private $maxAttempts;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * RemoteClient constructor.
     * @param Client $client
     * @param LoggerInterface $logger
     * @param int $maxAttempts
     */
    public function __construct(
        Client $client,
        LoggerInterface $logger,
        int $maxAttempts = 5
    )
    {
        $this->client = $client;
        $this->maxAttempts = $maxAttempts;
        $this->logger = $logger;
    }

    /**
     * @param Message $msg
     * @param int $attempt
     *
     * @throws HttpException
     * @throws \MessageBird\Exceptions\RequestException
     * @throws \MessageBird\Exceptions\ServerException
     */
    private function tryDeliver(Message $msg, $attempt = 1): void
    {
        $this->logger->info(
            'Sending message {originator} to {recipient} (attempt: {attempt})',
            [
                'originator' => $msg->originator,
                'recipient' => implode(', ', $msg->recipients),
                'attempt' => $attempt
            ]
        );
        try {
            $this->client->messages->create($msg);
        } catch (\MessageBird\Exceptions\HttpException $ex) {
            if ($attempt < $this->maxAttempts) {
                $this->tryDeliver($msg, $attempt + 1);
            }
            throw new HttpException($ex->getMessage());
        }
    }

    /**
     * @param Message $msg
     *
     * @throws HttpException in case of http problems with sending request
     * @throws \MessageBird\Exceptions\RequestException in case of problems with request
     * @throws \MessageBird\Exceptions\ServerException in case server returns invalid response
     */
    public function send(Message $msg)
    {
        $this->tryDeliver($msg);
    }

}
