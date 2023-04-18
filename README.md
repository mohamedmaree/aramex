# Aramex
## Installation

You can install the package via [Composer](https://getcomposer.org).

```bash
composer require maree/aramex
```
Publish your aramex.php config file with

```bash
php artisan vendor:publish --provider="maree\aramex\AramexServiceProvider" --tag="aramex"
```
then change your aramex config from config/aramex.php file
```php

    'ENV'  => 'TEST', //TEST|LIVE
	'TEST' => [
		'AccountNumber'		 	=> '11111',
		'UserName'			 	=> 'maree@aramex.com',
		'Password'			 	=> '123456',
		'AccountPin'		 	=> '122334',
		'AccountEntity'		 	=> 'EGY',
		'AccountCountryCode'	=> 'EG',
		'Version'			 	=> 'v1'
	],
```
## Usage


## Create Pickup 
```php
use maree\aramex\Aramex;
    $data = Aramex::createPickup([
            'name' => 'maree',
            'cell_phone' => '+123123123',
            'phone' => '+123123123',
            'email' => 'myEmail@gmail.com',
            'city' => 'Alexandria',
            'country_code' => 'EG',
            'zip_code'=> 21532,
            'line1' => 'The line1 Details',
            'line2' => 'The line2 Details',
            'line3' => 'The line2 Details',
            'pickup_date' => time() + 75000,
            'ready_time' => time()  + 80000,
            'last_pickup_time' => time() +  85000,
            'closing_time' => time()  + 90000,
            'status' => 'Ready',//Pending 
            'pickup_location' => 'some location',
            'weight' => 123,
            'volume' => 1
        ]);
        // extracting GUID
       if (!$data->error)
          $guid = $data->pickupGUID;
```
- note save this 'guid' ,will use it in next services 

## cancel Pickup 
```php
use maree\aramex\Aramex;
    //get $guid from createPickup function
    $response = Aramex::cancelPickup($guid,$cancel_reason = '');
```

## Create Shipment 
```php
use maree\aramex\Aramex;

        $callResponse = Aramex::createShipment([
            'shipper' => [
                'name' => 'maree',
                'email' => 'myEmail@gmail.com',
                'phone'      => '+123123123',
                'cell_phone' => '+123123123',
                'country_code' => 'EG',
                'city' => 'Alexandria',
                // 'zip_code' => 21532,
                'line1' => 'Line1 Details',
                'line2' => 'Line2 Details',
                'line3' => 'Line3 Details',
            ],
            'consignee' => [
                'name' => 'Steve',
                'email' => 'email@users.companies',
                'phone'      => '+123456789982',
                'cell_phone' => '+321654987789',
                'country_code' => 'EG',
                'city' => 'Alexandria',
                // 'zip_code' => 11865,
                'line1' => 'Line1 Details',
                'line2' => 'Line2 Details',
                'line3' => 'Line3 Details',
            ],
            'shipping_date_time' => time() + 50000,
            'due_date' => time() + 60000,
            'comments' => 'No Comment',
            'pickup_location' => 'at reception',
            'pickup_guid' => $guid, //from createPickup function
            'weight' => 123, // KG
            'number_of_pieces' => 1,
            'product_group'    => 'EXP',
            'product_type' => 'PDX', // if u don't pass it, it will take the config default value
            'height' => 5.5, // CM
            'width' => 3,  // CM
            'length' => 2.3,  // CM
            'description' => 'Goods Description, like Boxes of flowers',
                                        // 'goods_origin_country'    => 'EG',
                                        // 'cash_on_delivery_amount'  => array(
                                        //     'value'                 => 1,
                                        //     'currency_code'         => 'USD'
                                        // ),

                                        // 'insurance_amount'       => array(
                                        //     'value'                 => 0,
                                        //     'currencyCode'          => ''
                                        // ),

                                        // 'CollectAmount'         => array(
                                        //     'Value'                 => 0,
                                        //     'CurrencyCode'          => ''
                                        // ),

                                        // 'cash_additional_amount'  => array(
                                        //     'Value'                 => 0,
                                        //     'CurrencyCode'          => ''
                                        // ),

                                        // 'cash_additional_amount_description' => '',

                                        // 'customs_value_amount' => array(
                                        //     'Value'                 => 0,
                                        //     'CurrencyCode'          => ''
                                        // ),

                                        // 'items'                 => array(
                                        //         'package_type'   => 'Box',
                                        //         'quantity'      => 1,
                                        //         'weight'        => array(
                                        //                 'Value'     => 1,
                                        //                 'Unit'      => 1,
                                        //         ),
                                        //         'comments'      => 'Docs',
                                        //         'reference'     => ''
                                        //     )
        ]);
        if (!empty($callResponse->error))
        {
            foreach ($callResponse->errors as $errorObject) {
              handleError($errorObject->Code, $errorObject->Message);
            }
        }
        else {
          // extract your data here, for example
          // $shipmentId = $response->Shipments->ProcessedShipment->ID;
          // $labelUrl = $response->Shipments->ProcessedShipment->ShipmentLabel->LabelURL;
        }
```

        
## calculate rate 
```php
use maree\aramex\Aramex;
        $originAddress = [
            'line1' => 'Test string',
            'city' => 'Amman',
            'country_code' => 'JO'
        ];

        $destinationAddress = [
            'line1' => 'Test String',
            'city' => 'Dubai',
            'country_code' => 'AE'
        ];

        $shipmentDetails = [
            'weight' => 5, // KG
            'number_of_pieces' => 2,
            'payment_type' => 'P', // if u don't pass it, it will take the config default value 
            'product_group' => 'EXP', // if u don't pass it, it will take the config default value
            'product_type' => 'PPX', // if u don't pass it, it will take the config default value
            'height' => 5.5, // CM
            'width' => 3,  // CM
            'length' => 2.3  // CM
        ];

        $shipmentDetails = [
            'weight' => 5, // KG
            'number_of_pieces' => 2, 
        ];

        $currency = 'USD';
        $data = Aramex::calculateRate($originAddress, $destinationAddress , $shipmentDetails , 'USD');
        
        if(!$data->error){
        }
        else{
          // handle $data->errors
        }   
```

## Track Shipments 
```php
use maree\aramex\Aramex;
        //find createShipmentResults , anotherCreateShipmentResults from 'createShipment' function
        $shipments = [ 
            $createShipmentResults->Shipments->ProcessedShipment->ID,
            $anotherCreateShipmentResults->Shipments->ProcessedShipment->ID,
        ];

        $data = Aramex::trackShipments($shipments);
        if (!$data->error){
          // Code Here
        }
        else {
        // handle error
        }
```

## fetch Countries 
```php
use maree\aramex\Aramex; 
        $data = Aramex::fetchCountries('EG'); 
        //Or 
        $data = Aramex::fetchCountries();
```

## fetch Cities 
```php
use maree\aramex\Aramex; 
    $data = Aramex::fetchCities('EG'); 
```

## validate address
```php
use maree\aramex\Aramex; 
  $data = Aramex::validateAddress([
                'country_code' => 'EG',
                'city' => 'Cairo',
                'zip_code' => 11865,
                'line1' => 'Line1 Details',
                'line2' => 'Line2 Details',
                'line3' => 'Line3 Details',
  ]);
```

- Note that this package require SOAP extension on your server
- Use live account data because test data often does not work
- You can get doucmentation link: https://www.aramex.com/docs/default-source/resourses/resourcesdata/shipping-services-api-manual.pdf
- download aramex api src code from https://www.aramex.com/be/en/solutions-services/developers-solutions-center/apis
- Package is build on package https://github.com/Moustafa22/Laravel-Aramex-SDK






