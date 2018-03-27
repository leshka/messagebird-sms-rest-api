<?php
declare(strict_types=1);

namespace RestApi\Logger\Handler;

/**
 * Interface HandlerInterface
 * @package RestApi\Logger\Handler
 */
interface HandlerInterface
{

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     * @return mixed
     */
    public function write(string $level, string $message, array $context = array());

}
