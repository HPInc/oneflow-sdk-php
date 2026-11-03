<?php

/**
 * OneFlowStockItem class.
 *
 * @extends OneFlowBase
 */
class OneFlowStockItem extends OneFlowBase {

	public $code;
	public $quantity;
	public $shipmentIndex;
	public $name;
	public $unitPrice;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @param mixed $values (default: null)
	 */
	public function __construct($values=null)	{
		//keep the existing fields absent until init or a setter assigns them
		unset($this->code);
		unset($this->quantity);
		unset($this->shipmentIndex);
		unset($this->name);
		unset($this->unitPrice);

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
        $this->__addProperty("quantity", 1, false);
        $this->__addProperty("shipmentIndex");
        $this->__addProperty("name");
        $this->__addProperty("unitPrice");
    }

    /**
     * setCode function.
     *
     * @access public
     * @param mixed $code
     * @return mixed
     */
    public function setCode($code)    {
        return $this->code = $code;
    }

    /**
     * setQuantity function.
     *
     * @access public
     * @param mixed $quantity
     * @return void
     */
    public function setQuantity($quantity)  {
        $this->quantity = $quantity;
    }


    /**
     * setShipment function.
     *
     * @access public
     * @param mixed $shipment
     * @return void
     */
    public function setShipment($shipment)  {
        $this->shipmentIndex = $shipment->shipmentIndex;
    }

    /**
     * setShipmentIndex function.
     *
     * @access public
     * @param mixed $shipmentIndex
     * @return void
     */
    public function setShipmentIndex($shipmentIndex)  {
        $this->shipmentIndex = $shipmentIndex;
    }

	/**
	 * setName function.
	 *
	 * @access public
	 * @param string $name
	 * @return void
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * setUnitPrice function.
	 *
	 * @access public
	 * @param number $unitPrice
	 * @return void
	 */
	public function setUnitPrice($unitPrice)
	{
		$this->unitPrice = $unitPrice;
	}
}

?>
