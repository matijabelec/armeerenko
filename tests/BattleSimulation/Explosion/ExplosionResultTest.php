<?php
declare(strict_types=1);

namespace Test\BattleSimulation\Explosion;

use Armeerenko\BattleSimulation\Explosion\ExplosionResult;
use Test\UnitTestCase;

final class ExplosionResultTest extends UnitTestCase
{
    /**
     * @dataProvider provideHitsAndResults
     */
    public function testCreateExplosionResult(int $hits1, int $hits2, bool $hasHits1, bool $hasHits2): void
    {
        $explosionResult = new ExplosionResult($hits1, $hits2);

        $this->assertEquals($hits1, $explosionResult->hits1());
        $this->assertEquals($hasHits1, $explosionResult->hasHits1());
        $this->assertEquals($hits2, $explosionResult->hits2());
        $this->assertEquals($hasHits2, $explosionResult->hasHits2());
    }

    public function provideHitsAndResults(): array
    {
        return [
            [10, 20, true, true],
            [0, 10, false, true],
            [20, 0, true, false],
            [0, 0, false, false],
        ];
    }
}
