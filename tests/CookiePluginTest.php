<?php

declare(strict_types=1);

namespace Fansipan\HttpPluginAdapter\Tests;

use Fansipan\HttpPluginAdapter\PluginAdapter;
use Fansipan\Mock\MockClient;
use Fansipan\Mock\MockResponse;
use Http\Client\Common\Plugin\CookiePlugin;
use Http\Message\Cookie;
use Http\Message\CookieJar;
use PHPUnit\Framework\TestCase;

final class CookiePluginTest extends TestCase
{
    public function test_cookie_plugin(): void
    {
        $response = MockResponse::create('')
            ->withHeader('Set-Cookie', 'foo=bar; HttpOnly');

        $connector = (new GenericConnector())->withClient($client = new MockClient($response));
        $connector->middleware()->push(new PluginAdapter([
            new CookiePlugin($cookies = new CookieJar()),
        ]));
        $connector->send(new DummyRequest('http://localhost'));

        $this->assertCount(1, $cookies);

        $cookie = $cookies->getCookies()[0];

        $this->assertInstanceOf(Cookie::class, $cookie);
        $this->assertSame('foo', $cookie->getName());
        $this->assertSame('bar', $cookie->getValue());
    }
}
