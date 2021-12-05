<?php

namespace App\Puzzles;

class Day01SolarSweep extends AbstractPuzzle
{
    protected static int $day_number = 1;

    /**
     * Count the number of times the depth increases
     *
     * @return int
     */
    public function getPartOneAnswer(): int
    {
        $previous_depth = null;

        $deeper_depths = 0;

        foreach ($this->input->lines as $depth) {
            if ($previous_depth !== null && $depth > $previous_depth) {
                $deeper_depths++;
            }

            $previous_depth = $depth;
        }

        return $deeper_depths;
    }

    /**
     * Count the number of times the depth increases using a three-measurement sliding window
     *
     * @return int
     */
    public function getPartTwoAnswer(): int
    {
        $deeper_measurements = 0;

        $measurement_window = [];

        foreach ($this->input->lines as $index => $depth) {
            $previous_measurement = array_sum($measurement_window);
            $measurement_window[] = $depth;

            if ($index <= 2) {
                // We need a full measurement window before we can start comparing
                continue;
            }

            array_shift($measurement_window);
            $current_measurement = array_sum($measurement_window);

            if ($current_measurement > $previous_measurement) {
                $deeper_measurements++;
            }
        }

        return $deeper_measurements;
    }
}
