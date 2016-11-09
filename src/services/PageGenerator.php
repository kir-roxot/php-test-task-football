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
            $obj = json_decode($file);
            $game = $this->createGame($obj);
            $this->addInfo($game, $obj);
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
                    $gameLocation->city,
                    $gameLocation->stadium,
                    $this->addTeams($o->details)
                );
            }
        }

        return $game;
    }

    private function addTeams($data)
    {
        $teams = [];
        for ($i = 1; $i <= 2; $i++) {
            $team = $data->{"team" . $i};
            $teams[$team->title] = new Team(
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

    private function addInfo(Game $game, $obj)
    {
        $info = [];
        foreach ($obj as $o) {
            $info[] = new Info($o->time, $o->description, $o->type);
            switch ($o->type) {
                case 'yellowCard':
                    $this->addCard($game, $o->details, $o->time, YELLOW_CARD);
                    break;
                case 'redCard':
                    $this->addCard($game, $o->details, $o->time, RED_CARD);
                    break;
                case 'goal':

                    break;
                case 'replacePlayer':

                    break;
                default:
                    break;
            }

            if ($o->type === 'finishPeriod' && $o->time >= 90) {
                $this->addEndPeriod($game, $o->time);
            }
        }
    }

    private function addEndPeriod(Game $game, $time)
    {
        /**
         * @var Team $team
         */
        foreach ($game->teams as $team) {
            /**
             * @var Player $player
             */
            foreach ($team->players as $player) {
                $player->setEndTime($time);
            }
        }
    }

    private function addCard(Game $game, $data, $time, $type)
    {
        if ($type === YELLOW_CARD) {
            $game->teams[$data->team]->players[$data->playerNumber]->setYellowCards($time);
        } else if ($type === RED_CARD) {
            $game->teams[$data->team]->players[$data->playerNumber]->setRedCards($time);
        }
    }

}
