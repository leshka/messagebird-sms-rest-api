<?php
declare(strict_types=1);

namespace RestApi\Exception;

use Psr\Container\ContainerExceptionInterface;

/**
 * Class ContainerException
 * @package RestApi\Exception
 */
class ContainerException extends \Exception implements ContainerExceptionInterface
{

}
