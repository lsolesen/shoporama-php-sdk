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
    public function testGet()
    {
        $client = $this->getClient($this->api_key);
        $response = $client->get('/product?limit=100');
        $this->assertInstanceOf('Shoporama\Response', $response);
    }

    /**
     * @group IntegrationTest
     */
    public function testCreateProduct()
    {
        $data = array(
            "name" => "Testprodukt",
            "is_online" => 1,
            "supplier_id" => 4600,
            "brand_id" => 642,
            "description" => "",
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
                    "sec_own_id" => "xx",
                    "own_id" => "xxx"
                ),
                array(
                    "attribute_id" => 15965,
                    "attribute_value_id" => 100568,
                    "purchase_price" => mt_rand(1, 100),
                    "sec_own_id" => "yy",
                    "own_id" => "yyy"
                ),
                array(
                    "attribute_id" => 15966,
                    "attribute_value_id" => 100570,
                    "purchase_price" => mt_rand(1, 100),
                    "sec_own_id" => "yy",
                    "own_id" => "yyy"
                ),
            ),
            "images" => array(
                array(
                    "data" => base64_encode(file_get_contents(dirname(__FILE__) . "/../scripts/img.png"))
                )
            ),
        );

        $client = $this->getClient($this->api_key);
        $response = $client->post('/product', $data);
        $this->assertInstanceOf('Shoporama\Response', $response);

        $array = json_decode($response->getBody(), true);

        $product_id = $array['product_id'];

        $response = $client->delete('/product/' . $product_id);
        $this->assertInstanceOf('Shoporama\Response', $response);
    }
}
