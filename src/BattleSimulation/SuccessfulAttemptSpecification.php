<?php
declare(strict_types=1);

namespace Armeerenko\BattleSimulation;

final class SuccessfulAttemptSpecification
{
    public function isSatisfiedBy(float $maxValue): bool
    {
        $attempt = random_int(0, 100) / 100.0;

        return $attempt < $maxValue;
    }
}
