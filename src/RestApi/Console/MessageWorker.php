<?php
declare(strict_types=1);

namespace RestApi\Console;

use Psr\Log\LoggerInterface;
use RestApi\MessageRequest;
use RestApi\MessageRequestSet;
use RestApi\Sms\SendMessageCommand;

/**
 * Console worker class to process data from MessageRequestSet.
 * Waits for new messages and tries to execute them using SendMessageCommand command.
 *
 * Class MessageWorker
 * @package RestApi\Console
 */
final class MessageWorker
{

    /**
     * @var MessageRequestSet
     */
    private $messageSet;

    /**
     * @var SendMessageCommand
     */
    private $sendCommand;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * MessageWorker constructor.
     * @param MessageRequestSet $messageSet
     * @param SendMessageCommand $sendCommand
     * @param LoggerInterface $logger
     */
    public function __construct(
        MessageRequestSet $messageSet,
        SendMessageCommand $sendCommand,
        LoggerInterface $logger
    )
    {
        $this->messageSet = $messageSet;
        $this->sendCommand = $sendCommand;
        $this->logger = $logger;
    }

    /**
     * @param MessageRequest $message
     */
    private function processMessage(MessageRequest $message): void
    {
        try {
            $this->sendCommand->execute($message);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }

    public function __invoke()
    {
        while (true) {
            $message = $this->messageSet->pop();
            if ($message) {
                $this->processMessage($message);
            } else {
                usleep(5000);
            }
        }
    }

}
