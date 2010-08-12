<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Sprig migration driver.
 *
 * @package		Migration
 * @author		Oliver Morgan
 * @uses		DBForge
 * @copyright	(c) 2009 Oliver Morgan
 * @license		MIT
 */
class Migration_Kohanut_Element extends Migration_Sprig {
	protected function _model($model)
	{
		// If the model is given as a string
		if (is_string($model))
		{
			// Return the sprig object
			return Kohanut_Element::factory($model);
		}
		// If the model is an object instance of Sprig
		elseif (is_object($model) AND $model instanceof Kohanut_Element)
		{
			// Then return the model as is.
			return $model;
		}
		else
		{
			// Default route indicates failure.
			throw new Kohana_Exception('Invalid kohanut element :model given to kohanut element migration driver.', array(
				':model'	=> (string) $model
			));
		}
	}
} // End Migration_Kohanut_Element