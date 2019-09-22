<?php
namespace T3Monitor\T3monitoring\Domain\Model\Dto;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class ResolverData
{
    /**
     * @var array
     */
    protected $client;

    /**
     * @var array
     */
    protected $response;

    /**
     * @var array
     */
    protected $responseHeaders;

    public function __construct(array $client, array $response, array $responseHeaders)
    {
        $this->client = $client;
        $this->response = $response;
        $this->responseHeaders = $responseHeaders;
    }

    /**
     * @return array
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param array $client
     */
    public function setClient(array $client)
    {
        $this->client = $client;
    }

    /**
     * @return array
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param array $response
     */
    public function setResponse(array $response)
    {
        $this->response = $response;
    }

    /**
     * @return array
     */
    public function getResponseHeaders()
    {
        return $this->responseHeaders;
    }

    /**
     * @param array $responseHeaders
     */
    public function setResponseHeaders(array $responseHeaders)
    {
        $this->responseHeaders = $responseHeaders;
    }
}