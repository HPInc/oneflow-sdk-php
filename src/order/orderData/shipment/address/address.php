<?php

/**
 * OneFlowAddress class.
 *
 * @extends OneFlowBase
 */
class OneFlowAddress extends OneFlowBase {

	public $name;
	public $companyName;
	public $address1;
	public $address2;
	public $address3;
	public $town;
	public $state;
	public $postcode;
	public $isoCountry;
	public $country;
	public $phone;
	public $email;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @param mixed $values (default: null)
	 */
	public function __construct($values=null)	{
		//keep the existing fields absent until init or a setter assigns them
		unset($this->name);
		unset($this->companyName);
		unset($this->address1);
		unset($this->address2);
		unset($this->address3);
		unset($this->town);
		unset($this->state);
		unset($this->postcode);
		unset($this->isoCountry);
		unset($this->country);
		unset($this->phone);
		unset($this->email);

		parent::__construct($values);
	}

	/**
	 * init function.
	 *
	 * @access public
	 * @return void
	 */
	public function init()      {

		$this->__addProperty("name", "", true);
		$this->__addProperty("companyName");
		$this->__addProperty("address1", "", true);
		$this->__addProperty("address2", "");
		$this->__addProperty("address3", "");
		$this->__addProperty("town", "", true);
		$this->__addProperty("state", "");
		$this->__addProperty("postcode", "");
		$this->__addProperty("isoCountry", "", true);
		$this->__addProperty("country", "");
		$this->__addProperty("phone");
		$this->__addProperty("email", "");
	}
}

?>
