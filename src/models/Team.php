<?php

namespace Roxot\Models;

class Team
{
    /**
     * @var string $title
     */
    public $title;

    /**
     * @var string $coach
     */
    public $coach;

    /**
     * @var string $country
     */
    public $country;

    /**
     * @var Player[] $players
     */
    public $players;

    /**
     * @var int $goals
     */
    public $goals;

    /**
     * @var array $replacements
     */
    public $replacements;

    /**
     * Team constructor.
     * @param $title
     * @param $coach
     * @param $country
     * @param array $players
     */
    public function __construct($title, $coach, $country, array $players)
    {
        $this->title = $title;
        $this->coach = $coach;
        $this->country = $country;
        $this->players = $players;
    }

    public function increaseGoal()
    {
        $this->goals++;
    }

    /**
     * @param $playerOut
     * @param $playerIn
     * @param $time
     */
    public function setReplacement($playerOut, $playerIn, $time)
    {
        $this->replacements[] = ["out" => $playerOut, "in" => $playerIn, 'time' => $time];
    }
}
