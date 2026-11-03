<?php

/**
 * OneFlowFinish class.
 * 
 * @extends OneFlowBase
 */
class OneFlowFinish extends OneFlowBase {

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
	  
		$this->__addProperty("side1","none");
		$this->__addProperty("side2","none");

	}	

}

?>