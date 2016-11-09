<?php

include 'src/autoload.php';

$pageGenerator = new PageGenerator();

try {
    $pageGenerator->generate();
} catch (\Exception $e) {
    throw new \Exception($e->getMessage());
}

return 0;

