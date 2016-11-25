<?php

namespace Roxot\Factories;

use Roxot\Models\Player;
use Roxot\Services\ValidatorService;

class PlayerFactory
{
    /**
     * @param array $data
     * @param array $startPlayerNumbers
     * @return Player
     */
    public function createPlayer(array $data, array $startPlayerNumbers)
    {
        $this->validate($data);
        $isStarted = in_array($data['number'], $startPlayerNumbers);

        return new Player($data['number'], $data['name'], $isStarted);
    }

    /**
     * @param array $data
     * @throws \Exception
     */
    private function validate(array $data)
    {
        ValidatorService::validatePlayer($data);
    }
}