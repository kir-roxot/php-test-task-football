<?php

namespace Roxot\Models;

class Player
{
    const PLAYER_IN_MODE = "player_in_mode";
    const PLAYER_OUT_MODE = "player_out_mode";

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

    public function __construct(int $number, string $name, bool $isStarted)
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
    public function setEndTime(int $endTime)
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

    /**
     * @param string $type
     * @param int $time
     */
    public function setReplacement(string $type, int $time)
    {
        if ($type === self::PLAYER_IN_MODE) {
            $this->startTime = $time;
        } else if ($type === self::PLAYER_OUT_MODE) {
            $this->endTime = $time;
        }
    }
}
