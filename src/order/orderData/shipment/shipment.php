<?php

require_once "carrier/carrier.php";
require_once "address/address.php";
require_once "attachments/attachments.php";

/**
 * OneFlowShipment class.
 *
 * @extends OneFlowBase
 */
class OneFlowShipment extends OneFlowBase {

	/**
	 * init function.
	 *
	 * @access public
	 * @return void
	 */
	public function init()      {

		$this->__addObject("shipTo", "Address");
		$this->__addObject("carrier","Carrier");

		$this->__addArray("attachments","Attachments");

		$this->__addProperty("shipmentIndex", 0, true);
		$this->__addProperty("pspBranding", true);
		$this->__addProperty("labelName");
		$this->__addProperty("cost");
		$this->__addProperty("dispatchAlert");
	}

	/**
	 * newAttachment function.
	 *
	 * @access public
	 * @return void
	 */
	public function newAttachment($path)	{
		$attachment = new OneFlowAttachment();
		$attachment->setPath($path);
		$this->attachments[] = $attachment;
		return end($this->attachments);
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
	 * setShipmentIndex function.
	 *
	 * @access public
	 * @param mixed $index
	 * @return void
	 */
	public function setShipmentIndex($index)      {
		$this->shipmentIndex = $index;
	}

	/**
	 * setShipTo function.
	 *
	 * @access public
	 * @param mixed $name
	 * @param mixed $companyName
	 * @param mixed $address1
	 * @param string $address2 (default: "")
	 * @param string $address3 (default: "")
	 * @param mixed $town
	 * @param string $state (default: "")
	 * @param mixed $postcode
	 * @param mixed $isoCountry
	 * @param string $phone (default: "")
	 * @return void
	 */
	public function setShipTo($name, $companyName, $address1, $address2="", $address3="", $town, $state="", $postcode, $isoCountry, $country="", $phone="", $email="")      {
		$this->shipTo->name = $name;
		$this->shipTo->companyName = $companyName;
		$this->shipTo->address1 = $address1;
		$this->shipTo->address2 = $address2;
		$this->shipTo->address3 = $address3;
		$this->shipTo->town = $town;
		$this->shipTo->state = $state;
		$this->shipTo->isoCountry = $isoCountry;
		$this->shipTo->country = $country;
		$this->shipTo->postcode = $postcode;
		$this->shipTo->phone = $phone;
		$this->shipTo->email = $email;
	}

	/**
	 * setReturnAddress function.
	 *
	 * @access public
	 * @param mixed $name
	 * @param mixed $companyName
	 * @param mixed $address1
	 * @param string $address2 (default: "")
	 * @param string $address3 (default: "")
	 * @param mixed $town
	 * @param string $state (default: "")
	 * @param mixed $postcode
	 * @param mixed $isoCountry
	 * @return void
	 */
	public function setReturnAddress($name, $companyName, $address1, $address2="", $address3="", $town, $state="", $postcode, $isoCountry)      {

		$this->__addObject("returnAddress","Address");

		$this->returnAddress->address1 = $address1;
		$this->returnAddress->address2 = $address2;
		$this->returnAddress->address3 = $address3;
		$this->returnAddress->town = $town;
		$this->returnAddress->state = $state;
		$this->returnAddress->isoCountry = $isoCountry;
		$this->returnAddress->postcode = $postcode;

	}

	/**
	 * setCarrier function.
	 *
	 * @access public
	 * @param mixed $code
	 * @param mixed $service
	 * @return void
	 */
	public function setCarrier($code, $service)      {
		$this->carrier->code = $code;
		$this->carrier->service = $service;
	}

	/**
	 * setCarrierByAlias function.
	 *
	 * @access public
	 * @param mixed $code
	 * @param mixed $service
	 * @return void
	 */
	public function setCarrierByAlias($alias)      {
		$this->carrier->alias = $alias;
	}

	/**
	 * setLabelName function.
	 *
	 * @access public
	 * @param mixed $labelName
	 * @return void
	 */
	public function setLabelName($labelName)      {
		$this->labelName = $labelName;
	}

}

?>