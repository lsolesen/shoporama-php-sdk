<?php

namespace Shoporama\Tests;

use Shoporama\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Request.
     */
    protected $request;
    protected $api_key = API_KEY;

    public function __construct()
    {
        $this->request = new Request($this->api_key);
    }

    public function testConstructor()
    {
        $this->assertInstanceOf('Shoporama\Request', $this->request);
    }

    /**
     * @group IntegrationTest
     */
    public function testProductListAll()
    {
        $response = $this->request->call('GET', '/product?limit=100');
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
            "profile_id" => 12115,
            "price" => mt_rand(1, 200),
            "auto_offline" => 0,
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

        $response = $this->request->call('POST', '/product', $data);
        $this->assertInstanceOf('Shoporama\Response', $response);

        $array = json_decode($response->getBody(), true);

        $product_id = $array['product_id'];

        $response = $this->request->call('DELETE', '/product/' . $product_id);
        $this->assertInstanceOf('Shoporama\Response', $response);
    }
}
