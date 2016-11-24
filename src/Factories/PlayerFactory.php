<?php

namespace Roxot\Factories;

use Roxot\Models\Player;

class PlayerFactory
{
    const NUMBER = 'number';
    const NAME = 'name';

    public function createPlayer(array $data, array $startPlayerNumbers)
    {
        $this->validate($data);
        $isStarted = in_array($data['number'], $startPlayerNumbers);

        return new Player($data['number'], $data['name'], $isStarted);
    }

    private function validate(array $data)
    {
        $keys = [self::NUMBER, self::NAME];
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new \Exception(
                    sprintf(
                        'Key "%s" not found in team data: "%2s"',
                        $key,
                        implode(", ", array_keys($data))));
            }
        }
    }
}