<?php

namespace Roxot\Models;

class Player
{
    const PLAYER_IN = "player_in";
    const PLAYER_OUT = "player_out";

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
     * @var bool $isStarted
     */
    public $isStarted = false;

    /**
     * Player constructor.
     * @param $number
     * @param $name
     * @param $isStarted
     */
    public function __construct($number, $name, $isStarted)
    {
        $this->number = $number;
        $this->name = $name;
        $this->isStarted = $isStarted;
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
        if ($this->startTime === 0 && $this->endTime === 0 && $this->isStarted || $this->startTime > 0) {
            $this->endTime = $endTime;
        }
    }

    public function increaseYellowCards()
    {
        if ($this->yellowCards + 1 > 2) {
            return;
        }

        $this->yellowCards++;
    }

    public function setRedCard()
    {
        $this->redCards = 1;
    }

    public function increaseGoal()
    {
        $this->goals++;
    }

    public function increaseAssists()
    {
        $this->assists++;
    }

    public function setReplacement($type, $time)
    {
        if ($type === self::PLAYER_IN) {
            $this->startTime = $time;
        } else if ($type === self::PLAYER_OUT) {
            $this->endTime = $time;
        }
    }
}
