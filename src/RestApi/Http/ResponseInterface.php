<?php
declare(strict_types=1);

namespace RestApi\Http;

/**
 * Interface ResponseInterface
 * @package RestApi\Http
 */
interface ResponseInterface
{

    public const PROTOCOL_VERSION = '1.0';

    public const HTTP_OK = 200;

    public const HTTP_NOT_FOUND = 404;

    public const HTTP_UNPROCESSABLE_ENTITY = 422;

    public const HTTP_INTERNAL_SERVER_ERROR = 500;

    public function send(): void;

}
