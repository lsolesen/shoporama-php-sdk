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

use Shoporama\ShoporamaException;

/**
 * Shoporama: response.
 *
 * @category  Shoporama
 * @package   Shoporama
 * @author    Lars Olesen <lars@intraface.dk>
 * @copyright 2014 Lars Olesen
 */
class Response
{
    protected $status;
    protected $body;

    /**
     * Construct a Shoporama Request with an API key and an API version.
     *
     * @param array $info Info about the response
     * @param array $body Body of the response
     */
    public function __construct($info, $body)
    {
        $this->info = $info;
        $this->body = $body;
    }

    /**
     * Get the response body
     *
     * @return object stdClass
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Get the raw response body
     *
     * @return object stdClass
     */
    public function getRawBody()
    {
        return $this->body;
    }

    /**
     * Get the response body
     *
     * @return object stdClass
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Get the status code
     *
     * @return object stdClass
     */
    public function isSuccess()
    {
        return ($this->info['http_code'] === 200);
    }
}
