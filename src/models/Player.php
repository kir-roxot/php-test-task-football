<?php

class Player
{
    /**
     * @var int $number
     */
    public $number;

    /**
     * @var string $name
     */
    public $name;

    /**
     * @var int $startTime
     */
    public $startTime = 0;

    /**
     * @var int $endTime
     */
    public $endTime = 0;

    /**
     * @var int $goals
     */
    public $goals = 0;

    /**
     * @var int $assists
     */
    public $assists = 0;

    /**
     * @var int $yellowCards
     */
    public $yellowCards = 0;

    /**
     * @var int $redCards
     */
    public $redCards = 0;

    /**
     * @var bool $isReserve
     */
    public $isReserve = true;

    /**
     * Player constructor.
     * @param $number
     * @param $name
     */
    public function __construct($number, $name)
    {
        $this->number = $number;
        $this->name = $name;
    }

    public function fulltime()
    {
        return $this->endTime - $this->startTime;
    }

    /**
     * @param int $endTime
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $this->endTime && !$this->isReserve ? $endTime : $this->endTime;
    }

    /**
     * @param int $time
     */
    public function setYellowCards($time)
    {
        if ($this->yellowCards + 1 > 2) {
            return;
        }

        $this->yellowCards += 1;
        if ($this->yellowCards === 2) {
            $this->setRedCards($time);
        }
    }

    /**
     * @param int $time
     */
    public function setRedCards($time)
    {
        $this->redCards = 1;
        $this->setEndTime($time);
    }
}
