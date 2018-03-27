<?php
declare(strict_types=1);

namespace RestApi\Logger\Handler;

/**
 * Null handler for Logger class.
 * Can be used for tests.
 *
 * Class NullHandler
 * @package RestApi\Logger\Handler
 */
class NullHandler implements HandlerInterface
{

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     * @return mixed|void
     */
    public function write(string $level, string $message, array $context = array())
    {
    }

}
