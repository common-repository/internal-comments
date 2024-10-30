<?php

namespace DWS_IC_Deps\DeepWebSolutions\Framework\Core;

use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable\InitializationFailureException;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\Initializable\InitializeLocalTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\InitializableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Actions\SetupableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Helpers\HooksHelpersTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy\Actions\AddContainerChildrenTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy\Actions\InitializeChildrenTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy\Actions\MaybeSetupChildrenTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy\ParentTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy\Plugin\AbstractPluginNode;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy\States\ActiveParentTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Hierarchy\States\DisabledParentTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\States\ActiveableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\States\DisableableInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\HooksHelpersAwareInterface;
\defined( 'ABSPATH' ) || exit;
/**
 * Template for encapsulating the piping required for auto-magical lifecycle execution of a plugin node.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Core\Plugin
 */
abstract class AbstractPluginFunctionality extends AbstractPluginNode implements ActiveableInterface, DisableableInterface, HooksHelpersAwareInterface, InitializableInterface, SetupableInterface {

	// region TRAITS
	use AddContainerChildrenTrait;
	use ActiveParentTrait , DisabledParentTrait, ParentTrait {
        // phpcs:ignore WordPress.WhiteSpace.ControlStructureSpacing.NoSpaceAfterOpenParenthesis
		add_child as protected add_child_trait;
	}
	use HooksHelpersTrait;
	use InitializeLocalTrait, InitializeChildrenTrait;
	use MaybeSetupChildrenTrait;
	// endregion
	// region INHERITED METHODS
	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_plugin() : AbstractPluginFunctionalityRoot {
        // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
		/* @noinspection PhpIncompatibleReturnTypeInspection */
		return parent::get_plugin();
	}
	/**
	 * Automagically sets the plugin instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  InitializationFailureException|null
	 */
	protected function initialize_local() : ?InitializationFailureException {
		$this->set_plugin();
		return null;
	}
	/**
	 * Adds a child to the list of children of the current instance. If a string is passed along, the DI container
	 * is used to resolve the entry.
	 *
	 * @param   object|string   $child      Object to add or string to resolve before adding.
	 *
	 * @return  InitializationFailureException|null
	 */
	public function add_child( $child ) : ?InitializationFailureException {
		$child  = \is_string( $child ) ? $this->get_plugin()->get_container_entry( $child ) : $child;
		$result = $this->add_child_trait( $child );
		return $result ? null : new InitializationFailureException( \sprintf( 'Invalid child! Cannot add instance of type %1$s as child to instance of type %2$s.', \is_null( $child ) ? null : \get_class( $child ), static::get_qualified_class_name() ) );
	}
	// endregion
}
