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
     * @param array|null $params
     * @param array|null $headers
     *
     * @return string
     */
    public function sendRequest(string $method, string $url, ?array $params = [], ?array $headers = []): string;
}
