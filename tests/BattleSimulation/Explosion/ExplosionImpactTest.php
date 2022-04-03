<?php
declare(strict_types=1);

namespace Test\BattleSimulation\Explosion;

use Armeerenko\BattleSimulation\Explosion\ExplosionImpact;
use Test\UnitTestCase;

final class ExplosionImpactTest extends UnitTestCase
{
    public function testReturnZeroZero(): void
    {
        $explosionImpact = new ExplosionImpact();
        $result = $explosionImpact->calculate(0, 0);

        $this->assertEquals(0, $result->hits1());
        $this->assertEquals(false, $result->hasHits1());
        $this->assertEquals(0, $result->hits2());
        $this->assertEquals(false, $result->hasHits2());
    }
}
