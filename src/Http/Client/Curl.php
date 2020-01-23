<?php
/**
* ClientInterface implementation for cURL lib usage
*/
namespace Jekys\Http\Client;

use Jekys\Http\ClientInterface;

class Curl implements ClientInterface
{
    /**
     * @var array - Avaliable methods
     */
    protected $methods = [
        'GET',
        'POST',
        'PUT',
        'PATCH',
        'DELETE'
    ];

    /**
     * @var array - List of cURL options
     */
    protected $curlOptions = [
        CURLOPT_RETURNTRANSFER => true,
    ];

    /**
     * @var array - List of default requests headers
     */
    protected $defaultHeaders = [];

    /**
     * @var resource - cURL handler
     */
    private $curl;

    /**
     * Class object constructor
     * Initiates cURL handler
     *
     * @return void
     */
    public function __construct()
    {
        $this->curl = curl_init();

        if (!empty($this->curlOptions)) {
            $this->setOptions($this->curlOptions);
        }
    }

    /**
     * Set cURL options
     *
     * @param array $options
     *
     * @return bool
     */
    public function setOptions(array $options): bool
    {
        return curl_setopt_array($this->curl, $options);
    }

    /**
     * Some magic for requests shortcusts like $client->get()
     *
     * @param string $name
     * @param array $arguments
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     *
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        if (!in_array(strtoupper($name), $this->methods)) {
            throw new \RuntimeException('Unknown HTTP method');
        }

        if (empty($arguments)) {
            throw new \InvalidArgumentException('url param is empty');
        }

        $url = $arguments[0];
        $params = [];
        $headers = [];
        $rawData = false;

        if (!empty($arguments[1])) {
            $params = $arguments[1];
        }

        if (!empty($arguments[2])) {
            $headers = $arguments[2];
        }

        if (!empty($arguments[3])) {
            $rawData = $arguments[3];
        }

        return $this->sendRequest($name, $url, $params, $headers, $rawData);
    }

    /**
     * Send request to the server
     *
     * @param string $method
     * @param string $url
     * @param mixed|null $params
     * @param array|null $headers
     * @param bool $rawData
     * @return string
     */
    public function sendRequest(string $method, string $url, $params = [], ?array $headers = [], bool $rawData = false): string
    {
        $preparedParams = [];

        if (!empty($params) && is_array($params)) {
            $preparedParams = http_build_query($params);
        }

        switch (strtoupper($method)) {
            case 'GET':
                curl_setopt($this->curl, CURLOPT_POST, 0);
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, []);
                $url .= '?'.$preparedParams;

            break;

            case 'POST':
                curl_setopt($this->curl, CURLOPT_POST, 1);

                if ($rawData) {
                    curl_setopt($this->curl, CURLOPT_POSTFIELDS, $params);
                } else {
                    curl_setopt($this->curl, CURLOPT_POSTFIELDS, $preparedParams);
                }

            break;

            default:
                curl_setopt($this->curl, CURLOPT_POST, 0);

                if ($rawData) {
                    curl_setopt($this->curl, CURLOPT_POSTFIELDS, $params);
                } else {
                    curl_setopt($this->curl, CURLOPT_POSTFIELDS, $preparedParams);
                }

            break;
        }

        curl_setopt(
            $this->curl,
            CURLOPT_HTTPHEADER,
            array_merge($this->defaultHeaders, $headers)
        );

        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($this->curl, CURLOPT_URL, $url);

        $response = curl_exec($this->curl);

        return $response;
    }

    /**
     * Class object destructor
     *
     * @codeCoverageIgnore
     *
     * @return void
     */
    public function __destruct()
    {
        curl_close($this->curl);
    }
}
