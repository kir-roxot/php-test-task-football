<?php

namespace Roxot\Factories;

use Roxot\Models\Team;
use Roxot\Models\Player;
use Roxot\Services\ValidatorService;

class TeamFactory
{
    /**
     * @param array $data
     * @return Team
     */
    public function createTeam(array $data)
    {
        $this->validate($data);
        return new Team(
            $data['title'],
            $data['coach'],
            $data['country'],
            $this->addPlayers($data['players'], $data['startPlayerNumbers'])
        );
    }

    /**
     * @param array $data
     * @throws \Exception
     */
    private function validate(array $data)
    {
        ValidatorService::validateTeam($data);
    }

    /**
     * @param Player[] $players
     * @param array $startPlayerNumbers
     * @return array
     */
    private function addPlayers(array $players, array $startPlayerNumbers)
    {
        $data = [];
        foreach ($players as $player) {
            $data[$player['number']] = (new PlayerFactory())->createPlayer($player, $startPlayerNumbers);
        }

        return $data;
    }
}