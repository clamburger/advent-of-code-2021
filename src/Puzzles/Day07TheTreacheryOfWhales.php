<?php

namespace App\Puzzles;

class Day07TheTreacheryOfWhales extends AbstractPuzzle
{
    protected static int $day_number = 7;

    private array $crabs;

    private function resetCrabs(): void
    {
        $this->crabs = explode(',', $this->input->lines[0]);
    }

    public function getPartOneAnswer(): int
    {
        $this->resetCrabs();

        $limit = max($this->crabs);

        $min = null;

        for ($position = 0; $position <= $limit; $position++) {
            $fuel = 0;
            foreach ($this->crabs as $crab) {
                $fuel += abs($crab - $position);
            }

            if ($fuel < $min || $min === null) {
                $min = $fuel;
            }
        }

        return $min;
    }

    public function getPartTwoAnswer(): int
    {
        $this->resetCrabs();

        $limit = max($this->crabs);

        $min = null;

        for ($position = 0; $position <= $limit; $position++) {
            $fuel = 0;
            foreach ($this->crabs as $crab) {
                $diff = abs($crab - $position);
                $fuel += $diff * ($diff + 1) / 2;
            }

            if ($fuel < $min || $min === null) {
                $min = $fuel;
            }
        }

        return $min;
    }
}
