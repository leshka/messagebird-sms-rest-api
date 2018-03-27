<?php
declare(strict_types=1);

namespace RestApi;

/**
 * Parameters storage class
 *
 * Class ParameterBag
 * @package RestApi
 */
class ParameterBag
{

    /**
     * @var array
     */
    private $data = [];

    /**
     * ParameterBag constructor.
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        foreach ($params as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        if (!array_key_exists($key, $this->data)) {
            return $default;
        }

        return $this->data[$key];
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

}
