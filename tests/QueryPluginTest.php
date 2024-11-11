<?php

declare(strict_types=1);

namespace Fansipan\HttpPluginAdapter\Tests;

use Fansipan\HttpPluginAdapter\PluginAdapter;
use Fansipan\Mock\MockClient;
use Fansipan\Mock\RequestMatcher;
use Fansipan\RequestMatcher\QueryParameterRequestMatcher;
use Http\Client\Common\Plugin\QueryDefaultsPlugin;
use PHPUnit\Framework\TestCase;

final class QueryPluginTest extends TestCase
{
    public function test_query_plugin(): void
    {
        $connector = (new GenericConnector())->withClient($client = new MockClient());
        $connector->middleware()->push(new PluginAdapter([
            new QueryDefaultsPlugin(['locale' => 'en']),
        ]));
        $connector->send(new DummyRequest('http://localhost'));

        $client->assertSent(new RequestMatcher(
            new QueryParameterRequestMatcher(['locale' => 'en'])
        ));
    }
}
