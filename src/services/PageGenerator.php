<?php

class PageGenerator
{
    public function generate()
    {
        $filesNames = glob("source/matches/*.json");
        if (!count($filesNames)) {
            throw new \Exception("Files not found.");
        }

        foreach ($filesNames as $fileName) {
            $file = file_get_contents($fileName);
            $this->createGame(json_decode($file));
        }
    }

    private function createGame($obj)
    {
        $game = null;
        foreach ($obj as $o) {
            if ($o->type === "startPeriod" && !empty($o->details)) {
                $gameLocation = $o->details->stadium;
                // json bug county not country
                $game = new Game(
                    $gameLocation->county,
                    $gameLocation->city, $gameLocation->stadium,
                    $this->addTeams($o->details)
                );
            }
        }

        echo serialize($game->teams[1]) . "\n";
    }

    private function addTeams($details)
    {
        $teams = [];
        for ($i = 1; $i <= 2; $i++) {
            $team = $details->{"team" . $i};
            $teams[] = new Team(
                $team->title,
                $team->coach,
                $team->country,
                $this->addPlayers($team->players)
            );
        }

        return $teams;
    }

    private function addPlayers($players)
    {
        $data = [];
        foreach ($players as $player) {
            $data[$player->number] = new Player($player->number, $player->name);
        }

        return $data;
    }

}
