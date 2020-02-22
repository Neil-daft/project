<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageControllerTest extends WebTestCase
{
    private static $client;

    public function setUp()
    {
        self::$client = static::createClient();
    }
    public function testShowHomePage()
    {
        self::$client->request('GET', '/');

        $this->assertEquals(200, self::$client->getResponse()->getStatusCode(), 'Home page is down');
    }

    public function testLoginPageIsWorking()
    {
        self::$client->request('GET', '/login');

        $this->assertSame(200, self::$client->getResponse()->getStatusCode(), 'Login Page is down');
    }
}
