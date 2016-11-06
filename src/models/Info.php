<?php

class Info
{
    public $time;
    public $description;

    public function __construct($time, $description)
    {
        $this->time = $time;
        $this->description = $description;
    }
}
