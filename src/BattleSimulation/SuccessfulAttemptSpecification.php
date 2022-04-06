<?php
declare(strict_types=1);

namespace Armeerenko\BattleSimulation;

use Armeerenko\Contract\RandomGenerator;

final class SuccessfulAttemptSpecification
{
    private RandomGenerator $randomGenerator;

    public function __construct(RandomGenerator $randomGenerator)
    {
        $this->randomGenerator = $randomGenerator;
    }

    public function isSatisfiedBy(float $maxValue): bool
    {
        $attempt = $this->randomGenerator->randomNumber(0, 100) / 100.0;

        return $attempt < $maxValue;
    }
}
