<?php

namespace DWS_IC_Deps\DI;

use DWS_IC_Deps\DI\Definition\ArrayDefinitionExtension;
use DWS_IC_Deps\DI\Definition\EnvironmentVariableDefinition;
use DWS_IC_Deps\DI\Definition\Helper\AutowireDefinitionHelper;
use DWS_IC_Deps\DI\Definition\Helper\CreateDefinitionHelper;
use DWS_IC_Deps\DI\Definition\Helper\FactoryDefinitionHelper;
use DWS_IC_Deps\DI\Definition\Reference;
use DWS_IC_Deps\DI\Definition\StringDefinition;
use DWS_IC_Deps\DI\Definition\ValueDefinition;
if (!\function_exists('DWS_IC_Deps\\DI\\value')) {
    /**
     * Helper for defining a value.
     *
     * @param mixed $value
     * @return \DWS_IC_Deps\DI\Definition\ValueDefinition
     */
    function value($value)
    {
        return new ValueDefinition($value);
    }
}
if (!\function_exists('DWS_IC_Deps\\DI\\create')) {
    /**
    * Helper for defining an object.
    *
     * @param string $className Class name of the object.
                             If null, the name of the entry (in the container) will be used as class name.
     * @return \DWS_IC_Deps\DI\Definition\Helper\CreateDefinitionHelper
    */
    function create($className = null)
    {
        $className = (string) $className;
        return new CreateDefinitionHelper($className);
    }
}
if (!\function_exists('DWS_IC_Deps\\DI\\autowire')) {
    /**
    * Helper for autowiring an object.
    *
     * @param string $className Class name of the object.
                             If null, the name of the entry (in the container) will be used as class name.
     * @return \DWS_IC_Deps\DI\Definition\Helper\AutowireDefinitionHelper
    */
    function autowire($className = null)
    {
        $className = (string) $className;
        return new AutowireDefinitionHelper($className);
    }
}
if (!\function_exists('DWS_IC_Deps\\DI\\factory')) {
    /**
     * Helper for defining a container entry using a factory function/callable.
     *
     * @param callable $factory The factory is a callable that takes the container as parameter
     *                          and returns the value to register in the container.
     * @return \DWS_IC_Deps\DI\Definition\Helper\FactoryDefinitionHelper
     */
    function factory($factory)
    {
        return new FactoryDefinitionHelper($factory);
    }
}
if (!\function_exists('DWS_IC_Deps\\DI\\decorate')) {
    /**
     * Decorate the previous definition using a callable.
     *
     * Example:
     *
     *     'foo' => decorate(function ($foo, $container) {
     *         return new CachedFoo($foo, $container->get('cache'));
     *     })
     *
     * @param callable $callable The callable takes the decorated object as first parameter and
     *                           the container as second.
     * @return \DWS_IC_Deps\DI\Definition\Helper\FactoryDefinitionHelper
     */
    function decorate($callable)
    {
        return new FactoryDefinitionHelper($callable, \true);
    }
}
if (!\function_exists('DWS_IC_Deps\\DI\\get')) {
    /**
     * Helper for referencing another container entry in an object definition.
     * @param string $entryName
     * @return \DWS_IC_Deps\DI\Definition\Reference
     */
    function get($entryName)
    {
        $entryName = (string) $entryName;
        return new Reference($entryName);
    }
}
if (!\function_exists('DWS_IC_Deps\\DI\\env')) {
    /**
     * Helper for referencing environment variables.
     *
     * @param string $variableName The name of the environment variable.
     * @param mixed $defaultValue The default value to be used if the environment variable is not defined.
     * @return \DWS_IC_Deps\DI\Definition\EnvironmentVariableDefinition
     */
    function env($variableName, $defaultValue = null)
    {
        $variableName = (string) $variableName;
        // Only mark as optional if the default value was *explicitly* provided.
        $isOptional = 2 === \func_num_args();
        return new EnvironmentVariableDefinition($variableName, $isOptional, $defaultValue);
    }
}
if (!\function_exists('DWS_IC_Deps\\DI\\add')) {
    /**
     * Helper for extending another definition.
     *
     * Example:
     *
     *     'log.backends' => DI\add(DI\get('My\Custom\LogBackend'))
     *
     * or:
     *
     *     'log.backends' => DI\add([
     *         DI\get('My\Custom\LogBackend')
     *     ])
     *
     * @param mixed|array $values A value or an array of values to add to the array.
     *
     * @since 5.0
     * @return \DWS_IC_Deps\DI\Definition\ArrayDefinitionExtension
     */
    function add($values)
    {
        if (!\is_array($values)) {
            $values = [$values];
        }
        return new ArrayDefinitionExtension($values);
    }
}
if (!\function_exists('DWS_IC_Deps\\DI\\string')) {
    /**
     * Helper for concatenating strings.
     *
     * Example:
     *
     *     'log.filename' => DI\string('{app.path}/app.log')
     *
     * @param string $expression A string expression. Use the `{}` placeholders to reference other container entries.
     *
     * @since 5.0
     * @return \DWS_IC_Deps\DI\Definition\StringDefinition
     */
    function string($expression)
    {
        $expression = (string) $expression;
        return new StringDefinition($expression);
    }
}
