<?php
declare(strict_types=1);

namespace RestApi\Sms\Client;

use MessageBird\Objects\Message;

/**
 * Interface ClientInterface
 * @package RestApi\Sms\Client
 */
interface ClientInterface
{

    /**
     * @param Message $msg
     * @return mixed
     */
    public function send(Message $msg);

}
