<?php

require __DIR__ . '/vendor/autoload.php';

use Roxot\PageGenerator;

$generator = new PageGenerator(__DIR__ . "/source/matches/", __DIR__ . "/result/");
$generator->generate();

return 0;
