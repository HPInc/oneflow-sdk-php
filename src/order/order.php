<?php

require_once "point/point.php";
require_once "orderData/orderData.php";

/**
 * OneFlowOrder class.
 *
 * @extends OneFlowBase
 */
class OneFlowOrder extends OneFlowBase	{

	public $orderData;
	public $destination;
	public $source;
	public $files;
	public $_id;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @param mixed $values (default: null)
	 */
	public function __construct($values=null)	{
		//keep the existing fields absent until init or a setter assigns them
		unset($this->orderData);
		unset($this->destination);
		unset($this->source);
		unset($this->files);
		unset($this->_id);

		parent::__construct($values);
	}

	/**
	 * init function.
	 *
	 * @access public
	 * @return void
	 */
	public function init()      {

		global $OneFlowFilenames;
		$OneFlowFilenames  = Array();

		$this->__addObject("orderData",		"OrderData");
		$this->__addObject("destination",	"Point");
		$this->__addObject("source",		"Point");

		$this->__addArray("files",			"String");

		$this->__addProperty("_id");

    }

	/**
	 * setDestination function.
	 *
	 * @access public
	 * @param mixed $name
	 * @return void
	 */
	public function setDestination($name)	{
		$this->destination->name = $name;
	}

	/**
	 * setOrderData function.
	 *
	 * @access public
	 * @param mixed $orderData
	 * @return void
	 */
	public function setOrderData($orderData)	{
		$this->orderData = $orderData;
	}

	/**
	 * toXML function.
	 *
	 * @access public
	 * @return string
	 */
	public function toXML(){
		$xml  = "<?xml version=\"1.0\" ?".">\n";
		$xml .= "<order>\n";
		$xml .= $this->objectToXML();
		$xml .= "</order>\n";
		return $xml;
	}

	/**
	 * validateOrder function.
	 *
	 * @access public
	 * @return string
	 */
	function validateOrder()	{
		if (count($this->isValid())>0)	{
			$output = "Order NOT Valid\n";
			foreach ($this->getValidationMessages() as $message)	{
				$output .= "$message\n";
			}
		} else	{
			$output = "Valid Order\n";
		};

		return $output;
	}
}

?>