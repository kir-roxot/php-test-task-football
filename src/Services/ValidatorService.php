<?php

namespace Roxot\Services;

class ValidatorService
{
    // todo county not country
    const COUNTY = 'county';
    const CITY = 'city';
    const STADIUM = 'stadium';
    const TITLE = 'title';
    const COACH = 'coach';
    const COUNTRY = 'country';
    const NUMBER = 'number';
    const NAME = 'name';
    const TIME = 'time';
    const DESCRIPTION = 'description';
    const DETAILS = 'details';
    const TYPE = 'type';

    /**
     * @param array $data
     */
    public static function validateData(array $data)
    {
        $keys = [self::TYPE, self::TIME, self::DESCRIPTION, self::DETAILS];
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                self::throwException($key, $data);
            }
        }
    }

    /**
     * @param array $data
     */
    public static function validateGame(array $data)
    {
        $keys = [self::COUNTY, self::CITY, self::STADIUM];
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                self::throwException($key, $data);
            }
        }
    }

    /**
     * @param array $data
     */
    public static function validateTeam(array $data)
    {
        $keys = [self::TITLE, self::COACH, self::COUNTRY];
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                self::throwException($key, $data);
            }
        }
    }

    /**
     * @param array $data
     */
    public static function validatePlayer(array $data)
    {
        $keys = [self::NUMBER, self::NAME];
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                self::throwException($key, $data);
            }
        }
    }

    /**
     * @param array $data
     */
    public static function validateInfo(array $data)
    {
        $keys = [self::TIME, self::DESCRIPTION, self::TYPE];
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                self::throwException($key, $data);
            }
        }
    }

    /**
     * @param string $key
     * @param array $data
     * @throws \Exception
     */
    private function throwException(string $key, array $data)
    {
        throw new \Exception(
            sprintf(
                'Key "%s" not found in info data: "%s"',
                $key,
                implode(", ", array_keys($data))));
    }
}