<?php

namespace DeepWebSolutions\Plugins\InternalComments\Settings;

use  DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Booleans ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Settings\Functionalities\AbstractOptionsPageFunctionality ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Settings\Functionalities\AbstractValidatedOptionsGroupFunctionality ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Settings\SettingsService ;
use  DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Validation\ValidationTypesEnum ;
\defined( 'ABSPATH' ) || exit;
/**
 * Registers and outputs the plugin's General settings.
 *
 * @since   1.1.0
 * @version 1.2.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 */
class GeneralSettings extends AbstractValidatedOptionsGroupFunctionality
{
    // region INHERITED METHODS
    /**
     * {@inheritDoc}
     *
     * @since   1.1.0
     * @version 1.1.0
     */
    public function get_option_value( string $field_id )
    {
        return $this->get_option_value_trait( $field_id, $this->get_group_id(), array(
            'default' => null,
        ) );
    }
    
    /**
     * {@inheritDoc}
     *
     * @since   1.1.0
     * @version 1.1.0
     */
    public function update_option_value( string $field_id, $value )
    {
        return $this->update_option_value_trait( $field_id, $value, $this->get_group_id() );
    }
    
    /**
     * {@inheritDoc}
     *
     * @since   1.1.0
     * @version 1.1.0
     */
    public function delete_option_value( string $field_id )
    {
        return $this->get_settings_service()->delete_option_value( $field_id, $this->get_group_id() );
    }
    
    /**
     * {@inheritDoc}
     *
     * @since   1.1.0
     * @version 1.1.0
     */
    public function get_group_title() : string
    {
        return \_x( 'General', 'settings heading', 'internal-comments' );
    }
    
    /**
     * {@inheritDoc}
     *
     * @since   1.1.0
     * @version 1.1.0
     */
    protected function register_options_group( SettingsService $settings_service, AbstractOptionsPageFunctionality $options_page )
    {
        $settings_service->register_options_group(
            $this->get_group_id(),
            '',
            array( $this, 'get_group_fields' ),
            $this->get_group_id()
        );
    }
    
    /**
     * {@inheritDoc}
     *
     * @since   1.1.0
     * @version 1.2.0
     */
    public function get_group_fields_helper() : array
    {
        $fields = array( array(
            'id'       => 'post-types',
            'title'    => function () {
            return \__( 'Post Types that support internal comments', 'internal-comments' );
        },
            'callback' => function () {
            foreach ( $this->get_supported_options( 'post-types' ) as $name => $label ) {
                dws_ic_include_view( 'settings/html-settings-checkbox.php', array(
                    'id'      => "{$this->get_group_id()}_post-types_{$name}",
                    'name'    => "{$this->get_group_id()}[post-types][]",
                    'value'   => $name,
                    'checked' => \checked( true, \in_array( $name, $this->get_validated_option_value( 'post-types' ), true ), false ),
                    'label'   => $label,
                ) );
            }
        },
        ) );
        return $fields;
    }
    
    /**
     * {@inheritDoc}
     *
     * @since   1.1.0
     * @version 1.1.0
     */
    protected function validate_option_value_helper( $value, string $field_id )
    {
        switch ( $field_id ) {
            case 'post-types':
                $value = $this->validate_allowed_value(
                    $value,
                    $field_id,
                    $field_id,
                    ValidationTypesEnum::ARRAY
                );
                break;
            default:
        }
        return $value;
    }

}