<?php
/**
 * @coversDefaultClass Jekys\Http\Client\Curl
 */
use PHPUnit\Framework\TestCase;
use Jekys\Http\Client\Curl;

class CurlHttpClientTest extends TestCase
{
    /**
    * @var int
    */
    private static $process;

    /**
     * @var Curl
     */
    private static $client;

    /**
     * @var string
     */
    private static $address = 'localhost:8080';

    /**
    * @var string
    */
    private static $url;

    /**
     * @var array
     */
    private $methods = [
        'GET',
        'POST',
        'PUT',
        'PATCH',
        'DELETE'
    ];

    /**
     * @var array
     */
    private $testParams = [
        'foo' => 'bar',
        'bar' => 'baz'
    ];

    /**
     * Start local http server before tests
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        $path = dirname(__DIR__).'/http-server/';
        $command =  'php -S '.self::$address.' -t '.$path.' > /dev/null 2>&1 & echo $!; ';
        self::$process= exec($command);

        usleep(100000); //wait for server to get going*/

        self::$url = 'http://'.self::$address;
        self::$client = new Curl();
    }

    /**
     * @covers ::setOptions
     *
     * @return void
     */
    public function testSetOptions()
    {
        $this->assertTrue(self::$client->setOptions([
            CURLOPT_RETURNTRANSFER => true,
        ]));
    }

    /**
     * Check if RuntimeException is thrown then wrong http method was called
     *
     * @covers ::__call()
     *
     * @return void
     */
    public function testRuntimeException(): void
    {
        $this->expectException(\RuntimeException::class);

        self::$client->test();
    }

    /**
     * Check if InvalidArgumentException is thrown then url hadn't passed
     *
     * @covers ::__call()
     *
     * @return void
     */
    public function testInvalidArgumentException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        self::$client->get();
    }

    /**
     * Check if client sended correct method and params
     *
     * @covers ::sendRequest
     *
     * @return void
     */
    public function testSendRequest()
    {
        foreach ($this->methods as $method) {
            $response = self::$client->sendRequest(
                $method,
                self::$url,
                $this->testParams
            );

            $response = json_decode($response, true);

            $this->assertEquals($method, $response['method']);
            $this->assertEquals($this->testParams, $response['params']);
        }
    }

    /**
     * Check shortcuts for methods
     *
     * @covers ::__call()
     *
     * @return void
     */
    public function testShortucts(): void
    {
        foreach ($this->methods as $method) {
            $response = self::$client->{$method}(
                self::$url,
                $this->testParams,
                [
                    'User-Agent: phpUnit'
                ]
            );

            $response = json_decode($response, true);

            $this->assertEquals($method, $response['method']);
            $this->assertEquals($this->testParams, $response['params']);
        }
    }

    /**
     * Kill local http server process after tests
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        exec('kill '.self::$process);
    }
}
