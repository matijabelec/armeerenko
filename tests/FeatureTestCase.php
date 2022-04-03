<?php
declare(strict_types=1);

namespace Test;

use Framework\App;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Factory\RequestFactory;

abstract class FeatureTestCase extends TestCase
{
    private ?App $app = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app = $this->app();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->app = null;
    }

    protected function get(string $path, array $queryParameters = []): ResponseInterface
    {
        return $this->request('GET', $this->buildUri($path, $queryParameters));
    }

    protected function buildUri(string $path, array $queryParameters = []): string
    {
        return sprintf('%s%s',
            $path,
            [] === $queryParameters ? '' : sprintf('?%s', http_build_query($queryParameters))
        );
    }

    protected function request(string $method, string $uri): ResponseInterface
    {
        $factory = new RequestFactory();
        $request = $factory->createRequest($method, $uri);

        ob_start();
        $response = $this->app->run($request);
        ob_end_clean();

        return $response;
    }

    private function app(): App
    {
        return $this->app ?? require __DIR__ . '/../app/bootstrap.php';
    }
}
