<?php
declare(strict_types=1);

namespace Framework\Routing;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Framework\Contract\Routing\Router;
use function FastRoute\simpleDispatcher;

final class FastRouteRouter implements Router, RouteDispatcher
{
    private ?Dispatcher $dispatcher = null;
    private array $routes = [];

    public function addRoute(string $method, string $route, string $handler): void
    {
        if (null !== $this->dispatcher) {
            throw new \LogicException('Routing can not be changed after dispatching.');
        }

        $this->routes[] = [
            'method' => $method,
            'route' => $route,
            'handler' => $handler
        ];
    }

    public function dispatch(string $method, string $uri): array
    {
        if (null === $this->dispatcher) {
            $this->prepareDispatcher();
        }

        return $this->dispatcher->dispatch($method, $uri);
    }

    private function prepareDispatcher(): void
    {
        $routes = $this->routes;
        $this->dispatcher = simpleDispatcher(function (RouteCollector $r) use ($routes) {
            foreach ($routes as $route) {
                $r->addRoute($route['method'], $route['route'], $route['handler']);
            }
        });
    }
}
