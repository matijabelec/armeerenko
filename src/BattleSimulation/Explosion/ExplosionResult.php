<?php
declare(strict_types=1);

namespace Armeerenko\BattleSimulation\Explosion;

final class ExplosionResult
{
    private int $hits1;
    private int $hits2;

    public function __construct(int $hits1, int $hits2)
    {
        $this->hits1 = $hits1;
        $this->hits2 = $hits2;
    }

    public function hits1(): int
    {
        return $this->hits1;
    }

    public function hasHits1(): bool
    {
        return $this->hits1 > 0;
    }

    public function hits2(): int
    {
        return $this->hits2;
    }

    public function hasHits2(): bool
    {
        return $this->hits2 > 0;
    }
}
