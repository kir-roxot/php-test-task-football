<?php

namespace Roxot\Factories;

use Roxot\Models\Game;
use Roxot\Services\ValidatorService;

class GameFactory
{
    /**
     * @param array $data
     * @return Game
     */
    public function createGame(array $data)
    {
        $startInfo = $data['startInfoEvent'];
        $gameLocation = $startInfo['stadium'];
        $this->validate($gameLocation);
        // todo json bug - county not country
        return new Game(
            $gameLocation['county'],
            $gameLocation['city'],
            $gameLocation['stadium'],
            $this->addTeams($startInfo)
        );
    }

    /**
     * @param array $data
     * @throws \Exception
     */
    private function validate(array $data)
    {
        ValidatorService::validateGame($data);
    }

    /**
     * @param array $teamData
     * @return array
     */
    private function addTeams(array $teamData)
    {
        $teams = [];
        for ($i = 1; $i <= 2; $i++) {
            $team = $teamData['team' . $i];
            $teams[$team['title']] = (new TeamFactory)->createTeam($team);
        }

        return $teams;
    }
}