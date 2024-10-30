<?php

namespace DeepWebSolutions\Plugins\InternalComments;

use  DeepWebSolutions\Plugins\InternalComments\Permissions\OutputPermissions ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Core\AbstractPluginFunctionality ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers\AssetsHelpersTrait ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\States\Activeable\ActiveLocalTrait ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\Users ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\Actions\SetupHooksTrait ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksService ;
\defined( 'ABSPATH' ) || exit;
/**
 * Logical node for managing output nodes.
 *
 * @since   1.0.0
 * @version 1.2.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 */
class Output extends AbstractPluginFunctionality
{
    // region TRAITS
    use  ActiveLocalTrait ;
    use  AssetsHelpersTrait ;
    use  SetupHooksTrait ;
    // endregion
    // region INHERITED METHODS
    /**
     * {@inheritDoc}
     *
     * @since   1.0.0
     * @version 1.2.0
     */
    public function is_active_local() : bool
    {
        return Users::has_capabilities( OutputPermissions::SEE_INTERNAL_COMMENTS ) ?? false;
    }
    
    /**
     * {@inheritDoc}
     *
     * @since   1.0.0
     * @version 1.0.0
     */
    protected function get_di_container_children() : array
    {
        $children = array( Output\MetaBox::class, Output\WPTable::class );
        return $children;
    }
    
    /**
     * {@inheritDoc}
     *
     * @since   1.2.0
     * @version 1.2.0
     */
    public function register_hooks( HooksService $hooks_service ) : void
    {
    }

}