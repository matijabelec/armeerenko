<?php

namespace Framework\Routing;

interface RouteDispatcher
{
    public function dispatch(string $method, string $uri): array;
}
