<?php
declare(strict_types=1);

namespace RestApi;

use InvalidArgumentException;

/**
 * File based set to store message requests.
 *
 * Class MessageRequestSet
 * @package RestApi
 */
class MessageRequestSet
{

    private const UNIQID_PREFIX = 'mbs';

    /**
     * @var string
     */
    private $storagePath;

    /**
     * MessageRequestSet constructor.
     * @param string $storagePath
     *
     * @throws InvalidArgumentException in case of not existing directory
     */
    public function __construct(string $storagePath)
    {
        if (!is_dir($storagePath)) {
            throw new InvalidArgumentException('Storage path should be a valid directory');
        }
        $this->storagePath = $storagePath;
    }

    /**
     * @param MessageRequest $message
     *
     * @throws \RuntimeException if impossible to write a message
     */
    public function add(MessageRequest $message): void
    {
        $path = $this->storagePath . '/' . uniqid(self::UNIQID_PREFIX, true) . '.msg';
        if (file_put_contents($path, serialize($message), LOCK_EX) === false) {
            throw new \RuntimeException('Unable to add message for post processing');
        }
    }

    /**
     * @return null|MessageRequest
     */
    public function pop(): ?MessageRequest
    {
        $storedMessages = glob($this->storagePath . '/*.msg');
        foreach ($storedMessages as $messagePath) {
            $message = unserialize(file_get_contents($messagePath), [ 'allowed_classes' => [ MessageRequest::class ] ]);
            unlink($messagePath);
            if ($message !== false) {
                return $message;
            }
        }

        return null;
    }

}
