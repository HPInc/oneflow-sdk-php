<?php

require_once "component/component.php";

/**
 * OneFlowItem class.
 *
 * @extends OneFlowBase
 */
class OneFlowItem extends OneFlowBase {

	/**
	 * init function.
	 *
	 * @access public
	 * @return void
	 */
	public function init()      {

		$this->__addArray("components","Component");

		$this->__addProperty("description");
		$this->__addProperty("shipmentIndex", 0, true);
		$this->__addProperty("sourceItemId", "", true);
		$this->__addProperty("sku");
		$this->__addProperty("quantity", 1, true);
		$this->__addProperty("shrinkWrap", false);
		$this->__addProperty("barcode", "", false);
		$this->__addProperty("dispatchAlert");

		$this->__addProperty("colour");
    }

    /**
     * newComponent function.
     *
     * @access public
     * @param string $type
     * @return void
     */
    public function newComponent($code){

    	$count = count($this->components);
    	$component = new OneFlowComponent();
        $component->setCode($code);
	    $this->components[$count] = $component;

	    return $this->components[$count];
    }

    /**
     * addTextComponent function.
     *
     * @access public
     * @return void
     */
    public function addTextComponent()	{

	    return $this->newComponent('text');
    }

    /**
     * addTextComponent function.
     *
     * @access public
     * @return void
     */
    public function setBarcode($barcode)	{

	    return $this->barcode = $barcode;
    }

    /**
     * addCoverComponent function.
     *
     * @access public
     * @return void
     */
    public function addCoverComponent()	{

	    return $this->newComponent('cover');
    }

    /**
     * setDispatchAlert function.
     *
     * @access public
     * @param mixed $dispatchAlert
     * @return void
     */
    public function setDispatchAlert($dispatchAlert)	{
	    $this->dispatchAlert = $dispatchAlert;
    }

    /**
     * setShipment function.
     *
     * @access public
     * @param mixed $shipment
     * @return void
     */
    public function setShipment($shipment)	{

	    $this->shipmentIndex = $shipment->shipmentIndex;
    }

    /**
     * setBinding function.
     *
     * @access public
     * @param mixed $binding
     * @return void
     */
    public function setBinding($binding)	{

		$this->__addList("binding", Array("perfect", "saddle", "wiro", "pur", "threadSawn"));
		$this->binding = $binding;
		return true;
    }

    /**
     * setFolding function.
     *
     * @access public
     * @param mixed $folding
     * @return void
     */
    public function setFolding($folding)	{

		$this->__addList("folding", Array("half", "gate", "cross", "concertina", "z", "2", "3"));
		$this->folding = $folding;
		return true;
    }

    /**
     * setQuantity function.
     *
     * @access public
     * @param mixed $quantity
     * @return void
     */
    public function setQuantity($quantity)	{

	    $this->quantity = $quantity;
    }

    /**
     * setSourceItemId function.
     *
     * @access public
     * @param mixed $sourceItemId
     * @return void
     */
    public function setSourceItemId($sourceItemId)	{

	    $this->sourceItemId = $sourceItemId;
    }

    /**
     * setSKU function.
     *
     * @access public
     * @param mixed $skuCode
     * @return void
     */
    public function setSKU($skuCode)	{

	    $this->sku = $skuCode;
    }

}

?>