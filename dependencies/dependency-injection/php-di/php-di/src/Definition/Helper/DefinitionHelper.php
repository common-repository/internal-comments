<?php

declare (strict_types=1);
namespace DWS_IC_Deps\DI\Definition\Helper;

use DWS_IC_Deps\DI\Definition\Definition;
/**
 * Helps defining container entries.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface DefinitionHelper
{
    /**
     * @param string $entryName Container entry name
     */
    public function getDefinition(string $entryName) : Definition;
}
