<?php

class Team
{
    public $id;
    public $title;
    public $coach;
    public $country;
    public $players;

    public function __construct(array $players)
    {
        $this->players = $players;
    }
}
