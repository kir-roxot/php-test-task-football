<?php

require __DIR__ . '/vendor/autoload.php';

use Roxot\PageGenerator;

$generator = new PageGenerator("source/matches", "result");
$generator->generate();

return 0;
