<?php
declare(strict_types=1);

namespace RestApi\Sms;

use RestApi\MessageRequest;
use RestApi\Sms\Client\LimitedThroughputClient;
use RestApi\Sms\MessageBuilder\MessageBuilderChain;

/**
 * Sends message requests to remote client.
 *
 * Class SendMessageCommand
 * @package RestApi\Sms
 */
final class SendMessageCommand
{

    /**
     * @var LimitedThroughputClient
     */
    private $client;

    /**
     * @var MessageBuilderChain
     */
    private $messageBuilder;

    /**
     * SendMessageCommand constructor.
     * @param LimitedThroughputClient $client
     * @param MessageBuilderChain $messageBuilder
     */
    public function __construct(LimitedThroughputClient $client, MessageBuilderChain $messageBuilder)
    {
        $this->client = $client;
        $this->messageBuilder = $messageBuilder;
    }

    /**
     * @param MessageRequest $messageRequest
     */
    public function execute(MessageRequest $messageRequest): void
    {
        $messages = $this->messageBuilder->build($messageRequest);
        foreach ($messages as $message) {
            $this->client->send($message);
        }
    }

}
