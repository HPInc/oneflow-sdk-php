<?php

/**
 * OneFlowAddress class.
 *
 * @extends OneFlowBase
 */
class OneFlowAttachment extends OneFlowBase {

	/**
	 * init function.
	 *
	 * @access public
	 * @return void
	 */
	public function init()      {

		$this->__addProperty("path", "", true);
	}

	/**
	 * setPath function.
	 *
	 * @access public
	 * @return void
	 */
	public function setPath($path)      {

		$this->path = $path;
	}
}

?>