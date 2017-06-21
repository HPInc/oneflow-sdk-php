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
		$this->__addProperty("type", "", false);
	}

	/**
	 * setPath function.
	 *
	 * @access public
	 * @param string $path
	 * @return void
	 */
	public function setPath($path)      {
		$this->path = $path;
	}
}

?>