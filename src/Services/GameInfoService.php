<?php

namespace Roxot\Services;

use Roxot\Factories\InfoFactory;
use Roxot\Models\Game;
use Roxot\Models\Player;

class GameInfoService
{
    /**
     * @param Game $game
     * @param array $gameData
     */
    public function addInfo(Game $game, array $gameData)
    {
        $info = [];
        foreach ($gameData as $data) {
            $info[] = (new InfoFactory())->createInfo($data);
            switch ($data['type']) {
                case "yellowCard":
                    $this->addYellowCard($game, $data['details'], $data['time']);
                    break;
                case "redCard":
                    $this->addRedCard($game, $data['details'], $data['time']);
                    break;
                case "goal":
                    $this->addGoal($game, $data['details']);
                    $this->addAssist($game, $data['details']);
                    break;
                case "replacePlayer":
                    $playerIn = $this->replacePlayerIn($game, $data['details'], $data['time']);
                    $playerOut = $this->replacePlayerOut($game, $data['details'], $data['time']);
                    $game->teams[$data['details']['team']]->setReplacement($playerOut, $playerIn, $data['time']);
                    break;
                default:
                    break;
            }

            if ($data['type'] !== "finishPeriod") {
                continue;
            }
            if ($data['time'] < 90) {
                continue;
            }

            $this->addEndPeriod($game, $data['time']);
        }

        $game->info = $info;
    }

    /**
     * @param Game $game
     * @param array $data
     * @param int $time
     */
    private function addYellowCard(Game $game, array $data, int $time)
    {
        $game->teams[$data['team']]->players[$data['playerNumber']]->increaseYellowCards();
        if ($game->teams[$data['team']]->players[$data['playerNumber']]->yellowCards === 2) {
            $game->teams[$data['team']]->players[$data['playerNumber']]->setEndTime($time);
        }
    }

    /**
     * @param Game $game
     * @param array $data
     * @param int $time
     */
    private function addRedCard(Game $game, array $data, int $time)
    {
        $game->teams[$data['team']]->players[$data['playerNumber']]->increaseRedCard();
        $game->teams[$data['team']]->players[$data['playerNumber']]->setEndTime($time);
    }

    /**
     * @param Game $game
     * @param array $data
     */
    private function addGoal(Game $game, array $data)
    {
        $game->teams[$data['team']]->increaseGoal();
        $game->teams[$data['team']]->players[$data['playerNumber']]->increaseGoal();

    }

    /**
     * @param Game $game
     * @param array $data
     */
    private function addAssist(Game $game, array $data)
    {
        if ($data['assistantNumber'] !== null) {
            $game->teams[$data['team']]->players[$data['playerNumber']]->increaseAssists();
        }
    }

    /**
     * @param Game $game
     * @param array $data
     * @param int $time
     * @return Player
     */
    private function replacePlayerIn(Game $game, array $data, int $time)
    {
        $player = $game->teams[$data['team']]->players[$data['inPlayerNumber']];
        $player->setReplacementIn($time);

        return $player;
    }

    /**
     * @param Game $game
     * @param array $data
     * @param int $time
     * @return Player
     */
    private function replacePlayerOut(Game $game, array $data, int $time)
    {
        $player = $game->teams[$data['team']]->players[$data['outPlayerNumber']];
        $player->setReplacementOut($time);

        return $player;
    }

    /**
     * @param Game $game
     * @param $time
     */
    private function addEndPeriod(Game $game, int $time)
    {
        foreach ($game->teams as $team) {
            foreach ($team->players as $player) {
                $player->setEndTime($time);
            }
        }
    }
}