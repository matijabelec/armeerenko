<?php
declare(strict_types=1);

namespace Armeerenko\BattleSimulation;

use Armeerenko\BattleSimulation\Explosion\ExplosionImpact;

final class BattleSimulator
{
    private ExplosionImpact $explosionImpact;
    private SuccessfulAttemptSpecification $attemptWasMadeSuccessfullySpecification;
    private array $battleConfig;

    public function __construct(
        ExplosionImpact $explosionImpact,
        SuccessfulAttemptSpecification $attemptWasMadeSuccessfullySpecification,
        array $battleConfig
    ) {
        $this->explosionImpact = $explosionImpact;
        $this->attemptWasMadeSuccessfullySpecification = $attemptWasMadeSuccessfullySpecification;
        $this->battleConfig = $battleConfig;
    }

    public function simulate(int $army1Soldiers, int $army2Soldiers): Battle
    {
        $battle = Battle::start($army1Soldiers, $army2Soldiers);

        $army1Attack = $this->battleConfig['army1']['attack'];
        $army2Attack = $this->battleConfig['army2']['attack'];
        $explosionsRate = $this->battleConfig['explosions']['rate'];

        foreach (range(1, 20) as $day) {
            if ($this->attemptWasMadeSuccessfullySpecification->isSatisfiedBy($explosionsRate)) {
                $battle->randomExplosion($this->explosionImpact);

                if ($battle->isEnded()) {
                    return $battle;
                }
            }

            if ($this->attemptWasMadeSuccessfullySpecification->isSatisfiedBy($army1Attack)) {
                $battle->army1Attack();

                if ($battle->isEnded()) {
                    return $battle;
                }
            }

            if ($this->attemptWasMadeSuccessfullySpecification->isSatisfiedBy($army2Attack)) {
                $battle->army2Attack();

                if ($battle->isEnded()) {
                    return $battle;
                }
            }
        }

        $battle->end();

        return $battle;
    }
}
