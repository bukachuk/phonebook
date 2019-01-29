<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;
use GuzzleHttp\Client;

class PhoneBookTest extends PHPUnit_Framework_TestCase
{
    const SERVER = '127.0.0.1:8080';
    const SUCCESS_STATUS = 'success';
    const ERROR_STATUS = 'error';
    const URI = 'phonebook';

    private $client;
    private static $process;

    public static function setUpBeforeClass()
    {
        self::$process = new Process("php -S " . self::SERVER . " -t .");
        self::$process->start();
        sleep(1);
    }

    public function setUp()
    {
        $this->client = new Client(['base_uri' => 'http://' . self::SERVER . '/']);
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreate($form, $status)
    {
        $result = $this->makeAPIRequest('POST', self::URI, ['form_params' => $form]);

        $this->assertArrayHasKey('status', $result);
        $this->assertSame($result['status'], $status);
    }

    public function testPhonebook()
    {
        $result = $this->makeAPIRequest('GET', self::URI);

        $this->assertArrayHasKey('result', $result);
        $this->assertGreaterThan(0, sizeof($result['result']));
    }

    public function testOnePhonebook()
    {
        $result = $this->makeAPIRequest('GET', self::URI);

        $this->assertArrayHasKey('result', $result);
        $this->assertGreaterThan(0, sizeof($result['result']));

        $phonebook = array_shift($result['result']);
        $result = $this->makeAPIRequest('GET', self::URI . '/' . $phonebook['id']);

        $this->assertSame(1, sizeof($result['result']));
        $phonebookFiltered = array_shift($result['result']);
        $this->assertSame($phonebookFiltered, $phonebook);
    }

    public function testDeletePhonebook(){
        $result = $this->makeAPIRequest('GET', self::URI);

        $this->assertArrayHasKey('result', $result);
        $this->assertGreaterThan(0, sizeof($result['result']));

        $phonebook = array_shift($result['result']);

        $result = $this->makeAPIRequest('DELETE', self::URI, ['form_params' => ['id' => $phonebook['id']]]);
        $this->assertArrayHasKey('result', $result);
        $this->assertSame($result['status'], self::SUCCESS_STATUS);

        $result = $this->makeAPIRequest('GET', self::URI . '/' . $phonebook['id']);
        $this->assertSame(0, sizeof($result['result']));

        $result = $this->makeAPIRequest('DELETE', self::URI, ['form_params' => ['id' => 0]]);
        $this->assertSame($result['status'], self::ERROR_STATUS);
    }

    public function testUpdatePhonebook()
    {
        $result = $this->makeAPIRequest('GET', self::URI);

        $this->assertArrayHasKey('result', $result);
        $this->assertGreaterThan(0, sizeof($result['result']));

        $phonebook = array_shift($result['result']);

        $result = $this->makeAPIRequest(
            'PUT',
            self::URI,
            ['form_params' => ['id' => $phonebook['id'], 'firstName' => 'firstName2', 'lastName' => 'lastName2', 'phone' => '+79083125328', 'countryCode' => 'RU', 'timeZone' => 'Europe/Moscow']]
        );

        $this->assertArrayHasKey('status', $result);
        $this->assertSame($result['status'], self::SUCCESS_STATUS);
    }

    public function createProvider()
    {
        return [
            [['firstName' => 'firstName1', 'LastName' => 'LastName', 'phone' => '+79083125328', 'countryCode' => 'RU', 'timeZone' => 'Europe/Moscow'], self::SUCCESS_STATUS],
            [['firstName' => 'firstName2', 'phone' => '+79083125328', 'countryCode' => 'RU', 'timeZone' => 'Europe/Moscow'], self::SUCCESS_STATUS],
            [['firstName' => 'firstName3', 'phone' => '79083125328', 'countryCode' => 'RU', 'timeZone' => 'Europe/Moscow'], self::ERROR_STATUS],
            [['firstName' => 'firstName4', 'phone' => '', 'countryCode' => 'RU', 'timeZone' => 'Europe/Moscow'], self::ERROR_STATUS],
            [['firstName' => '', 'phone' => '+79083125328', 'countryCode' => 'RU', 'timeZone' => 'Europe/Moscow'], self::ERROR_STATUS],
            [['firstName' => 'firstName5', 'phone' => '+79083125328', 'countryCode' => 'RUR', 'timeZone' => 'Europe/Moscow'], self::ERROR_STATUS],
            [['firstName' => 'firstName6', 'phone' => '+79083125328', 'countryCode' => 'RU', 'timeZone' => 'Europe/Europe'], self::ERROR_STATUS],
            [['firstName' => 'firstName7', 'phone' => '+79083125328'], self::SUCCESS_STATUS],
        ];
    }

    public function tearDown() {
        $this->client = null;
    }

    public static function tearDownAfterClass()
    {
        self::$process->stop();
    }

    private function makeAPIRequest(string $method, string $uri, $data = [])
    {
        $response = $this->client->request($method, $uri, $data);
        $result = json_decode($response->getBody()->getContents(), true);

        return $result;
    }
}