<?php

use PHPUnit\Framework\TestCase;

final class OneflowOrderTest extends TestCase
{
	public function testCanBuildASingleItemOrder()
	{
		// Arrange
		$order = new OneFlowOrder();
		$order->setDestination('destinationName');
		$orderData = new OneFlowOrderData();
		$orderData->setSourceOrderId('uniqueSourceOrderId');
		$orderData->setCustomerName('customerName');
		$orderData->setEmail('customerEmail');

		$item = $orderData->newSKUItem('skuCode', 'itemId', 5);
		$item->setBarcode('customItemBarcode');
		$item->setDispatchAlert('my item dispatch alert');

		$component = $item->newComponent('componentCode');
		$component->setFetchUrl('http://site.com/file.pdf');
		$component->setBarcode('customComponentBarcode');
		$component->setPreflight(true);
		$component->setPreflightProfile('custom');
		$component->setPreflightProfileId('preflightProfileId');
		$component->setBarcode('customComponentBarcode');
		$component->addAttribute('fooString', 'bar');
		$component->addAttribute('fooNumber', 123);

		$stockItem = $orderData->newStockItem('stockCode', 100);

		$shipment = $orderData->newShipment();
		$shipment->setShipTo(
			'name',
			'companyName',
			'address1',
			'address2',
			'address3',
			'town',
			'state',
			'postcode',
			'isoCountryCode',
			'countryName',
			'phone',
			'email'
		);
		$shipment->setReturnAddress(
			'name',
			'companyName',
			'address1',
			'address2',
			'address3',
			'town',
			'state',
			'postcode',
			'isoCountryCode'
		);
		$shipment->setCarrier('carrierCode', 'carrierService');
		$shipment->setCarrierByAlias('carrierAlias');
		$shipment->newAttachment('http://site.com/attachment.pdf', 'insert');
		$shipment->setDispatchAlert('my shipment dispatch alert');
		$shipment->setLabelName('labelName');

		$item->setShipment($shipment);
		$stockItem->setShipment($shipment);

		$order->setOrderData($orderData);

		// Execute
		$json = $order->toJSON();
		$result = json_decode($json);

		// Assert
		$validationOutput = $order->validateOrder();
		$this->assertEquals("Valid Order\n", $validationOutput);

		// Order
		$this->assertObjectHasAttribute('orderData', $result);
		$this->assertObjectHasAttribute('destination', $result);
		$this->assertEquals('destinationName', $result->destination->name);
		$this->assertEquals('uniqueSourceOrderId', $result->orderData->sourceOrderId);
		$this->assertEquals('customerName', $result->orderData->customerName);
		$this->assertEquals('customerEmail', $result->orderData->email);

		// Items
		$this->assertEquals(1, count($result->orderData->items));
		$outputItem = $result->orderData->items[0];
		$this->assertEquals('skuCode', $outputItem->sku);
		$this->assertEquals('itemId', $outputItem->sourceItemId);
		$this->assertEquals('customItemBarcode', $outputItem->barcode);
		$this->assertEquals(5, $outputItem->quantity);
		$this->assertEquals(0, $outputItem->shipmentIndex);
		$this->assertEquals('my item dispatch alert', $outputItem->dispatchAlert);

		$this->assertEquals(1, count($outputItem->components));
		$outputComponent = $outputItem->components[0];
		$this->assertEquals('componentCode', $outputComponent->code);
		$this->assertEquals('http://site.com/file.pdf', $outputComponent->path);
		$this->assertEquals('customComponentBarcode', $outputComponent->barcode);
		$this->assertEquals(true, $outputComponent->fetch);
		$this->assertEquals(false, $outputComponent->localFile);
		$this->assertObjectHasAttribute('attributes', $outputComponent);
		$this->assertEquals('bar', $outputComponent->attributes->fooString);
		$this->assertEquals(123, $outputComponent->attributes->fooNumber);
		$this->assertEquals(true, $outputComponent->preflight);
		$this->assertEquals('custom', $outputComponent->preflightProfile);
		$this->assertEquals('preflightProfileId', $outputComponent->preflightProfileId);

		// Stock items
		$this->assertEquals(1, count($result->orderData->stockItems));
		$outputStockItem = $result->orderData->stockItems[0];
		$this->assertEquals('stockCode', $outputStockItem->code);
		$this->assertEquals(100, $outputStockItem->quantity);
		$this->assertEquals(0, $outputStockItem->shipmentIndex);


		// Shipments
		$this->assertEquals(1, count($result->orderData->shipments));
		$outputShipment = $result->orderData->shipments[0];
		$this->assertEquals(0, $outputShipment->shipmentIndex);
		$this->assertEquals('my shipment dispatch alert', $outputShipment->dispatchAlert);
		$this->assertEquals('labelName', $outputShipment->labelName);

		$this->assertObjectHasAttribute('shipTo', $outputShipment);
		$this->assertEquals('name', $outputShipment->shipTo->name);
		$this->assertEquals('companyName', $outputShipment->shipTo->companyName);
		$this->assertEquals('address1', $outputShipment->shipTo->address1);
		$this->assertEquals('address2', $outputShipment->shipTo->address2);
		$this->assertEquals('address3', $outputShipment->shipTo->address3);
		$this->assertEquals('town', $outputShipment->shipTo->town);
		$this->assertEquals('state', $outputShipment->shipTo->state);
		$this->assertEquals('postcode', $outputShipment->shipTo->postcode);
		$this->assertEquals('isoCountryCode', $outputShipment->shipTo->isoCountry);
		$this->assertEquals('countryName', $outputShipment->shipTo->country);
		$this->assertEquals('phone', $outputShipment->shipTo->phone);
		$this->assertEquals('email', $outputShipment->shipTo->email);

		$this->assertObjectHasAttribute('returnAddress', $outputShipment);
		$this->assertEquals('name', $outputShipment->returnAddress->name);
		$this->assertEquals('companyName', $outputShipment->returnAddress->companyName);
		$this->assertEquals('address1', $outputShipment->returnAddress->address1);
		$this->assertEquals('address2', $outputShipment->returnAddress->address2);
		$this->assertEquals('address3', $outputShipment->returnAddress->address3);
		$this->assertEquals('town', $outputShipment->returnAddress->town);
		$this->assertEquals('state', $outputShipment->returnAddress->state);
		$this->assertEquals('postcode', $outputShipment->returnAddress->postcode);
		$this->assertEquals('isoCountryCode', $outputShipment->returnAddress->isoCountry);

		$this->assertObjectHasAttribute('carrier', $outputShipment);
		$this->assertEquals('carrierCode', $outputShipment->carrier->code);
		$this->assertEquals('carrierService', $outputShipment->carrier->service);
		$this->assertEquals('carrierAlias', $outputShipment->carrier->alias);

		$this->assertEquals(1, count($outputShipment->attachments));
		$outputAttachment = $outputShipment->attachments[0];
		$this->assertEquals('http://site.com/attachment.pdf', $outputAttachment->path);
		$this->assertEquals('insert', $outputAttachment->type);

	}
}
