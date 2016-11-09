<?php

class Info
{
    /**
     * @var int $time
     */
    public $time = 0;

    /**
     * @var string $description
     */
    public $description = '';

    /**
     * @var string $type
     */
    public $type = '';

    /**
     * Info constructor.
     * @param $time
     * @param $description
     * @param $type
     */
    public function __construct($time, $description, $type)
    {
        $this->time = $time;
        $this->description = $description;
        $this->type = $type;
    }
}
