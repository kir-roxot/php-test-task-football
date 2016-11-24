<?php

namespace Roxot;

use Roxot\Models\Game;
use Roxot\Models\Team;
use Roxot\Models\Player;
use Roxot\Models\Info;

class PageGenerator
{
    const YELLOW_CARD_MODE = "yellow_card_mode";
    const RED_CARD_MODE = "red_card_mode";

    private $scanPath;
    private $resultPath;

    public function __construct(string $scanPath, string $resultPath){
        $this->scanPath = $scanPath;
        $this->resultPath = $resultPath;
    }

    public function generate()
    {
        $filesPaths = $this->getFilesPaths();

        foreach ($filesPaths as $filePath) {
            $gameData = $this->getGameInfoByFile($filePath);
            $game = $this->createGame($gameData);
            $this->addInfo($game, $gameData);
            $this->savePage($filePath, $game);
        }
    }

    /**
     * @return array $filesPaths
     * @throws \Exception
     */
    private function getFilesPaths()
    {
        $filesPaths = glob($this->scanPath . "*.json");
        if (!count($filesPaths)) {
            throw new \Exception(sprintf('The files not found in the directory: %s', $this->scanPath));
        }

        return $filesPaths;
    }

    /**
     * @param string $filePath
     * @return array
     * @throws \Exception
     */
    private function getGameInfoByFile(string $filePath)
    {
        $file = file_get_contents($filePath);
        $gameInfo = json_decode($file, true);
        if (is_null($gameInfo)) {
            throw new \Exception(sprintf('It is not possible to get data from file: %s', $filePath));
        }

        return $gameInfo;
    }

    /**
     * @param string $filePath
     * @param Game $game
     */
    private function savePage(string $filePath, Game $game)
    {
        $content = $this->getGameContent($game);
        $newFile = fopen($this->resultPath . "{$this->getFileName($filePath)}.html", "w");
        fwrite($newFile, $content);
        fclose($newFile);
    }

    /**
     * @param Game $game
     * @return string
     */
    private function getGameContent(Game $game)
    {
        ob_start();
        // template used Game object
        require_once "Templates/game.php";
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * @param string $filePath
     * @return string
     */
    private function getFileName(string $filePath)
    {
        $nameParts = explode(DIRECTORY_SEPARATOR, $filePath);
        $fileName = $nameParts[count($nameParts) - 1];
        return substr($fileName, 0, count($fileName) - 6);
    }

    /**
     * @param array $gameData
     * @return Game|null
     */
    private function createGame(array $gameData)
    {
        $game = null;
        foreach ($gameData as $data) {
            if ($data['type'] === "startPeriod" && !empty($data['details'])) {
                $gameLocation = $data['details']['stadium'];

                // json bug - county not country
                $game = new Game(
                    $gameLocation['county'],
                    $gameLocation['city'],
                    $gameLocation['stadium'],
                    $this->addTeams($data['details'])
                );
            }
        }

        return $game;
    }

    /**
     * @param array $teamData
     * @return array
     */
    private function addTeams(array $teamData)
    {
        $teams = [];
        for ($i = 1; $i <= 2; $i++) {
            $team = $teamData['team' . $i];
            $teams[$team['title']] = new Team(
                $team['title'],
                $team['coach'],
                $team['country'],
                $this->addPlayers($team['players'], $team['startPlayerNumbers'])
            );
        }

        return $teams;
    }

    /**
     * @param Player[] $players
     * @param array $startPlayerNumbers
     * @return array
     */
    private function addPlayers(array $players, array $startPlayerNumbers)
    {
        $data = [];
        foreach ($players as $player) {
            $isStarted = in_array($player['number'], $startPlayerNumbers);
            $data[$player['number']] = new Player($player['number'], $player['name'], $isStarted);
        }

        return $data;
    }

    /**
     * @param Game $game
     * @param array $gameData
     */
    private function addInfo(Game $game, array $gameData)
    {
        $info = [];
        foreach ($gameData as $data) {
            $info[] = new Info($data['time'], $data['description'], $data['type']);
            switch ($data['type']) {
                case "yellowCard":
                    $this->addCard($game, $data['details'], $data['time'], self::YELLOW_CARD_MODE);
                    break;
                case "redCard":
                    $this->addCard($game, $data['details'], $data['time'], self::RED_CARD_MODE);
                    break;
                case "goal":
                    $this->addGoal($game, $data['details']);
                    $this->addAssist($game, $data['details']);
                    break;
                case "replacePlayer":
                    $this->addReplacement($game, $data['details'], $data['time']);
                    break;
                default:
                    break;
            }

            if ($data['type'] === "finishPeriod" && $data['time'] >= 90) {
                $this->addEndPeriod($game, $data['time']);
            }
        }

        $game->info = $info;
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

    /**
     * @param Game $game
     * @param array $data
     * @param int $time
     * @param string $type
     */
    private function addCard(Game $game, array $data, int $time, string $type)
    {
        if ($type === self::YELLOW_CARD_MODE) {
            $game->teams[$data['team']]->players[$data['playerNumber']]->increaseYellowCards();
            if ($game->teams[$data['team']]->players[$data['playerNumber']] === 2) {
                $game->teams[$data['team']]->players[$data['playerNumber']]->setEndTime($time);
            }
        } else if ($type === self::RED_CARD_MODE) {
            $game->teams[$data['team']]->players[$data['playerNumber']]->setRedCard();
            $game->teams[$data['team']]->players[$data['playerNumber']]->setEndTime($time);
        }
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
     */
    private function addReplacement(Game $game, array $data, int $time)
    {
        $playerOut = $game->teams[$data['team']]->players[$data['outPlayerNumber']];
        $playerIn = $game->teams[$data['team']]->players[$data['inPlayerNumber']];
        $playerOut->setReplacement(Player::PLAYER_OUT_MODE, $time);
        $playerIn->setReplacement(Player::PLAYER_IN_MODE, $time);
        $game->teams[$data['team']]->setReplacement($playerOut, $playerIn, $time);
    }
}
