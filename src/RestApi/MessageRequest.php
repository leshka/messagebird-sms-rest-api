<?php
declare(strict_types=1);

namespace RestApi;

use InvalidArgumentException;

/**
 * Value object for user message requests
 *
 * Class MessageRequest
 * @package RestApi
 */
final class MessageRequest
{

    /**
     * @var string
     */
    private $originator;

    /**
     * @var string
     */
    private $recipient;

    /**
     * @var string
     */
    private $body;

    /**
     * MessageRequest constructor.
     * @param string $originator
     * @param string $recipient
     * @param string $body
     *
     * @throws InvalidArgumentException for invalid entities
     */
    public function __construct(string $originator, string $recipient, string $body)
    {
        $this->originator = $originator;
        $this->recipient = $this->filterPhoneNumber($recipient);
        $this->body = $body;

        $this->validate();
    }

    /**
     * @param $phoneNumber
     * @return mixed
     */
    private function filterPhoneNumber(string $phoneNumber): string
    {
        return str_replace(['-', '+'], '', $phoneNumber);
    }

    /**
     * @throws InvalidArgumentException
     */
    private function validate(): void
    {
        if (empty($this->originator)) {
            throw new InvalidArgumentException('Originator cannot be empty');
        }
        if (empty($this->recipient)) {
            throw new InvalidArgumentException('Recipient cannot be empty');
        }
        if (!is_numeric($this->recipient)) {
            throw new InvalidArgumentException(
                sprintf('Invalid recipient value %s. Phone number can contain only digits', $this->recipient)
            );
        }
        if (empty($this->body)) {
            throw new InvalidArgumentException('Sms body cannot be empty');
        }
    }

    /**
     * @return string
     */
    public function getOriginator(): string
    {
        return $this->originator;
    }

    /**
     * @return string
     */
    public function getRecipient(): string
    {
        return $this->recipient;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

}
