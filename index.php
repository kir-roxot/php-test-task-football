<?php

require_once "config.php";
require_once "src/autoload.php";

$generator = new PageGenerator();
$generator->generate();

return 0;
