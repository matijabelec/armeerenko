<?php
declare(strict_types=1);

namespace Test\BattleSimulation;

use Armeerenko\BattleSimulation\SuccessfulAttemptSpecification;
use Test\UnitTestCase;

final class SuccessfulAttemptSpecificationTest extends UnitTestCase
{
    public function testMaxValuesForAttemptToBeNormalizedToRangeZeroToOne(): void
    {
        $specification = new SuccessfulAttemptSpecification();

        $this->assertFalse($specification->isSatisfiedBy(0));
        $this->assertTrue($specification->isSatisfiedBy(1.1));
    }
}
