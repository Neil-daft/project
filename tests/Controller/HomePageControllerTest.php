<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageControllerTest extends WebTestCase
{
    /** @var KernelBrowser  */
    private static $client;

    public function setUp()
    {
        self::$client = static::createClient();
    }

    /**
     * @dataProvider provideNavbarLinks
     */
    public function testHomePageNavbarLinksAreWorking(string $link)
    {
        self::$client->request('GET', $link);

        $this->assertEquals(200, self::$client->getResponse()->getStatusCode(), sprintf('The %s navbar link is down', $link));
    }

    public function provideNavbarLinks(): array
    {
        return [
            ['/'],
            ['/login'],
            ['/register']
        ];
    }

    public function testLoginPageContainsTheLoginForm()
    {
        $crawler = self::$client->request('GET', '/');
        $link = $crawler->selectLink('Login')->link();
        $crawler = self::$client->click($link);
        $this->assertCount(1, $crawler->filter('input[type=email]'));
        $this->assertCount(1, $crawler->filter('input[type=password]'));
    }

    public function testFailedLoginRedirectsbackToLoginPageWithErrorMessage()
    {
        $crawler = self::$client->request('GET', '/');
        $link = $crawler->selectLink('Login')->link();
        $crawler = self::$client->click($link);
        self::$client->followRedirects();
        $form = $crawler->selectButton('Sign in')->form();
        $form['email'] = 'neil@gmail.com';
        $form['password'] = 'randompassword';
        $crawler = self::$client->submit($form);
        $this->assertContains('Email could not be found', $crawler->text('html', true));
    }
}
