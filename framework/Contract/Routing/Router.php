<?php

namespace Framework\Contract\Routing;

interface Router
{
    public function addRoute(string $method, string $route, string $handler): void;
}
