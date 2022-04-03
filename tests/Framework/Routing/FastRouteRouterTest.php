<?php
declare(strict_types=1);

namespace Test\Framework\Routing;

use FastRoute\Dispatcher;
use Framework\Routing\FastRouteRouter;
use Test\UnitTestCase;

final class FastRouteRouterTest extends UnitTestCase
{
    public function testRouteNotFound(): void
    {
        $fastRouteRouter = new FastRouteRouter();

        $this->assertEquals(
            [Dispatcher::NOT_FOUND],
            $fastRouteRouter->dispatch('GET', '/route-name')
        );
    }

    public function testRouteFound(): void
    {
        $fastRouteRouter = new FastRouteRouter();
        $fastRouteRouter->addRoute('GET', '/route-name', 'route_handler');

        $this->assertEquals(
            [Dispatcher::FOUND, 'route_handler', []],
            $fastRouteRouter->dispatch('GET', '/route-name')
        );
    }
}
