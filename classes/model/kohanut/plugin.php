<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohanut Redirect Model
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Model_Kohanut_Plugin extends Sprig {

	protected function _init()
	{
		$this->_fields += array(
			'id' => new Sprig_Field_Auto,
			'name' => new Sprig_Field_Char,
			'installed' => new Sprig_Field_Boolean,
		);
	}
	
	/**
	 * Find a redirect from $url
	 *
	 * @return  boolean   true if found, false if not
	 */
	public function find($url) {
		// Check for a redirect at $url
		$this->url = $url;
		$this->load();
		return $this;
	}
	
	public function go() {
		
		// Make sure this redirect is loaded
		if ( $this->loaded())
		{
			
			if ($this->type == '301' || $this->type == '302')
			{
				// Redirect to the new url
				Kohana::$log->add('INFO', "Kohanut - Redirected '$this->url' to '$this->newurl' ($this->type)."); 
				Request::instance()->redirect($this->newurl,$this->type);
			}
			else
			{
				// This should never happen, log an error and display an error
				Kohana::$log->add('ERROR', "Kohanut - Could not redirect '$this->url' to '$this->newurl'. Unknown redirect type: ($this->type)");
				throw new Kohanut_Exception("Unknown redirect type",array(),404);
			}
			
		}
	}
}