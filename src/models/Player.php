<?php

class Player
{
    public $number;
    public $name;
    public $start_time;
    public $end_time;
    public $goals;
    public $assists;
    public $red_cards;
    public $yellow_cards;
    public $is_reserve;

    public function __construct($number, $name)
    {
        $this->number = $number;
        $this->fullname = $name;
    }

    public function fulltime()
    {
        return $this->end_time - $this->start_time;
    }
}
