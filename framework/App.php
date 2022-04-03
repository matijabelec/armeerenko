<?php
declare(strict_types=1);

namespace Framework;

use DI\Container;
use FastRoute\Dispatcher;
use Fig\Http\Message\StatusCodeInterface;
use Framework\Contract\Action;
use Framework\Contract\Routing\Router;
use Framework\Routing\FastRouteRouter;
use Framework\Routing\RouteDispatcher;
use Psr\Container\ContainerExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Response;

final class App
{
    private Container $container;
    private RouteDispatcher $router;

    public function __construct(?RouteDispatcher $router = null)
    {
        $this->container = new Container();
        $this->router = $router ?? new FastRouteRouter();
    }

    public function router(): Router
    {
        return $this->router;
    }

    public function run(?RequestInterface $request = null): ResponseInterface
    {
        if (null === $request) {
            $request = ServerRequestFactory::createFromGlobals();
        }

        $result = $this->router->dispatch($request->getMethod(), $request->getUri()->getPath());
        if (Dispatcher::FOUND === $result[0]) {
            [, $handlerId, $args] = $result;

            $handler = $this->handler($handlerId);
            $response = $handler($request, new Response(), $args);
        } else {
            $response = new Response(StatusCodeInterface::STATUS_NOT_FOUND);
            $response->getBody()->write('Route Not Found');
        }

        $this->emitResponse($response);

        return $response;
    }

    private function emitResponse(ResponseInterface $response): void
    {
        if (false === headers_sent()) {
            http_response_code($response->getStatusCode());

            foreach ($response->getHeaders() as $name => $values) {
                foreach ($values as $value) {
                    header(sprintf('%s: %s', $name, $value));
                }
            }

            header(sprintf(
                'HTTP/%s %s %s',
                $response->getProtocolVersion(),
                $response->getStatusCode(),
                $response->getReasonPhrase()
            ), true, $response->getStatusCode());
        }

        echo $response->getBody();
    }

    private function handler(string $handlerId): Action
    {
        $previousException = null;

        try {
            $handler = $this->container->get($handlerId);

            if ($handler instanceof Action) {
                return $handler;
            }
        } catch (ContainerExceptionInterface $exception) {
            $previousException = $exception;
        }

        throw new \LogicException('Compatible action handler is not found.', 900, $previousException);
    }
}
