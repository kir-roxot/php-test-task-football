<?php

namespace Roxot;

use Roxot\Models\Game;
use Roxot\Factories\GameFactory;
use Roxot\Services\GameInfoService;
use Roxot\Services\NormalizerService;

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
            (new NormalizerService)->normalize($gameData);
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
        (new GameInfoService())->addInfo($game, $gameData);

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
