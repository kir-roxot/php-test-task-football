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

    public function increaseRedCard()
    {
        if ($this->redCards === 1) {
            return;
        }

        $this->redCards++;
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
    * @param int $time
    */
    public function setReplacementIn(int $time)
    {
        $this->startTime = $time;
    }

    /**
     * @param int $time
     */
    public function setReplacementOut(int $time)
    {
        $this->endTime = $time;
    }
}
