<?php

class Parser
{
    public function __construct()
    {
        $this->scan();
    }

    public function scan()
    {
        $filesNames = glob("source/matches/*.json");
        if (!count($filesNames)) {
            return 1;
        }

        $obj = [];
        foreach ($filesNames as $fileName) {
            $file = file_get_contents($fileName);
            $obj[] = json_decode($file);
        }

        return $obj;
    }
}
