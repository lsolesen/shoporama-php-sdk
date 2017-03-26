<?php

namespace Shoporama\Tests;

use Shoporama\Client;
use Shoporama\Request;

/**
 * Class ClientTest
 *
 * @package Shoporama
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    protected $api_key = API_KEY;

    public function getClient($key)
    {
        $request = new Request($key);
        return new Client($request);
    }

    public function testConstructor()
    {
        $invalid_api_key = 'invalid';
        $client = $this->getClient($invalid_api_key);
        $this->assertInstanceOf('Shoporama\Client', $client);
    }

    /**
     * @group IntegrationTest
     */
    public function testConstructorWithValidCredentials()
    {
        $client = $this->getClient($this->api_key);
        $this->assertInstanceOf('Shoporama\Client', $client);
    }

    /**
     * @group IntegrationTest
     */
    public function testGetByGettingAProductList()
    {
        $client = $this->getClient($this->api_key);
        $response = $client->get('/product?limit=100');
        $this->assertInstanceOf('Shoporama\Response', $response);
    }

    protected function getProductDataArray()
    {
        $data = array(
            "name" => "Testprodukt",
            "is_online" => 1,
            "supplier_id" => 4600,
            "brand_id" => 642,
            "description" => "Test product",
            "list_description" => "",
            "main_category_id" => "",
            "profile_id" => 12115,
            "price" => mt_rand(1, 200),
            "sale_price" => "",
            "auto_offline" => 0,
            "shipping_weight" => "",
            "meta_title" => "",
            "meta_description" => "",
            "canonical_id" => "",
            "auto_offline" => "",
            "rewrite_url" => "produktet-" . mt_rand(1, 100),
            "attributes" => array(
                // Plastik
                array(
                    "attribute_id" => 15964,
                    "value" => 100588
                ),
                // Vægt
                array(
                    "attribute_id" => 15965,
                    "value" => 100568
                ),
                // Farve
                array(
                    "attribute_id" => 15966,
                    "value" => 100570
                ),
            ),
            "stock_settings" => array(
                array(
                    "attribute_id" => 15964,
                    "attribute_value_id" => 100588,
                    "purchase_price" => mt_rand(1, 100),
                    "sec_own_id" => "STOCK",
                    "own_id" => "STOCK-1"
                ),
                array(
                    "attribute_id" => 15965,
                    "attribute_value_id" => 100568,
                    "purchase_price" => mt_rand(1, 100),
                    "sec_own_id" => "yy",
                    "own_id" => "STOCK-2"
                ),
                array(
                    "attribute_id" => 15966,
                    "attribute_value_id" => 100570,
                    "purchase_price" => mt_rand(1, 100),
                    "sec_own_id" => "yy",
                    "own_id" => "STOCK-3"
                ),
            ),
            "images" => array(
                array(
                    "data" => base64_encode(file_get_contents(dirname(__FILE__) . "/img.png"))
                )
            ),
        );
        return $data;
    }

    /**
     * @group IntegrationTest
     */
    public function __testGetPostPutPatchDeleteByManipulatingAProduct()
    {
        // Test POST for creating a product
        $data = $this->getProductDataArray();
        $client = $this->getClient($this->api_key);
        $response = $client->post('/product', $data);
        $this->assertInstanceOf('Shoporama\Response', $response);
        $array = json_decode($response->getBody(), true);

        $product_id = $array['product_id'];
        $this->assertTrue($product_id > 0);

        // Test PUT for updating a Product
        $expected = $data['name'] = 'PUT Testproduct';
        $response = $client->put('/product/' . $product_id, $data);
        $this->assertInstanceOf('Shoporama\Response', $response);
        $array = json_decode($response->getBody(), true);
        $put_product_id = $array['product_id'];

        $this->assertTrue($product_id === $put_product_id);
        $response = $client->get('/product/' . $product_id);
        $array = json_decode($response->getBody(), true);
        $this->assertEquals($expected, $array['name']);
        $this->assertEquals($data['description'], $array['description']);

        // Test PUT for updating a Product - overwrites everything when new data
        $put_data = array('name' => 'PUT new data Testproduct');
        $expected = $put_data['name'];
        $response = $client->put('/product/' . $product_id, $put_data);
        $this->assertInstanceOf('Shoporama\Response', $response);
        $array = json_decode($response->getBody(), true);
        $put_product_id = $array['product_id'];

        $this->assertTrue($product_id === $put_product_id);
        $response = $client->get('/product/' . $product_id);
        $array = json_decode($response->getBody(), true);
        $this->assertEquals($expected, $array['name']);
        //$this->assertNotEquals($data['description'], $array['description']);

        // Test PATCH for updating af product.
        $patch_data = array('name' => 'PATCH Testproduct');
        $expected = $patch_data['name'];
        $response = $client->put('/product/' . $product_id, $patch_data);
        $this->assertInstanceOf('Shoporama\Response', $response);
        $array = json_decode($response->getBody(), true);
        $patch_product_id = $array['product_id'];

        $this->assertTrue($product_id === $patch_product_id);
        $response = $client->get('/product/' . $product_id);
        $array = json_decode($response->getBody(), true);
        $this->assertEquals($expected, $array['name']);
        $this->assertEquals($data['description'], $array['description']);

        // Testing DELETE - should remove the product
        $response = $client->delete('/product/' . $product_id);
        $this->assertInstanceOf('Shoporama\Response', $response);

        // Testing GET - product should not exist anymore
        $response = $client->get('/product/' . $product_id);
        $this->assertInstanceOf('Shoporama\Response', $response);
        $this->assertEquals('410 Gone', $response->getBody());
    }

    public function testStock()
    {
        $data = array(
            "name" => "Testprodukt",
            "is_online" => 1,
            "supplier_id" => 4600,
            "brand_id" => 642,
            "profile_id" => 12115,
            "price" => mt_rand(1, 200),
            "auto_offline" => 0,
            "rewrite_url" => "stock-" . mt_rand(1, 100),
            "attributes" => array(
                // Speed
                array(
                    "attribute_id" => 15984,
                    "value" => 5
                )
            ),
            "stock_settings" => array(
                // Plastik: 15964
                // Vægt: 15965
                // Farve: 15966
                array(
                    "attribute_id" => 15964,
                    "attribute_value_id" => 100588,
                    "purchase_price" => mt_rand(1, 100),
                    "sec_own_id" => "STOCK",
                    "own_id" => "STOCK-1"
                ),
                array(
                    "attribute_id" => 15965,
                    "attribute_value_id" => 100568,
                    "purchase_price" => mt_rand(1, 100),
                    "sec_own_id" => "yy",
                    "own_id" => "STOCK-2"
                ),
                array(
                    "attribute_id" => 15966,
                    "attribute_value_id" => 100570,
                    "purchase_price" => mt_rand(1, 100),
                    "sec_own_id" => "yy",
                    "own_id" => "STOCK-3"
                ),
            )
        );
        $client = $this->getClient($this->api_key);
        $response = $client->post('/product', $data);
        $array = json_decode($response->getBody(), true);

        $product_id = $array['product_id'];

        $data = array(
            array(
                "SKU" => "STOCK-1",
                "stock" => 10
            ),
            array(
                "SKU" => "STOCK-2",
                "stock" => 5
            ),
            array(
                "SKU" => "STOCK-3",
                "stock" => 5
            )
        );

        $response = $client->post('/stock', $data);
        $this->assertInstanceOf('Shoporama\Response', $response);
        print_r($response->getBody());

        $response = $client->get('/product/' . $product_id);
        print_r($response->getBody());
    }
}
