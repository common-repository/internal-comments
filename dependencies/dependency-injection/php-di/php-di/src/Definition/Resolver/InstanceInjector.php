<?php

declare (strict_types=1);
namespace DWS_IC_Deps\DI\Definition\Resolver;

use DWS_IC_Deps\DI\Definition\Definition;
use DWS_IC_Deps\DI\Definition\InstanceDefinition;
use DWS_IC_Deps\DI\DependencyException;
use DWS_IC_Deps\Psr\Container\NotFoundExceptionInterface;
/**
 * Injects dependencies on an existing instance.
 *
 * @since 5.0
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class InstanceInjector extends ObjectCreator
{
    /**
     * Injects dependencies on an existing instance.
     *
     * @param InstanceDefinition $definition
     */
    public function resolve(Definition $definition, array $parameters = [])
    {
        try {
            $this->injectMethodsAndProperties($definition->getInstance(), $definition->getObjectDefinition());
        } catch (NotFoundExceptionInterface $e) {
            $message = \sprintf('Error while injecting dependencies into %s: %s', \get_class($definition->getInstance()), $e->getMessage());
            throw new DependencyException($message, 0, $e);
        }
        return $definition;
    }
    public function isResolvable(Definition $definition, array $parameters = []) : bool
    {
        return \true;
    }
}
