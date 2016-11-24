<?php

namespace Roxot\Models;

class Game
{
    /**
     * @var string $country
     */
    public $country;

    /**
     * @var string $city
     */
    public $city;

    /**
     * @var string $stadium
     */
    public $stadium;

    /**
     * @var Team[] $teams
     */
    public $teams;

    /**
     * @var Info[] $info
     */
    public $info = [];

    public function __construct(string $country, string $city, string $stadium, array $teams)
    {
        $this->country = $country;
        $this->city = $city;
        $this->stadium = $stadium;
        $this->teams = $teams;
    }

    /**
     * @param array $info
     */
    public function setInfo(array $info)
    {
        $this->info = $info;
    }
}

