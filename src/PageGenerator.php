<?php

namespace Roxot;

use Roxot\Models\Game;
use Roxot\Models\Team;
use Roxot\Models\Player;
use Roxot\Models\Info;

class PageGenerator
{
    const YELLOW_CARD = "yellow_card";
    const RED_CARD = "red_card";
    const PLAYER_IN = "player_in";
    const PLAYER_OUT = "player_out";

    private $scanPath;
    private $resultPath;

    public function __construct($scanPath, $resultPath)
    {
        $this->scanPath = $scanPath;
        $this->resultPath = $resultPath;
    }

    public function generate()
    {
        $filesNames = $this->scan();

        foreach ($filesNames as $fileName) {
            $file = file_get_contents($this->scanPath . "/" . $fileName);
            $obj = json_decode($file);

            $game = $this->createGame($obj);
            if (is_null($game)) {
                continue;
            }

            $this->addInfo($game, $obj);
            $this->savePage($fileName, $game);
        }
    }

    /**
     * @return array $filesNames
     * @throws \Exception
     */
    private function scan()
    {
        $filesNames = scandir($this->scanPath);
        $filesNames = array_diff($filesNames, array(".", ".."));
        if (!count($filesNames)) {
            throw new \Exception("Files not found.");
        }

        return $filesNames;
    }

    /**
     * @param $fileName
     * @param Game $game
     */
    private function savePage($fileName, Game $game)
    {
        ob_start();
        // template used Game object
        require_once "templates/game.php";
        $content = ob_get_contents();
        ob_clean();

        $nameParts = explode(".", $fileName);
        $newFile = fopen($this->resultPath . "/{$nameParts[0]}.html", "w");
        fwrite($newFile, $content);
        fclose($newFile);
    }

    /**
     * @param $obj
     * @return Game|null
     */
    private function createGame($obj)
    {
        $game = null;
        foreach ($obj as $o) {
            if ($o->type === "startPeriod" && !empty($o->details)) {
                $gameLocation = $o->details->stadium;

                // json bug - county not country
                $game = new Game(
                    $gameLocation->county,
                    $gameLocation->city,
                    $gameLocation->stadium,
                    $this->addTeams($o->details)
                );
            }
        }

        return $game;
    }

    /**
     * @param $data
     * @return array
     */
    private function addTeams($data)
    {
        $teams = [];
        for ($i = 1; $i <= 2; $i++) {
            $team = $data->{"team" . $i};
            $teams[$team->title] = new Team(
                $team->title,
                $team->coach,
                $team->country,
                $this->addPlayers($team->players, $team->startPlayerNumbers)
            );
        }

        return $teams;
    }

    /**
     * @param Player[] $players
     * @param $startPlayerNumbers
     * @return array
     */
    private function addPlayers($players, $startPlayerNumbers)
    {
        $data = [];
        foreach ($players as $player) {
            $isStarted = in_array($player->number, $startPlayerNumbers);
            $data[$player->number] = new Player($player->number, $player->name, $isStarted);
        }

        return $data;
    }

    /**
     * @param Game $game
     * @param $obj
     */
    private function addInfo(Game $game, $obj)
    {
        $info = [];
        foreach ($obj as $o) {
            $info[] = new Info($o->time, $o->description, $o->type);
            switch ($o->type) {
                case "yellowCard":
                    $this->addCard($game, $o->details, $o->time, self::YELLOW_CARD);
                    break;
                case "redCard":
                    $this->addCard($game, $o->details, $o->time, self::RED_CARD);
                    break;
                case "goal":
                    $this->addGoal($game, $o->details);
                    $this->addAssist($game, $o->details);
                    break;
                case "replacePlayer":
                    $this->addReplacement($game, $o->details, $o->time);
                    break;
                default:
                    break;
            }

            if ($o->type === "finishPeriod" && $o->time >= 90) {
                $this->addEndPeriod($game, $o->time);
            }
        }

        $game->info = $info;
    }

    /**
     * @param Game $game
     * @param $time
     */
    private function addEndPeriod(Game $game, $time)
    {
        foreach ($game->teams as $team) {
            foreach ($team->players as $player) {
                $player->setEndTime($time);
            }
        }
    }

    /**
     * @param Game $game
     * @param $data
     * @param $time
     * @param $type
     */
    private function addCard(Game $game, $data, $time, $type)
    {
        if ($type === self::YELLOW_CARD) {
            $game->teams[$data->team]->players[$data->playerNumber]->increaseYellowCards();
            if ($game->teams[$data->team]->players[$data->playerNumber] === 2) {
                $game->teams[$data->team]->players[$data->playerNumber]->setEndTime($time);
            }
        } else if ($type === self::RED_CARD) {
            $game->teams[$data->team]->players[$data->playerNumber]->setRedCard();
            $game->teams[$data->team]->players[$data->playerNumber]->setEndTime($time);
        }
    }

    /**
     * @param Game $game
     * @param $data
     */
    private function addGoal(Game $game, $data)
    {
        $game->teams[$data->team]->increaseGoal();
        $game->teams[$data->team]->players[$data->playerNumber]->increaseGoal();

    }

    /**
     * @param Game $game
     * @param $data
     */
    private function addAssist(Game $game, $data)
    {
        if ($data->assistantNumber !== null) {
            $game->teams[$data->team]->players[$data->playerNumber]->increaseAssists();
        }
    }

    /**
     * @param Game $game
     * @param $data
     * @param $time
     */
    private function addReplacement(Game $game, $data, $time)
    {
        $playerOut = $game->teams[$data->team]->players[$data->outPlayerNumber];
        $playerIn = $game->teams[$data->team]->players[$data->inPlayerNumber];
        $playerOut->setReplacement(self::PLAYER_OUT, $time);
        $playerIn->setReplacement(self::PLAYER_IN, $time);
        $game->teams[$data->team]->setReplacement($playerOut, $playerIn, $time);
    }
}
