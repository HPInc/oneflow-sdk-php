<?php
require_once "base.php";
require_once __DIR__.'/order/order.php';



class OneFlowSDKLoader {

	public $mappings;
	public $orderModel;

	public function __construct() {

		$this->mappings = Array(
			'source'=>'OneFlowPoint',
			'destination'=>'OneFlowPoint',
			'orderData'=>'OneFlowOrderData',
			'error'=>'OneFlowError',
			'item'=>'OneFlowItem',
			'shipment'=>'OneFlowShipment',
			'component'=>'OneFlowComponent',
			'colour'=>'OneFlowColour',
			'finish'=>'OneFlowFinish',
			'shipTo'=>'OneFlowAddress',
			'returnAddress'=>'OneFlowReturnAddress',
			'carrier'=>'OneFlowCarrier'
		);

	}

}

$loader = new OneFlowSDKLoader();

/**
 * OneflowSDK class.
 */
class OneflowSDK {

	protected $url;
	protected $file_url;
	protected $key;
	protected $secret;
	protected $client;

	public function __construct($url, $key, $secret){
		if(!$url || !$key || !$secret){
			throw new Exception("Error creating sdk instance. Url, key and secret are required", 1);
		}
		$this->url = $url;
		$this->key = $key;
		$this->secret = $secret;
		$this->apiName = "connect";
		$this->version = "0.1";
		$this->authHeader = "x-oneflow-authorization";
	}

	//ACCOUNTS

	public function setAuthHeader($header){
		$this->authHeader = $header;
	}

	/**
	 * accountsGetMy function.
	 *
	 * @access public
	 * @return void
	 */
	public function accountsGetMy(){
		return json_decode($this->get('/account'));
	}

	/**
	 * accountsGetAll function.
	 *
	 * @access public
	 * @return void
	 */
	public function accountsGetAll(){
		return json_decode($this->get('/account/all'));
	}

	/**
	 * accountsGetById function.
	 *
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	public function accountsGetById($id){
		return json_decode($this->get('/account/' . $id));
	}

	/**
	 * accountsCreate function.
	 *
	 * @access public
	 * @return void
	 */
	public function accountsCreate(){
		return json_decode($this->post('/account', $data));
	}

	//ORDERS

	/**
	 * processOrderArray function.
	 *
	 * @access private
	 * @param mixed $orderResponse
	 * @return void
	 */
	private function processOrderArray($orderResponse){
		$orders = Array();

		if ($orderResponse)	{
			foreach ($orderResponse as $k=>$order)	{
				$orders[] = new OneFlowOrder($order);
			}
			return $orders;
		}	else	{
			echo "Order Fetch Error\n";
			return false;
		}

	}

	/**
	 * ordersGetById function.
	 *
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	public function ordersGetById($id){
		$order = json_decode($this->get('/order/' . $id));
		return new OneFlowOrder($order);
	}

	/**
	 * ordersCreate function.
	 *
	 * @access public
	 * @param mixed $order
	 * @return void
	 */
	public function orderValidate($order)	{

		echo "Validation     : Passed\n";
		return $this->post('/order/validate', $order->toJSON());

	}

	/**
	 * ordersCreate function.
	 *
	 * @access public
	 * @param mixed $order
	 * @return void
	 */
	public function ordersCreate($order)	{

		//check that order is valid before submission

		if (count($order->isValid())>0)	{

			echo "Validation     : Failed\n";
			return $order->validateOrder();

		}	else	{

			echo "Validation     : Passed\n";
			return $this->post('/order', $order->toJSON());
		}

	}

	/**
	 * postFile function.
	 *
	 * @access public
	 * @param mixed $uploadUrl
	 * @param mixed $localPath
	 * @return void
	 */
	public function postFile($uploadUrl, $localPath){
		if (file_exists($localPath))	{
			return json_decode($this->post_file_s3($uploadUrl, $localPath));
		}	else	{
			return json_decode('{"success":false, "message":"File: '.$localPath.' does not exist"}');
		}
	}

	/**
	 * ordersCancel function.
	 *
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	public function orderCancel($id){
		if (strlen($id)>0)		return $this->del("/order/$id");
		else					return false;
	}

	/**
	 * ordersUpdateById function.
	 *
	 * @access public
	 * @param mixed $id
	 * @param mixed $orderData
	 * @return void
	 */
	public function ordersUpdateById($id, $orderData){
		if (strlen($id)>0)	return $this->put("/order/$id", json_encode($orderData));
		else				return false;
	}

/////////////////////////////////////////////////////////////////

	/**
	 * request function.
	 *
	 * @access private
	 * @param mixed $method
	 * @param mixed $path
	 * @param mixed $jsonData (default: null)
	 * @param mixed $optional_headers (default: null)
	 * @return void
	 */
	protected function request($method, $path, $jsonData=null, $optional_headers = null)	{

		ini_set("track_errors","on");

		$timestamp = time();
		$url = $this->url.$path;
		$urlParts = parse_url($url);
		$fullPath = $urlParts['path'];

		if (filter_var($url, FILTER_VALIDATE_URL)===FALSE)	return false;

		$params = array(
			'http' => array(
				'ignore_errors' => '1',
				'method' => $method
			)
		);

		if ($method=="POST" || $method=="PUT")	{
			$params['http']['content'] = $jsonData;
		}

		$params['http']['header'][] = "x-oneflow-date: $timestamp";
		$params['http']['header'][] = $this->authHeader.": ".$this->token($method, $fullPath, $timestamp, $jsonData);

		foreach ($optional_headers as $name => $value)	{
			$params['http']['header'][] = "$name: $value";
		}

//		echo "Connecting To  : $url\n";

		$context = stream_context_create($params);
		$fp = fopen($url, 'rb', false, $context);
		if (!$fp)	{
//			print_r($http_response_header);
			throw new Exception("Problem creating stream from $url, \n\t".implode("\n\t", error_get_last()));
		}

		$response = stream_get_contents($fp);
		if ($response === false)	throw new Exception("Problem reading data from $url, $php_errormsg");

		return $response;
	}

	/**
	 * get function.
	 *
	 * @access private
	 * @param mixed $path
	 * @param string $format (default: 'application/json')
	 * @return void
	 */
	protected function get($path, $format = 'application/json'){

		try {

			$response = $this->request("GET", $path, "", array(
	    		'Accept' => $format,
			));

		} catch (Exception $e) {
			echo "get exception\n";
			echo $e->getMessage()."\n";
		}

		return $response;
	}

	/**
	 * post function.
	 *
	 * @access private
	 * @param mixed $path
	 * @param mixed $jsonData
	 * @param string $format (default: 'application/json')
	 * @return void
	 */
	protected function post($path, $jsonData, $format = 'application/json')	{

		try {

			$response = $this->request("POST", $path, $jsonData, array(
	    		'Content-Type' => $format,
	    		'Accept' => $format,
			));

		} catch (Exception $e) {
			echo $e->getMessage()."\n";
		}

		return $response;
	}

	/**
	 * put function.
	 *
	 * @access private
	 * @param mixed $path
	 * @param mixed $jsonData
	 * @param string $format (default: 'application/json')
	 * @return void
	 */
	protected function put($path, $jsonData, $format = 'application/json'){

		try {

			$response = $this->request("PUT", $path, $jsonData, array(
	    		'Content-Type' => $format,
	    		'Accept' => $format,
			));

		} catch (Exception $e) {
			echo $e->getMessage()."\n";
		}

		return $response;
	}

	/**
	 * del function.
	 *
	 * @access private
	 * @param mixed $path
	 * @param string $format (default: 'application/json')
	 * @return void
	 */
	protected function del($path, $format = 'application/json'){

		try {
			$response = $this->request("DELETE", $path, "", array(
	    		'Accept' => $format,
			));

		} catch (Exception $e) {
			echo $e->getMessage()."\n";
		}

		return $response;
	}

	/**
	 * post_file function.
	 *
	 * @access public
	 * @param mixed $uploadUrl
	 * @param mixed $localPath
	 * @return void
	 */
	protected function post_file_s3($uploadUrl, $localPath)	{

		echo "Uploading      : $localPath\n";
		echo "To             : $uploadUrl\n";

		//get the file
		$fileHandle = fopen($localPath, "rb");
		$fileContents = stream_get_contents($fileHandle);
		fclose($fileHandle);

		echo "File Size      : ".strlen($fileContents)."\n";

		//set the ctx params
		$params = array(
			'http' => array(
				'ignore_errors' => '1',
				'method' => 'PUT',
				'header' => Array(
					"Content-Type: application/pdf"
				),
				'content' => $fileContents
		    ));
		$ctx = stream_context_create($params);

		//upload the file
		$fp = fopen($uploadUrl, 'rb', false, $ctx);
		if (!$fp)					throw new Exception("PROBLEM:\n".implode("\n\t", error_get_last())."\n\n\n\n");

		$response = stream_get_contents($fp);
		if ($response === false) 	throw new Exception("Problem reading data from $url, $php_errormsg");

		return $response;
	}

	/**
	 * token function.
	 *
	 * @access private
	 * @param mixed $method
	 * @param mixed $path
	 * @param mixed $timestamp
	 * @return void
	 */
	private function token($method, $path, $timestamp){
		$stringToSign = strtoupper($method) . ' ' . $path . ' ' . $timestamp;
		return $this->key . ':' . hash_hmac('sha1', $stringToSign, $this->secret);
	}
}