<?php

namespace Roxot\Factories;

use Roxot\Models\Info;
use Roxot\Services\ValidatorService;

class InfoFactory
{
    /**
     * @param array $data
     * @return Info
     */
    public function createInfo(array $data)
    {
        $this->validate($data);
        return new Info($data['time'], $data['description'], $data['type']);
    }

    /**
     * @param array $data
     * @throws \Exception
     */
    private function validate(array $data)
    {
        ValidatorService::validateInfo($data);
    }
}