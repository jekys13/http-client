<?php
/**
* Inreface for HTTP Client
*/
namespace Jekys\Http;

interface ClientInterface
{
    /**
     * Send request to the server
     *
     * @param string $method
     * @param string $url
     * @param mixed|null $params
     * @param array|null $headers
     *
     * @return string
     */
    public function sendRequest(string $method, string $url, $params = [], ?array $headers = [], bool $rawPost = false): string;
}
