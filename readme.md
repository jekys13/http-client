# Simple HTTP Client via cURL

[![Build Status](https://travis-ci.org/jekys13/http-client.svg?branch=master)](https://travis-ci.org/jekys13/http-client)
[![Coverage Status](https://coveralls.io/repos/github/jekys13/http-client/badge.svg)](https://coveralls.io/github/jekys13/http-client)
[![Latest Stable Version](https://poser.pugx.org/jekys/http-client/v/stable)](https://packagist.org/packages/jekys/http-client)
[![License](https://poser.pugx.org/jekys/http-client/license)](https://packagist.org/packages/jekys/http-client)

## Install
```
composer require jekys/http-client
```

## Usage
```
require_once 'vendor/autoload.php'

$url = 'http://localhost';

$client = new Jekys\Http\Client\Curl();

//GET request with params
$client->get($url, ['foo' => 'bar']);

//POST request with params
$client->post($url, ['foo' => 'bar']);

//PUT request with params
$client->put($url, ['foo' => 'bar']);

//PATCH request with params
$client->patch($url, ['foo' => 'bar']);

//DELETE request with params
$client->delete($url, ['foo' => 'bar']);
```