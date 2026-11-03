<?php

/**
 * OneFlowPoint class.
 * 
 * @extends OneFlowBase
 */
class OneFlowPoint extends OneFlowBase	{

	public $id;
	public $name;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @param mixed $values (default: null)
	 */
	public function __construct($values=null)	{
		//keep the existing fields absent until init or a setter assigns them
		unset($this->id);
		unset($this->name);

		parent::__construct($values);
	}

	/**
	 * init function.
	 * 
	 * @access public
	 * @return void
	 */
	public function init()      {  

		$this->__addProperty("id");
		$this->__addProperty("name");
		
    }
	
}

?>