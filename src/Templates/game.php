<?php

use Roxot\Models\Game;

/**
 * @var Game $game
 */
$teams = array_values($game->teams);
$team1 = $teams[0];
$team2 = $teams[1];

function getImage($type)
{
    switch ($type) {
        case 'yellowCard':
            return '<img src="../assets/images/yellow.gif">';
        case 'redCard':
            return '<img src="../assets/images/red.gif">';
        case 'replacePlayer':
            return '<img src="../assets/images/sub.gif">';
        case 'goal':
            return '<img src="../assets/images/ball.png">';
        default:
            return '';
    }
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Футбол</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <p class="navbar-company">Футбол</p>
        </div>
    </div>
</nav>

<div class="container">
    <div class="starter-template">
        <h1>Результат матча</h1>
        <div class="scores">
            <span class="team"><?= $team1->title ?></span><span class="goals"><?= $team1->goals ?></span><span
                    class="big-colon">:</span><span class="goals"><?= $team2->goals ?></span><span
                    class="team"><?= $team2->title ?></span>
        </div>
        <div class="tabs">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#game" aria-controls="home" role="tab"
                                                          data-toggle="tab">Ход матча</a></li>
                <li role="presentation"><a href="#players" aria-controls="profile" role="tab"
                                           data-toggle="tab">Основные игроки</a></li>
                <li role="presentation"><a href="#reservePlayers" aria-controls="profile" role="tab"
                                           data-toggle="tab">Запасные игроки</a></li>
                <li role="presentation"><a href="#changes" aria-controls="messages" role="tab"
                                           data-toggle="tab">Замены</a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="game">
                    <br>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Минута</th>
                            <th>Событие</th>
                            <th>Описание</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($game->info as $info) { ?>
                            <tr>
                                <td><?= $info->time ?>'</td>
                                <td><?= getImage($info->type) ?></td>
                                <td><?= $info->description ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="players">
                    <span class="title-team"><?= $team1->title ?></span>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Номер</th>
                            <th>ФИО</th>
                            <th>Время на поле</th>
                            <th>Голов</th>
                            <th>Голевых передач</th>
                            <th>Желтых карточек</th>
                            <th>Красных карточек</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($team1->players as $player) {
                            if ($player->isStarted) {
                                ?>
                                <tr>
                                    <td><?= $player->number ?></td>
                                    <td><?= $player->name ?></td>
                                    <td><?= $player->fulltime() ?>'</td>
                                    <td><?= $player->goals ?></td>
                                    <td><?= $player->assists ?></td>
                                    <td><?= $player->yellowCards ?></td>
                                    <td><?= $player->redCards ?></td>
                                </tr>
                            <?php }
                        } ?>
                        </tbody>
                    </table>
                    <span class="title-team"><?= $team2->title ?></span>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Номер</th>
                            <th>ФИО</th>
                            <th>Время на поле</th>
                            <th>Голов</th>
                            <th>Голевых передач</th>
                            <th>Желтых карточек</th>
                            <th>Красных карточек</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($team2->players as $player) {
                            if ($player->isStarted) {
                                ?>
                                <tr>
                                    <td><?= $player->number ?></td>
                                    <td><?= $player->name ?></td>
                                    <td><?= $player->fulltime() ?>'</td>
                                    <td><?= $player->goals ?></td>
                                    <td><?= $player->assists ?></td>
                                    <td><?= $player->yellowCards ?></td>
                                    <td><?= $player->redCards ?></td>
                                </tr>
                            <?php }
                        } ?>
                        </tbody>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="reservePlayers">
                    <span class="title-team"><?= $team1->title ?></span>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Номер</th>
                            <th>ФИО</th>
                            <th>Время на поле</th>
                            <th>Голов</th>
                            <th>Голевых передач</th>
                            <th>Желтых карточек</th>
                            <th>Красных карточек</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($team1->players as $player) {
                            if (!$player->isStarted) {
                                ?>
                                <tr>
                                    <td><?= $player->number ?></td>
                                    <td><?= $player->name ?></td>
                                    <td><?= $player->fulltime() ?>'</td>
                                    <td><?= $player->goals ?></td>
                                    <td><?= $player->assists ?></td>
                                    <td><?= $player->yellowCards ?></td>
                                    <td><?= $player->redCards ?></td>
                                </tr>
                            <?php }
                        } ?>
                        </tbody>
                    </table>
                    <span class="title-team"><?= $team2->title ?></span>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Номер</th>
                            <th>ФИО</th>
                            <th>Время на поле</th>
                            <th>Голов</th>
                            <th>Голевых передач</th>
                            <th>Желтых карточек</th>
                            <th>Красных карточек</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($team2->players as $player) {
                            if (!$player->isStarted) {
                                ?>
                                <tr>
                                    <td><?= $player->number ?></td>
                                    <td><?= $player->name ?></td>
                                    <td><?= $player->fulltime() ?>'</td>
                                    <td><?= $player->goals ?></td>
                                    <td><?= $player->assists ?></td>
                                    <td><?= $player->yellowCards ?></td>
                                    <td><?= $player->redCards ?></td>
                                </tr>
                            <?php }
                        } ?>
                        </tbody>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="changes">
                    <span class="title-team"><?= $team1->title ?></span>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Время</th>
                            <th>Вышел</th>
                            <th>Покинул</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($team1->replacements as $replacement) { ?>
                            <tr>
                                <td><?= $replacement['time'] ?>'</td>
                                <td><?= $replacement['in']->number ?> - <?= $replacement['in']->name ?></td>
                                <td><?= $replacement['out']->number ?> - <?= $replacement['out']->name ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <span class="title-team"><?= $team2->title ?></span>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Время</th>
                            <th>Вышел</th>
                            <th>Покинул</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($team2->replacements as $replacement) { ?>
                            <tr>
                                <td><?= $replacement['time'] ?>'</td>
                                <td><?= $replacement['in']->number ?> - <?= $replacement['in']->name ?></td>
                                <td><?= $replacement['out']->number ?> - <?= $replacement['out']->name ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>