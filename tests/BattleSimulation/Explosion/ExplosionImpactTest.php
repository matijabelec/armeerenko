<?php
declare(strict_types=1);

namespace Test\BattleSimulation\Explosion;

use Armeerenko\BattleSimulation\Explosion\ExplosionImpact;
use Armeerenko\Contract\RandomGenerator;
use Test\UnitTestCase;

final class ExplosionImpactTest extends UnitTestCase
{
    public function testReturnZeroZero(): void
    {
        $randomGenerator = $this->prophesize(RandomGenerator::class);
        $randomGenerator->randomNumber(0, 0)->willReturn(0);

        $explosionImpact = new ExplosionImpact($randomGenerator->reveal());
        $result = $explosionImpact->calculate(0, 0);

        $this->assertEquals(0, $result->hits1());
        $this->assertEquals(false, $result->hasHits1());
        $this->assertEquals(0, $result->hits2());
        $this->assertEquals(false, $result->hasHits2());
    }
}
