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

    public function __construct(string $title, string $coach, string $country, array $players)
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
     * @param Player $playerOut
     * @param Player $playerIn
     * @param int $time
     */
    public function setReplacement(Player $playerOut, Player $playerIn, int $time)
    {
        $this->replacements[] = ["out" => $playerOut, "in" => $playerIn, 'time' => $time];
    }
}
