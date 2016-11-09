<?php

spl_autoload_register(function ($class) {
    include "models/" . $class . ".php";
});

include "services/PageGenerator.php";