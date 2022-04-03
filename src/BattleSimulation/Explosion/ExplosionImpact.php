<?php
declare(strict_types=1);

namespace Armeerenko\BattleSimulation\Explosion;

final class ExplosionImpact
{
    public function calculate(int $army1Soldiers, int $army2Soldiers): ExplosionResult
    {
        $hits1 = random_int(0, $army1Soldiers);
        $hits2 = random_int(0, $army2Soldiers);

        return new ExplosionResult(
            $hits1 < $hits2 * 0.75 ? $hits1 : 0,
            $hits2 < $hits1 * 0.75 ? $hits2 : 0
        );
    }
}
