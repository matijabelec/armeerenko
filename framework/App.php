<?php
declare(strict_types=1);

namespace Framework;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

final class App
{
    public function run(?RequestInterface $request = null): ResponseInterface
    {
        $response = new Response(StatusCodeInterface::STATUS_NOT_FOUND);
        $response->getBody()->write('Route Not Found');

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
}
