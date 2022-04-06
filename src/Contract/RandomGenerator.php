<?php

namespace Armeerenko\Contract;

interface RandomGenerator
{
    public function randomNumber(int $min, int $max): int;
}
