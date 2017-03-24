<?php
/**
 * Shoporama
 *
 * PHP version 5
 *
 * @category  Shoporama
 * @package   Shoporama
 * @author    Lars Olesen <lars@intraface.dk>
 * @copyright 2014 Lars Olesen
 * @license   MIT Open Source License https://opensource.org/licenses/MIT
 * @version   GIT: <git_id>
 * @link      http://github.com/lsolesen/Shoporama
 */

namespace Shoporama;

/**
 * Shoporama: request.
 *
 * @category  Shoporama
 * @package   Shoporama
 * @author    Lars Olesen <lars@intraface.dk>
 * @copyright 2014 Lars Olesen
 */
class Request
{
    protected $accessToken;

    /**
     * Construct a Shoporama Request with an API key and an API version.
     *
     * @param string $accessToken Access token from Shoporama
     */
    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Run a custom request on Shoporama API on a specific address
     * with possible parameters and receive a response array as
     * return.
     *
     * @param string $method Either GET, POST, PUT or DELETE
     * @param string $url    Sub-address to call, e.g. invoices or invoices/ID
     * @param array  $body   Parameters to be sent to Shoporama API
     *
     * @return Response from Shoporama API
     */
    public function call($method, $url, $body = null)
    {
        $headers = array(
            "X-Access-Token: " . $this->accessToken,
            "Authorization: Shoporama " . $this->accessToken,
            "User-Agent: Shoporama PHP-SDK");

        $curl = curl_init("https://www.shoporama.dk/REST" . $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        if ($body) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body));
            $headers[] = "Content-Type: application/json";
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $return = curl_exec($curl);
        if (curl_errno($curl)) {
            throw new Exception('Curl error: ' . curl_error($curl));
        }
        $info = curl_getinfo($curl);

        return new Response($info, $return);
    }
}
