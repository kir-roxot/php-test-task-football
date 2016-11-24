<?php

namespace Roxot\Factories;

use Roxot\Models\Info;

class InfoFactory
{
    const TIME = 'time';
    const DESCRIPTION = 'description';
    const TYPE = 'type';

    public function createInfo(array $data)
    {
        $this->validate($data);
        return new Info($data['time'], $data['description'], $data['type']);
    }

    private function validate(array $data)
    {
        $keys = [self::TIME, self::DESCRIPTION, self::TYPE];
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new \Exception(
                    sprintf(
                        'Key "%s" not found in info data: "%s"',
                        $key,
                        implode(", ", array_keys($data))));
            }
        }
    }
}