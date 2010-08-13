<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohanut Snippet Element. Similar to content, but not unique, and therefore reusable.
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Kohanut_Element_Spacer extends Kohanut_Element
{
	protected $_unique = FALSE;
	
	// Sprig Init
	protected $_table = 'kohanut_element_spacer';
	
	protected function _init()
	{
		$this->_fields += array(
			'id' => new Sprig_Field_Auto,
			'height' => new Sprig_Field_Integer,
		);
	}

	protected function _render()
	{
		$out = '<div class="spacer" style="height: '.$this->height.'px;"><!-- IE Hack --></div>';
		
		return $out;
	}
	
	public function title()
	{
		return "Spacer: " . $this->height . "px";
	}
}