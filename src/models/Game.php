<?php

class Game
{
    public $country;
    public $city;
    public $stadium;
    public $teams;
    public $info;

    public function __construct($country, $city, $stadium)
    {
        $this->country = $country;
        $this->city = $city;
        $this->stadium = $stadium;
    }
}

