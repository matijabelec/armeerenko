<?php
declare(strict_types=1);

namespace Armeerenko\BattleSimulation\Explosion;

use Armeerenko\Contract\RandomGenerator;

final class ExplosionImpact
{
    private RandomGenerator $randomGenerator;

    public function __construct(RandomGenerator $randomGenerator)
    {
        $this->randomGenerator = $randomGenerator;
    }

    public function calculate(int $army1Soldiers, int $army2Soldiers): ExplosionResult
    {
        $hits1 = $this->randomGenerator->randomNumber(0, $army1Soldiers);
        $hits2 = $this->randomGenerator->randomNumber(0, $army2Soldiers);

        return new ExplosionResult(
            $hits1 < $hits2 * 0.75 ? $hits1 : 0,
            $hits2 < $hits1 * 0.75 ? $hits2 : 0
        );
    }
}
