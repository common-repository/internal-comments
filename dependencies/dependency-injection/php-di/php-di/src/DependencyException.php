<?php

declare (strict_types=1);
namespace DWS_IC_Deps\DI;

use DWS_IC_Deps\Psr\Container\ContainerExceptionInterface;
/**
 * Exception for the Container.
 */
class DependencyException extends \Exception implements ContainerExceptionInterface
{
}
