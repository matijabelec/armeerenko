<?php
declare(strict_types=1);

namespace Test\BattleSimulation;

use Armeerenko\BattleSimulation\SuccessfulAttemptSpecification;
use Armeerenko\Contract\RandomGenerator;
use Test\UnitTestCase;

final class SuccessfulAttemptSpecificationTest extends UnitTestCase
{
    public function testMaxValuesForAttemptToBeNormalizedToRangeZeroToOne(): void
    {
        $randomGenerator = $this->prophesize(RandomGenerator::class);
        $randomGenerator->randomNumber(0, 100)->willReturn(12);

        $specification = new SuccessfulAttemptSpecification($randomGenerator->reveal());

        $this->assertFalse($specification->isSatisfiedBy(0));
        $this->assertTrue($specification->isSatisfiedBy(1.1));
    }

    public function testTrueForRandomValueLessThanPassedMaxValue(): void
    {
        $randomGenerator = $this->prophesize(RandomGenerator::class);
        $randomGenerator->randomNumber(0, 100)->shouldBeCalledOnce()->willReturn(50);

        $specification = new SuccessfulAttemptSpecification($randomGenerator->reveal());

        $this->assertTrue($specification->isSatisfiedBy(0.75));
    }

    public function testTrueForRandomValueGreaterThanPassedMaxValue(): void
    {
        $randomGenerator = $this->prophesize(RandomGenerator::class);
        $randomGenerator->randomNumber(0, 100)->shouldBeCalledOnce()->willReturn(25);

        $specification = new SuccessfulAttemptSpecification($randomGenerator->reveal());

        $this->assertFalse($specification->isSatisfiedBy(0.2));
    }
}
