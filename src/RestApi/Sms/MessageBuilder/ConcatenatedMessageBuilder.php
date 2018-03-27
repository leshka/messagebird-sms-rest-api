<?php
declare(strict_types=1);

namespace RestApi\Sms\MessageBuilder;

use MessageBird\Objects\Message;
use RestApi\MessageRequest;

/**
 * Splits messages and ads user data header
 *
 * Class ConcatenatedMessageBuilder
 * @package RestApi\Sms\MessageBuilder
 */
final class ConcatenatedMessageBuilder extends MessageBuilder
{

    private const VALIDATION_MIN_LENGTH = 160;

    private const MESSAGE_MAX_LENGTH = 153;

    /**
     * @var UserDataHeaderCreator
     */
    private $udhCreator;

    /**
     * ConcatenatedMessageBuilder constructor.
     */
    public function __construct()
    {
        $this->udhCreator = new UserDataHeaderCreator();
    }

    /**
     * @param MessageRequest $messageRequest
     * @return array
     *
     * @throws \LogicException in case builder cannot process existing request and there is no next builder
     * @throws \InvalidArgumentException in case of problems with creating user data header
     */
    public function build(MessageRequest $messageRequest): array
    {
        if (\strlen($messageRequest->getBody()) <= self::VALIDATION_MIN_LENGTH) {
            if (!$this->next) {
                throw new \LogicException('Impossible to build message by given request');
            }
            return $this->next->build($messageRequest);
        }

        $reference = mt_rand(1, 255);
        $body = str_split($messageRequest->getBody(), self::MESSAGE_MAX_LENGTH);
        $messages = [];
        foreach ($body as $id => $bodyPart) {
            $mbMessage = new Message();
            $mbMessage->originator = $messageRequest->getOriginator();
            $mbMessage->recipients = [ $messageRequest->getRecipient() ];
            $mbMessage->setBinarySms(
                $this->udhCreator->create($id + 1, \count($body), $reference),
                bin2hex($bodyPart)
            );
            $messages[] = $mbMessage;
        }

        return $messages;
    }

}
