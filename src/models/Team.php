<?php

class Team
{
    public $title;
    public $coach;
    public $country;
    public $players;

    public function __construct($title, $coach, $country, $players)
    {
        $this->title = $title;
        $this->coach = $coach;
        $this->country = $country;
        $this->players = $players;
    }
}
