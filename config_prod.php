<?php

use DeepWebSolutions\Plugins\InternalComments\Ajax;
use DeepWebSolutions\Plugins\InternalComments\Comments;
use DeepWebSolutions\Plugins\InternalComments\Output;
use DeepWebSolutions\Plugins\InternalComments\Permissions;
use DeepWebSolutions\Plugins\InternalComments\Plugin;
use DeepWebSolutions\Plugins\InternalComments\Query;
use DeepWebSolutions\Plugins\InternalComments\Settings;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\Logging\LoggingService;
use DWS_IC_Deps\DeepWebSolutions\Framework\Foundations\PluginInterface;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Validation\Handlers\ContainerValidationHandler;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Validation\ValidationService;
use DWS_IC_Deps\DI\ContainerBuilder;
use function DWS_IC_Deps\DI\factory;
use function DWS_IC_Deps\DI\get;
use function DWS_IC_Deps\DI\autowire;

defined( 'ABSPATH' ) || exit;

return array_merge(
	// Foundations
	array(
		PluginInterface::class => get( Plugin::class ),
	),
	// Settings
	array(
		'settings-validation-handler' => factory(
			function() {
				$config    = require_once __DIR__ . '/src/configs/settings.php';
				$container = ( new ContainerBuilder() )->addDefinitions( $config )->build();

				return new ContainerValidationHandler( 'settings', $container );
			}
		),
		ValidationService::class      => autowire( ValidationService::class )
			->method( 'register_handler', get( 'settings-validation-handler' ) ),
	),
	// Plugin
	array(
		Plugin::class                          => autowire( Plugin::class )
			->constructorParameter( 'plugin_slug', 'internal-comments' )
			->constructorParameter( 'plugin_file_path', dws_ic_path() ),

		Ajax::class                            => autowire( Ajax::class )
			->constructorParameter( 'component_id', 'ajax' )
			->constructorParameter( 'component_name', 'AJAX' ),

		Comments::class                        => autowire( Comments::class )
			->constructorParameter( 'component_id', 'comments' )
			->constructorParameter( 'component_name', 'Comments' ),
		Comments\ListTable::class              => autowire( Comments\ListTable::class )
			->constructorParameter( 'component_id', 'comments-list-table' )
			->constructorParameter( 'component_name', 'Internal Comments List Table' ),
		Comments\ReplyComments::class          => autowire( Comments\ReplyComments::class )
			->constructorParameter( 'component_id', 'reply-comments' )
			->constructorParameter( 'component_name', 'Reply Comments' ),

		Query::class                           => autowire( Query::class )
			->constructorParameter( 'component_id', 'query' )
			->constructorParameter( 'component_name', 'Query' ),
		Query\ScopedHooks::class               => autowire( Query\ScopedHooks::class ),

		Settings::class                        => autowire( Settings::class )
			->constructorParameter( 'component_id', 'settings' )
			->constructorParameter( 'component_name', 'Settings' ),
		Settings\GeneralSettings::class        => autowire( Settings\GeneralSettings::class )
			->constructorParameter( 'component_id', 'general-settings' )
			->constructorParameter( 'component_name', 'General Settings' ),
		Settings\PluginSettings::class         => autowire( Settings\PluginSettings::class )
			->constructorParameter( 'component_id', 'plugin-settings' )
			->constructorParameter( 'component_name', 'Plugin Settings' ),

		Permissions::class                     => autowire( Permissions::class )
			->constructorParameter( 'component_id', 'permissions' )
			->constructorParameter( 'component_name', 'Permissions' ),
		Permissions\CommentPermissions::class  => autowire( Permissions\CommentPermissions::class )
			->constructorParameter( 'component_id', 'comment-permissions' )
			->constructorParameter( 'component_name', 'Comments Permissions' ),
		Permissions\OutputPermissions::class   => autowire( Permissions\OutputPermissions::class )
			->constructorParameter( 'component_id', 'output-permissions' )
			->constructorParameter( 'component_name', 'Output Permissions' ),
		Permissions\SettingsPermissions::class => autowire( Permissions\SettingsPermissions::class )
			->constructorParameter( 'component_id', 'settings-permissions' )
			->constructorParameter( 'component_name', 'Settings Permissions' ),

		Output::class                          => autowire( Output::class )
			->constructorParameter( 'component_id', 'output' )
			->constructorParameter( 'component_name', 'Output' ),
		Output\MetaBox::class                  => autowire( Output\MetaBox::class )
			->constructorParameter( 'component_id', 'metabox-output' )
			->constructorParameter( 'component_name', 'MetaBox Output' ),
		Output\WPTable::class                  => autowire( Output\WPTable::class )
			->constructorParameter( 'component_id', 'wp-table-output' )
			->constructorParameter( 'component_name', 'WP Table Output' ),
	),
	// Plugin aliases
	array(
		'ajax'                 => get( Ajax::class ),

		'comments'             => get( Comments::class ),
		'comments-list-table'  => get( Comments\ListTable::class ),
		'reply-comments'       => get( Comments\ReplyComments::class ),

		'query'                => get( Query::class ),
		'query-scoped-hooks'   => get( Query\ScopedHooks::class ),

		'settings'             => get( Settings::class ),
		'general-settings'     => get( Settings\GeneralSettings::class ),
		'plugin-settings'      => get( Settings\PluginSettings::class ),

		'permissions'          => get( Permissions::class ),
		'comment-permissions'  => get( Permissions\CommentPermissions::class ),
		'output-permissions'   => get( Permissions\OutputPermissions::class ),
		'settings-permissions' => get( Permissions\SettingsPermissions::class ),

		'output'               => get( Output::class ),
		'metabox-output'       => get( Output\MetaBox::class ),
		'wp-table-output'      => get( Output\WPTable::class ),
	)
);
