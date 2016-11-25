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
        foreach ($gameData['infoEvent'] as $data) {
            $info[] = (new InfoFactory())->createInfo($data);
        }

        foreach ($gameData['yellowCardEvent'] as $data) {
            $this->addYellowCard($game, $data);
        }
        foreach ($gameData['redCardEvent'] as $data) {
            $this->addRedCard($game, $data);
        }
        foreach ($gameData['goalEvent'] as $data) {
            $this->addGoal($game, $data);
        }
        foreach ($gameData['replacementEvent'] as $data) {
            $this->addReplacement($game, $data);
        }

        $this->addEndPeriod($game, $gameData['periodEvent']);

        $game->info = $info;
    }

    /**
     * @param Game $game
     * @param array $data
     */
    private function addYellowCard(Game $game, array $data)
    {
        $game->teams[$data['team']]->players[$data['playerNumber']]->increaseYellowCards();
        if ($game->teams[$data['team']]->players[$data['playerNumber']]->yellowCards === 2) {
            $game->teams[$data['team']]->players[$data['playerNumber']]->setEndTime($data['time']);
        }
    }

    /**
     * @param Game $game
     * @param array $data
     */
    private function addRedCard(Game $game, array $data)
    {
        $game->teams[$data['team']]->players[$data['playerNumber']]->increaseRedCard();
        $game->teams[$data['team']]->players[$data['playerNumber']]->setEndTime($data['time']);
    }

    /**
     * @param Game $game
     * @param array $data
     */
    private function addGoal(Game $game, array $data)
    {
        $game->teams[$data['team']]->increaseGoal();
        $game->teams[$data['team']]->players[$data['playerNumber']]->increaseGoal();
        $this->addAssist($game, $data);

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
     */
    private function addReplacement(Game $game, array $data)
    {
        $playerIn = $this->replacePlayerIn($game, $data);
        $playerOut = $this->replacePlayerOut($game, $data);
        $game->teams[$data['team']]->setReplacement($playerOut, $playerIn, $data['time']);
    }

    /**
     * @param Game $game
     * @param array $data
     * @return Player
     */
    private function replacePlayerIn(Game $game, array $data)
    {
        $player = $game->teams[$data['team']]->players[$data['inPlayerNumber']];
        $player->setReplacementIn($data['time']);

        return $player;
    }

    /**
     * @param Game $game
     * @param array $data
     * @return Player
     */
    private function replacePlayerOut(Game $game, array $data)
    {
        $player = $game->teams[$data['team']]->players[$data['outPlayerNumber']];
        $player->setReplacementOut($data['time']);

        return $player;
    }

    /**
     * @param Game $game
     * @param array $data
     */
    private function addEndPeriod(Game $game, array $data)
    {
        $endPeriod = 0;
        foreach ($data as $period)
        {
            foreach ($period as $p) {
                if ($p['type'] !== 'finishPeriod') {
                continue;
            }
            if ($p['time'] < 90) {
                continue;
            }

            $endPeriod = $p['time'];
            break;
            }
        }

        foreach ($game->teams as $team) {
            foreach ($team->players as $player) {
                $player->setEndTime($endPeriod);
            }
        }
    }
}