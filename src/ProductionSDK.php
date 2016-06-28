<?php

if (!class_exists('OneflowSDK')) {
	require_once 'OneFlowSDK.php';
}

class ProductionSDK extends OneflowSDK	{

	public function getStations(){
		return $this->get("/station");
	}
	public function addStation($jsonData){
		return $this->post("/station", $jsonData);
	}

	public function getSKUs(){
		return $this->get("/sku");
	}
	public function addSKU($jsonData){
		return $this->post("/sku", $jsonData);
	}

	public function getClients(){
		return $this->get("/client");
	}
	public function addClient($jsonData){
		return $this->post("/client", $jsonData);
	}

	public function getBatches(){
		return $this->get("/batch");
	}

	public function getWalls(){
		return $this->get("/wall");
	}
	public function addWall($jsonData){
		return $this->post("/wall", $jsonData);
	}

	public function getStock(){
		return $this->get("/stock");
	}
	public function addStock($jsonData){
		return $this->post("/stock", $jsonData);
	}

	public function getCouriers(){
		return $this->get("/courier");
	}
	public function addCourier($jsonData){
		return $this->post("/courier", $jsonData);
	}

	public function getPresses(){
		return $this->get("/press");
	}
	public function addPress($jsonData){
		return $this->post("/press", $jsonData);
	}

	public function getAccounts(){
		return $this->get("/account");
	}
	public function getAccount(){
		return $this->get("/account");
	}

	public function getAccountSettings(){
		return $this->get("/accountSettings");
	}
	public function getStationWorkList($id){
		return $this->get("/worklist/id/$id");
	}

	public function getProducts(){
		return $this->get("/product");
	}
	public function addProduct($jsonData){
		return $this->post("/product", $jsonData);
	}
	public function removeProduct($id){
		return $this->del("/product/$id");
	}

	public function addEventType($jsonData){
		return $this->post("/event-type", $jsonData);
	}
	public function getEventTypes(){
		return $this->get("/event-type");
	}

	public function getPaper(){
		return $this->get("/paper");
	}
	public function addPaper($jsonData){
		return $this->post("/paper", $jsonData);
	}

	public function getPrinters(){
		return $this->get("/printer");
	}
	public function addPrinter($jsonData){
		return $this->post("/printer", $jsonData);
	}

	public function getUsers(){
		return $this->get("/user");
	}
	public function addUser($jsonData){
		return $this->post("/register", $jsonData);
	}
	public function getOrderFiles($orderId){
		return $this->get("/files/order/$orderId");
	}

}