<?php
/**
 * Shoporama
 *
 * PHP version 5
 *
 * @category  Shoporama
 * @package   Shoporama
 * @author    Lars Olesen <lars@intraface.dk>
 * @copyright 2017 Lars Olesen
 * @license   MIT Open Source License https://opensource.org/licenses/MIT
 * @version   GIT: <git_id>
 * @link      http://github.com/lsolesen/shoporama-php-sdk
 */

namespace Shoporama;

/**
 * Class Client
 *
 * @category  Shoporama
 * @package   Shoporama
 * @author    Lars Olesen <lars@intraface.dk>
 * @copyright 2017 Lars Olesen
 */
class Client
{
    /**
     * Request object.
     *
     * @var Request
     */
    protected $request;

    /**
     * Construct a Shoporama Client with an API key and optionally an API version.
     *
     * @param Request $request Request object
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Get method
     *
     * @param string $url Url on the REST service
     *
     * @return Response
     */
    public function get($url)
    {
        return $this->request->call('GET', $url);
    }

    /**
     * Get method
     *
     * @param string $url  Url on the REST service
     * @param array  $body Parameters for the request
     *
     * @return Response
     */
    public function post($url, $body)
    {
        return $this->request->call('POST', $url, $body);
    }

    /**
     * Put method
     *
     * @param string $url  Url on the REST service
     * @param array  $body Parameters for the request
     *
     * @return Response
     */
    public function put($url, $body)
    {
        return $this->request->call('PUT', $url, $body);
    }

    /**
     * Delete method
     *
     * @param string $url Url on the REST service
     *
     * @return Response
     */
    public function delete($url)
    {
        return $this->request->call('DELETE', $url);
    }
}
