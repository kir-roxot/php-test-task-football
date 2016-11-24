<?php

namespace Roxot;

use Roxot\Factories\InfoFactory;
use Roxot\Models\Game;
use Roxot\Models\Player;
use Roxot\Factories\GameFactory;

class PageGenerator
{
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
            $game = $this->buildGame($gameData);
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
     * @param array $gameData
     * @return Game
     */
    private function buildGame(array $gameData)
    {
        $game = (new GameFactory)->createGame($gameData);
        $this->addInfo($game, $gameData);

        return $game;
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
     * @param array $gameData
     */
    private function addInfo(Game $game, array $gameData)
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
}
