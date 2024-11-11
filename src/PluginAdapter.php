<?php

declare(strict_types=1);

namespace Fansipan\HttpPluginAdapter;

use Http\Client\Common\Plugin;
use Http\Client\Common\PluginChain;
use Http\Client\Exception;
use Http\Client\Promise\HttpFulfilledPromise;
use Http\Client\Promise\HttpRejectedPromise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class PluginAdapter
{
    /**
     * @var iterable<Plugin>
     */
    private $plugins;

    /**
     * @param iterable<Plugin> $plugins
     */
    public function __construct(iterable $plugins)
    {
        $this->plugins = $plugins;
    }

    /**
     * @param  callable(RequestInterface): ResponseInterface $next
     */
    public function __invoke(RequestInterface $request, callable $next): ResponseInterface
    {
        $plugins = $this->plugins instanceof \Traversable ? \iterator_to_array($this->plugins) : (array) $this->plugins;

        $pluginChain = new PluginChain($plugins, static function (RequestInterface $request) use ($next) {
            try {
                return new HttpFulfilledPromise($next($request));
            } catch (Exception $exception) {
                return new HttpRejectedPromise($exception);
            }
        });

        return $pluginChain($request)->wait();
    }
}
