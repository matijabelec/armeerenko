<?php
declare(strict_types=1);

namespace Test\Framework;

use Framework\App;
use Framework\Contract\Routing\Router;
use Framework\Routing\FastRouteRouter;
use Framework\Routing\RouteDispatcher;
use Test\UnitTestCase;

final class AppTest extends UnitTestCase
{
    public function testDefaultRouterWasCreated(): void
    {
        $app = new App();
        $this->assertInstanceOf(FastRouteRouter::class, $app->router());
    }

    public function testAssignedRouterReused(): void
    {
        $app = new App(new DummyRouter());
        $this->assertInstanceOf(DummyRouter::class, $app->router());
    }
}

final class DummyRouter implements Router, RouteDispatcher {
    public function addRoute(string $method, string $route, string $handler): void
    {
    }

    public function dispatch(string $method, string $uri): array
    {
        return [];
    }
}
