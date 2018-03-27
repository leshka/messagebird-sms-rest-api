<?php
declare(strict_types=1);

namespace RestApi\Sms\MessageBuilder;

use MessageBird\Objects\Message;
use RestApi\MessageRequest;

/**
 * Class MessageBuilder
 * @package RestApi\Sms\MessageBuilder
 */
abstract class MessageBuilder
{

    /**
     * @var MessageBuilder
     */
    protected $next;

    /**
     * @param MessageBuilder $next
     */
    public function setNext(MessageBuilder $next): void
    {
        $this->next = $next;
    }

    /**
     * @param MessageRequest $messageRequest
     * @return Message[]
     */
    abstract public function build(MessageRequest $messageRequest): array;

}
