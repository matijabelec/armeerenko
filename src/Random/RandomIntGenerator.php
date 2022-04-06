<?php
declare(strict_types=1);

namespace Armeerenko\Random;

use Armeerenko\Contract\RandomGenerator;

final class RandomIntGenerator implements RandomGenerator
{
    public function randomNumber(int $min, int $max): int
    {
        return random_int($min, $max);
    }
}
