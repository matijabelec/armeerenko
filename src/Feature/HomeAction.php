<?php
declare(strict_types=1);

namespace Armeerenko\Feature;

use Framework\Contract\Action;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class HomeAction implements Action
{
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $response->getBody()->write('Armeerenko');

        return $response;
    }
}
