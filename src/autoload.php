<?php

spl_autoload_register(function ($class) {
    require_once "models/" . $class . ".php";
});

require_once "PageGenerator.php";
require_once "constant.php";