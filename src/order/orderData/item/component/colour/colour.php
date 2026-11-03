<?php

/**
 * OneFlowColour class.
 * 
 * @extends OneFlowBase
 */
class OneFlowColour extends OneFlowBase {

	public $side1;
	public $side2;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @param mixed $values (default: null)
	 */
	public function __construct($values=null)	{
		//keep the existing fields absent until init or a setter assigns them
		unset($this->side1);
		unset($this->side2);

		parent::__construct($values);
	}

	/**
	 * init function.
	 * 
	 * @access public
	 * @return void
	 */
	public function init()      {
	  
		$this->__addProperty("side1","4process");
		$this->__addProperty("side2","4process");
	}	
}

?>