<?php defined('SYSPATH') or die('No direct script access.');
/**
 *
 *
 * @package    Kohanut
 * @license    http://kohanut.com/license
 */
class Kohanut_Plugin {
	protected static $_registered_plugins = array();
	protected static $_installed_plugins = array();
	protected static $_known_plugins = array();

	public static function register($name, $version = NULL)
	{
		if (empty(Kohanut_Plugin::$_known_plugins))
		{
			try
			{
				$plugins = Sprig::factory('kohanut_plugin')->load(NULL, FALSE);

				foreach ($plugins as $plugin)
				{
					if ($plugin->installed)
					{
						Kohanut_Plugin::$_installed_plugins[$plugin->name] = $plugin->name;
					}

					Kohanut_Plugin::$_known_plugins[$plugin->name] = $plugin->name;
				}
			}
			catch (Database_Exception $e)
			{
				return;
			}
		}

		if ( ! isset(Kohanut_Plugin::$_known_plugins[$name]))
		{
			$plugin = Sprig::factory('kohanut_plugin');
			$plugin->name = $name;
			$plugin->create();
		}
		
		Kohanut_Plugin::$_registered_plugins[$name] = array('name'=> $name, 'version' => $version);

		Event::run('kohanut_plugin_registered', Kohanut_Plugin::$_registered_plugins[$name]);

		if (isset(Kohanut_Plugin::$_installed_plugins[$name]))
		{
			$class = "Kohanut_Plugin_".$name;
			$class::init();
		}
	}

	// Is this needed?
//	public static function unregister_plugin($name)
//	{
//		if ( ! isset(Kohanut_Plugin::$_registered_plugins[$name]))
//			return FALSE;
//
//		Event::run('kohanut_plugin_unregistered', Kohanut_Plugin::$_registered_plugins[$name]);
//
//		unset(Kohanut_Plugin::$_registered_plugins[$name]);
//
//		return TRUE;
//	}

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
		$class = "Kohanut_Plugin_".$name;

		if ( ! $class::install())
		{
			unset(Kohanut_Plugin::$_registered_plugins[$name]);
			// TODO: Alert the user somehow
			return FALSE;
		}

		$plugin = Sprig::factory('kohanut_plugin');
		$plugin->name = $name;
		$plugin->load();

		if ($plugin->loaded())
		{
			$plugin->installed = 1;
			$plugin->update();
		}

		Event::run('kohanut_plugin_installed', Kohanut_Plugin::$_registered_plugins[$name]);

		return TRUE;
	}

	public static function uninstall($name)
	{
		if ( ! isset(Kohanut_Plugin::$_installed_plugins[$name]))
		{
			// TODO: Alert the user somehow
			return FALSE;
		}

		$result = FALSE;

		// Give the plugin a chance to get itself ready to be disabled.
		$class = "Kohanut_Plugin_".$name;

		if ( ! $class::uninstall())
		{
			// TODO: Alert the user somehow
			return FALSE;
		}

		$plugin = Sprig::factory('kohanut_plugin');
		$plugin->name = $name;
		$plugin->load();

		if ($plugin->loaded())
		{
			$plugin->installed = 0;
			$plugin->update();
		}

		Event::run('kohanut_plugin_uninstalled', Kohanut_Plugin::$_registered_plugins[$name]);

		return TRUE;
	}
}