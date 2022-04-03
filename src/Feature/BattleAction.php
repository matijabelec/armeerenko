<?php
declare(strict_types=1);

namespace Armeerenko\Feature;

use Armeerenko\BattleSimulation\Battle;
use Armeerenko\BattleSimulation\BattleSimulator;
use Framework\Contract\Action;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class BattleAction implements Action
{
    private BattleSimulator $battleSimulator;

    public function __construct(BattleSimulator $battleSimulator)
    {
        $this->battleSimulator = $battleSimulator;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $query = $request->getQueryParams();

        $army1 = $this->intOrFail($query, 'army1');
        $army2 = $this->intOrFail($query, 'army2');

        if (null === $army1 || null === $army2) {
            $response->getBody()->write('Missing or invalid "army1" and "army2" query parameters.');
            return $response->withStatus(422);
        }

        $battle = $this->battleSimulator->simulate($army1, $army2);

        $response->getBody()->write(sprintf('<p>Winner is <b>army %d</b>.</p>', $battle->winnerArmyId()));
        $this->createEventsList($battle, $response);

        return $response;
    }

    private function intOrFail(array $query, string $key): ?int
    {
        if (false === isset($query[$key])) {
            return null;
        }

        $army = (int) $query[$key];
        if ($query[$key] !== (string) $army) {
            return null;
        }

        return $army;
    }

    private function createEventsList(Battle $battle, ResponseInterface $response): void
    {
        $events = $battle->pullEvents();

        $response->getBody()->write('<ol>');
        foreach ($events as $event) {
            $response->getBody()->write(sprintf('<li>%s</li>', $event->summary()));
        }
        $response->getBody()->write('</ol>');
    }
}
