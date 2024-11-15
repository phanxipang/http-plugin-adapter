<?php

declare(strict_types=1);

namespace Fansipan\HttpPluginAdapter\Tests;

use Fansipan\Contracts\ConnectorInterface;
use Fansipan\Traits\ConnectorTrait;
use Psr\Http\Client\ClientInterface;

final class GenericConnector implements ConnectorInterface, ClientInterface
{
    use ConnectorTrait;
}
