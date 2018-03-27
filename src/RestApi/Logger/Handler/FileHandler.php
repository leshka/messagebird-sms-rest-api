<?php
declare(strict_types=1);

namespace RestApi\Logger\Handler;

use DateTime;

/**
 * File handler for Logger class.
 * Outputs data to provided filename destination.
 *
 * Class FileHandler
 * @package RestApi\Logger\Handler
 */
class FileHandler implements HandlerInterface
{
    /**
     * @var string
     */
    protected $filename;

    /**
     * @var resource|null
     */
    protected $stream;

    /**
     * StreamHandler constructor.
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    /**
     * @param string $message
     * @param array $context
     * @return string
     */
    private function interpolate(string $message, array $context = array()): string
    {
        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
            // check that the value can be casted to string
            if (!\is_array($val) && (!\is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     * @return string
     */
    private function format(string $level, string $message, array $context): string
    {
        return sprintf(
            "[%s]\t%s\t%s\n",
            (new DateTime())->format('Y-m-d H:i:s'),
            $level,
            $this->interpolate($message, $context)
        );
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     */
    public function write(string $level, string $message, array $context = array())
    {
        if (null === $this->stream) {
            $this->stream = fopen($this->filename, 'ab');
        }
        flock($this->stream, LOCK_EX);
        fwrite($this->stream, $this->format($level, $message, $context));
        flock($this->stream, LOCK_UN);
    }

    public function close(): void
    {
        if ($this->stream) {
            fclose($this->stream);
        }
    }
}
