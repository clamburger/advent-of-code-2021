<?php

namespace App\Puzzles;

class Day02Dive extends AbstractPuzzle
{
    protected static int $day_number = 2;

    /**
     * Calculate the final horizontal position multiplied by the final depth
     *
     * @return int
     */
    public function getPartOneAnswer(): int
    {
        $horizontal_position = 0;
        $depth = 0;

        foreach ($this->input->lines as $command) {
            [$direction, $distance] = explode(' ', $command);
            $distance = (int) $distance;

            switch ($direction) {
                case 'forward':
                    $horizontal_position += $distance;
                    break;
                case 'up':
                    $depth -= $distance;
                    break;
                case 'down':
                    $depth += $distance;
                    break;
            }
        }

        return $horizontal_position * $depth;
    }

    /**
     * Calculate the final horizontal position multiplied by the final depth, taking aim into account
     *
     * @return int
     */
    public function getPartTwoAnswer(): int
    {
        $horizontal_position = 0;
        $depth = 0;
        $aim = 0;

        foreach ($this->input->lines as $command) {
            [$direction, $distance] = explode(' ', $command);
            $distance = (int) $distance;

            switch ($direction) {
                case 'forward':
                    $horizontal_position += $distance;
                    $depth += ($aim * $distance);
                    break;
                case 'up':
                    $aim -= $distance;
                    break;
                case 'down':
                    $aim += $distance;
                    break;
            }
        }

        return $horizontal_position * $depth;
    }
}
