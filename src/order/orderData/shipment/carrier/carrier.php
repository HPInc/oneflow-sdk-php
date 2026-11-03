<?php

/**
 * OneFlowCarrier class.
 * 
 * @extends OneFlowBase
 */
class OneFlowCarrier extends OneFlowBase {

	public $code;
	public $service;
	public $alias;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @param mixed $values (default: null)
	 */
	public function __construct($values=null)	{
		//keep the existing fields absent until init or a setter assigns them
		unset($this->code);
		unset($this->service);
		unset($this->alias);

		parent::__construct($values);
	}

	/**
	 * init function.
	 * 
	 * @access public
	 * @return void
	 */
	public function init()      {
	  
		$this->__addProperty("code");
		$this->__addProperty("service");
		$this->__addProperty("alias");
	}	
}

?>