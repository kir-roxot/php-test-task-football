<?php

class Team
{
    /**
     * @var string $title
     */
    public $title;

    /**
     * @var string $coach
     */
    public $coach;

    /**
     * @var string $country
     */
    public $country;

    /**
     * @var Player[] $players
     */
    public $players;

    /**
     * Team constructor.
     * @param $title
     * @param $coach
     * @param $country
     * @param $players
     */
    public function __construct($title, $coach, $country, $players)
    {
        $this->title = $title;
        $this->coach = $coach;
        $this->country = $country;
        $this->players = $players;
    }
}
