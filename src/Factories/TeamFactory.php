<?php

namespace Roxot\Factories;

use Roxot\Models\Team;
use Roxot\Models\Player;

class TeamFactory
{
    const TITLE = 'title';
    const COACH = 'coach';
    const COUNTRY = 'country';

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

    private function validate(array $data)
    {
        $keys = [self::TITLE, self::COACH, self::COUNTRY];
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new \Exception(
                    sprintf(
                        'Key "%s" not found in team data: "%s"',
                        $key,
                        implode(", ", array_keys($data))));
            }
        }
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