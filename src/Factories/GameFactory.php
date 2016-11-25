<?php

namespace Roxot\Factories;

use Roxot\Models\Game;

class GameFactory
{
    // todo county not country
    const COUNTRY = 'county';
    const CITY = 'city';
    const STADIUM = 'stadium';

    /**
     * @param array $data
     * @return Game
     */
    public function createGame(array $data)
    {
        $startInfo = $this->getStartInfo($data);
        $gameLocation = $startInfo['details']['stadium'];
        $this->validate($gameLocation);
        // todo json bug - county not country
        return new Game(
            $gameLocation['county'],
            $gameLocation['city'],
            $gameLocation['stadium'],
            $this->addTeams($startInfo['details'])
        );
    }

    /**
     * @param array $data
     * @throws \Exception
     */
    private function validate(array $data)
    {
        $keys = [self::COUNTRY, self::CITY, self::STADIUM];
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new \Exception(
                    sprintf(
                        'Key "%s" not found in game data: "%s"',
                        $key,
                        implode(", ", array_keys($data))));
            }
        }
    }

    /**
     * @param array $gameData
     * @return array
     * @throws \Exception
     */
    private function getStartInfo(array $gameData)
    {
        foreach ($gameData as $data) {
            if ($data['type'] !== "startPeriod") {
                continue;
            }
            if (empty($data['details'])) {
                continue;
            }

            return $data;
        }

        throw new \Exception('Start game information is empty');
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