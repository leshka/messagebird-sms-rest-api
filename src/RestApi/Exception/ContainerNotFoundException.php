<?php
declare(strict_types=1);

namespace RestApi\Exception;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class ContainerNotFoundException
 * @package RestApi\Exception
 */
class ContainerNotFoundException extends Exception implements NotFoundExceptionInterface
{

}
