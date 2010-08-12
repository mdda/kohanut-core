<?php defined('SYSPATH') or die('No direct script access.');
/**
 *
 *
 * @package    Kohanut
 * @license    http://kohanut.com/license
 */
class Kohanut_Plugin {
	protected static $_registered_plugins = array();

	public static function register($name, $version = NULL)
	{
		Kohanut_Plugin::$_registered_plugins[$name] = array('name'=> $name, 'version' => $version);

		Event::run('kohanut_plugin_registered', Kohanut_Plugin::$_registered_plugins[$name]);
	}

	public static function unregister_plugin($name)
	{
		if ( ! isset(Kohanut_Plugin::$_registered_plugins[$name]))
			return FALSE;

		Event::run('kohanut_plugin_unregistered', Kohanut_Plugin::$_registered_plugins[$name]);

		unset(Kohanut_Plugin::$_registered_plugins[$name]);

		return TRUE;
	}

//	public static function check_dependencies($name)
//	{
//		if ( ! isset(Kohanut_Plugin::$_registered_plugins[$name]))
//			return FALSE;
//
//		$dependencies = array();
//
//		$event_data = array('plugin' => Kohanut_Plugin::$_registered_plugins[$name], 'dependencies' => array());
//
//		Event::run('kohanut_plugin_check_dependencies', $event_data);
//
//		return $dependencies;
//	}

	public static function install($name)
	{
		if ( ! isset(Kohanut_Plugin::$_registered_plugins[$name]))
		{
			// TODO: Alert the user somehow
			return FALSE;
		}

		$result = FALSE;

		// Give the plugin a chance to get itself ready to go.
		Event::run('kohanut_plugin_install_'.$name, $result);

		if ( ! $result)
		{
			unset(Kohanut_Plugin::$_registered_plugins[$name]);
			// TODO: Alert the user somehow
			return FALSE;
		}

		// TODO: Update database

		Event::run('kohanut_plugin_installed', Kohanut_Plugin::$_registered_plugins[$name]);

		return TRUE;
	}

	public static function uninstall($name)
	{
		if ( ! isset(Kohanut_Plugin::$_registered_plugins[$name]))
		{
			// TODO: Alert the user somehow
			return FALSE;
		}

		$result = FALSE;

		// Give the plugin a chance to get itself ready to be disabled.
		Event::run('kohanut_plugin_uninstall_'.$name, $result);

		if ( ! $result)
		{
			// TODO: Alert the user somehow
			return FALSE;
		}

		// TODO: Update database

		Event::run('kohanut_plugin_uninstalled', Kohanut_Plugin::$_registered_plugins[$name]);

		return TRUE;
	}
}