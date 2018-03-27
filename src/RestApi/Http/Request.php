<?php
declare(strict_types=1);

namespace RestApi\Http;

use RestApi\ParameterBag;

/**
 * HTTP request.
 * Should be initialized with $_SERVER array and request body.
 * Request body (if exists) will be parsed from JSON to array and can be used as parameters for methods.
 *
 * Class Request
 * @package RestApi\Http
 */
final class Request
{

    /**
     * @var ParameterBag
     */
    private $server;

    /**
     * @var ParameterBag
     */
    private $input;

    /**
     * @var string|null
     */
    private $method;

    /**
     * @var string|null
     */
    private $requestUri;

    /**
     * Request constructor.
     * @param array $server $_SERVER parameters
     * @param string|null $content The raw body data
     */
    public function __construct(array $server, string $content = null)
    {
        $this->server = new ParameterBag($server);

        $this->input = new ParameterBag();
        if ($content) {
            $this->initInputFromJsonBody($content);
        }
    }

    /**
     * @param string $content
     */
    private function initInputFromJsonBody(string $content): void
    {
        $params = json_decode($content, true);
        if (\is_array($params)) {
            $this->input = new ParameterBag($params);
        }
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        if (null === $this->method) {
            $this->method = strtoupper($this->server->get('REQUEST_METHOD', 'GET'));
        }

        return $this->method;
    }

    /**
     * @return string
     */
    public function getRequestUri(): string
    {
        if (null === $this->requestUri) {
            $this->requestUri = $this->prepareRequestUri();
        }

        return $this->requestUri;
    }

    /**
     * @return string
     */
    private function prepareRequestUri(): string
    {
        $requestUri = $this->server->get('REQUEST_URI');
        if (empty($requestUri)) {
            $requestUri = '/';
        }

        return $requestUri;
    }

    /**
     * Get the data from the parsed request body
     *
     * @param string $key
     * @param null $default
     * @return mixed|null
     */
    public function input(string $key, $default = null)
    {
        return $this->input->get($key, $default);
    }

}
