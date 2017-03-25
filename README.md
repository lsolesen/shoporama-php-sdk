# Shoporama PHP SDK
[![Build Status](https://travis-ci.org/lsolesen/shoporama-php-sdk.svg?branch=master)](https://travis-ci.org/lsolesen/shoporama-php-sdk) [![Code Coverage](https://scrutinizer-ci.com/g/lsolesen/shoporama-php-sdk/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/lsolesen/shoporama-php-sdk/?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lsolesen/shoporama-php-sdk/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lsolesen/shoporama-php-sdk/?branch=master) [![Latest Stable Version](https://poser.pugx.org/lsolesen/shoporama-php-sdk/v/stable)](https://packagist.org/packages/lsolesen/shoporama-php-sdk) [![Total Downloads](https://poser.pugx.org/lsolesen/shoporama-php-sdk/downloads)](https://packagist.org/packages/lsolesen/shoporama-php-sdk) [![License](https://poser.pugx.org/lsolesen/shoporama-php-sdk/license)](https://packagist.org/packages/lsolesen/shoporama-php-sdk)

PHP SDK for [Shoporama API](https://shoporama.dk/api) only from [Shoporama](http://www.shoporama.dk/).

## Getting started

Before doing anything you should register yourself with Billy and get access credentials.

## Installation

### Composer

Simply add a dependency on lsolesen/billy-php-sdk to your project's `composer.json` file if you use Composer to manage the dependencies of your project. Here is a minimal example of a composer.json file that just defines a dependency on Billy PHP SDK 2.1:

```
{
    "require": {
        "lsolesen/shoporama-php-sdk": "0.1.*"
    }
}
```

After running `composer install`, you can take advantage of Composer's autoloader in `vendor/autoload.php`.

## Usage

#### Create client

```php5
<?php
$request = new Request($api_key);
return new Client($request);
```

Now the client can be used to call the diffferent endpoint at Shoporama.

```php5
<?php
$client->get($ressource_url);
$client->post($ressource_url, $data);
$client->put($ressource_url, $data);
$client->patch($ressource_url, $data);
$client->delete($ressource_url);
```

### Examples

#### Create product

```php5
<?php
// POST for creating a product
$data = array('name' => 'My Shoporama Product');
$response = $client->post('/product', $data);
$array = json_decode($response->getBody(), true);
$product_id = $array['product_id'];
```

#### Update entire product ressource

```php5
<?php
// PUT for updating a Product
$data['name'] = 'PUT Testproduct';
$response = $client->put('/product/' . $product_id, $data);
$array = json_decode($response->getBody(), true);
```

#### Update product field

```php5
<?php
// Test PATCH for updating af product.
$patch_data = array('name' => 'PATCH Testproduct');
$response = $client->put('/product/' . $product_id, $patch_data);
$array = json_decode($response->getBody(), true);
```

#### Delete product

```php5
<?php
// Testing DELETE - should remove the product
$response = $client->delete('/product/' . $product_id);
$this->assertInstanceOf('Shoporama\Response', $response);
```

#### Get product

```php5
<?php
// Testing GET - product should not exist anymore
$response = $client->get('/product/' . $product_id);
```

## Contributing

You are more than welcome to contribute using pull requests.
