<?php
declare(strict_types=1);

namespace RestApi\Sms\MessageBuilder;

use MessageBird\Objects\Message;
use RestApi\MessageRequest;

/**
 * Builder for short messages
 *
 * Class ShortMessageBuilder
 * @package RestApi\Sms\MessageBuilder
 */
final class ShortMessageBuilder extends MessageBuilder
{

    private const MAX_SINGLE_MSG_LENGTH = 160;

    /**
     * @param MessageRequest $messageRequest
     * @return Message[]
     *
     * @throws \LogicException in case builder cannot process existing request and there is no next builder
     */
    public function build(MessageRequest $messageRequest): array
    {
        if (\strlen($messageRequest->getBody()) > self::MAX_SINGLE_MSG_LENGTH) {
            if (!$this->next) {
                throw new \LogicException('Impossible to build message by given request');
            }
            return $this->next->build($messageRequest);
        }

        $msg = new Message();
        $msg->originator = $messageRequest->getOriginator();
        $msg->recipients = [ $messageRequest->getRecipient() ];
        $msg->body = $messageRequest->getBody();

        return [ $msg ];
    }

}
