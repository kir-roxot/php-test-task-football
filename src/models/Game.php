<?php

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

    /**
     * Game constructor.
     * @param $country
     * @param $city
     * @param $stadium
     * @param array $teams
     */
    public function __construct($country, $city, $stadium, array $teams)
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

