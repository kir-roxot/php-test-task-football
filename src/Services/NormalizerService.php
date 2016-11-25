<?php

namespace Roxot\Services;

class NormalizerService
{
    const GOAL = 'goal';
    const YELLOW_CARD = 'yellowCard';
    const RED_CARD = 'redCard';
    const START_PERIOD = 'startPeriod';
    const END_PERIOD = 'finishPeriod';
    const REPLACEMENT = 'replacePlayer';

    public function normalize(array $gameData)
    {
        $newData = [];
        $startPeriod = null;
        foreach ($gameData as $data) {
            $newData['infoEvent'][] = $this->getInfoEvent($data);
            if ($data['type'] === self::START_PERIOD) {
                $startPeriod = $this->getStartPeriod($data);
            }
            if ($data['type'] === self::END_PERIOD) {
                $endPeriod = $this->getEndPeriod($data);
                $newData['periodEvent'][] = [$startPeriod, $endPeriod];
                $startPeriod = null;
            }
            if ($data['type'] === self::GOAL) {
                $newData['goalEvent'][] = $this->getGoalEvent($data);
            }
            if ($data['type'] === self::YELLOW_CARD) {
                $newData['yellowCardEvent'][] = $this->getYellowCardEvent($data);
            }
            if ($data['type'] === self::RED_CARD) {
                $newData['redCardEvent'][] = $this->getRedCardEvent($data);
            }
            if ($data['type'] === self::REPLACEMENT) {
                $newData['replacementEvent'][] = $this->getReplacementEvent($data);
            }


            if ($data['type'] !== self::START_PERIOD) {
                continue;
            }
            if (empty($data['details'])) {
                continue;
            }

            $newData['startInfoEvent'] = $data['details'];

        }

        var_dump($newData['startInfoEvent']);
    }

    private function validate(array $data)
    {

    }

    /**
     * @param array $data
     * @return array
     */
    private function getInfoEvent(array $data)
    {
        unset($data['details']);

        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    private function getGoalEvent(array $data)
    {
        $data = array_merge($data, $data['details']);
        unset($data['type']);
        unset($data['description']);
        unset($data['details']);

        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    private function getYellowCardEvent(array $data)
    {
        $data = array_merge($data, $data['details']);
        unset($data['type']);
        unset($data['description']);
        unset($data['details']);

        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    private function getRedCardEvent(array $data)
    {
        $data = array_merge($data, $data['details']);
        unset($data['type']);
        unset($data['description']);
        unset($data['details']);

        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    private function getStartPeriod(array $data)
    {
        unset($data['description']);
        unset($data['details']);

        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    private function getEndPeriod(array $data)
    {
        unset($data['description']);
        unset($data['details']);

        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    private function getReplacementEvent(array $data)
    {
        $data = array_merge($data, $data['details']);
        unset($data['type']);
        unset($data['description']);
        unset($data['details']);

        return $data;
    }

}