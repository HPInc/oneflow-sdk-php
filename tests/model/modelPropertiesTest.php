<?php

use PHPUnit\Framework\TestCase;

final class ModelPropertiesTest extends TestCase
{
	public function testModelSettersUseDeclaredProperties()	{
		set_error_handler(function ($severity, $message, $file, $line) {
			if ($severity===E_DEPRECATED)	{
				throw new ErrorException($message, 0, $severity, $file, $line);
			}
			return false;
		});

		try {
			$item = new OneFlowItem();
			$item->setBinding('perfect');
			$item->setFolding('half');
			$component = $item->newComponent('cover');
			$component->setColour();
			$component->setFinish();
			$shipment = new OneFlowShipment();
			$shipment->setReturnAddress('name', '', 'street', '', '', 'town', '', 'postcode', 'GB', 'UK', 'phone');

			foreach (Array($item, $component, $shipment, new OneflowSDK('https://example.com', 'key', 'secret')) as $model)	{
				$reflection = new ReflectionObject($model);
				foreach (get_object_vars($model) as $name=>$value)	{
					$this->assertTrue($reflection->getProperty($name)->isDefault());
				}
			}
		} finally {
			restore_error_handler();
		}
	}

	public function testOptionalFieldsKeepExistingHydrationBehaviour()	{
		$point = new OneFlowPoint((object)Array('name'=>'ignored', 'id'=>'ignored'));
		$this->assertEquals('{}', $point->toJSON());
		$point->name = 'destination';
		$point->setValue('name', 'updated');
		$this->assertEquals('updated', $point->name);
		$point->name = '';
		$point->removeIfEmpty('name');
		$point->setValue('name', 'ignored');
		$this->assertEquals('{}', $point->toJSON());
	}

	public function testValidationStillRemovesEmptyOptionalFields()	{
		$item = new OneFlowItem();
		$item->barcode = '';
		$this->assertTrue(property_exists(json_decode($item->toJSON()), 'components'));
		$item->isValid();
		$result = json_decode($item->toJSON());
		$this->assertFalse(property_exists($result, 'components'));
		$this->assertFalse(property_exists($result, 'barcode'));
		$this->assertEquals('', $result->sourceItemId);
		$this->assertEquals(0, $result->shipmentIndex);
	}

	public function testSubclassNullDefaultsArePreserved()	{
		$point = new class extends OneFlowPoint {
			public $custom = null;
		};
		$this->assertTrue(array_key_exists('custom', get_object_vars($point)));
		$this->assertEquals('{"custom":null}', $point->toJSON());
	}
}
