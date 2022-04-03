<?php
declare(strict_types=1);

namespace Test\Feature;

use PHPUnit\Framework\TestCase;
use Test\FeatureTestCase;

final class HomeActionTest extends FeatureTestCase
{
    public function testHomeActionReturnTitle(): void
    {
        $response = $this->get('/');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Armeerenko', (string) $response->getBody());
    }
}
