<?php

namespace App\Puzzles;

class Day06Lanternfish extends AbstractPuzzle
{
    protected static int $day_number = 6;

    private array $fish_by_number;

    private function resetFish()
    {
        $this->fish_by_number = array_count_values(explode(',', $this->input->lines[0]));
        for ($i = 0; $i <= 8; $i++) {
            if (!isset($this->fish_by_number[$i])) {
                $this->fish_by_number[$i] = 0;
            }
        }
        ksort($this->fish_by_number);
    }

    public function getPartOneAnswer(): int
    {
        $this->resetFish();
        $limit = 80;
        for ($days = 0; $days < $limit; $days++) {
            $this->evolveFish();
        }

        return $this->getFishCount();
    }

    private function evolveFish()
    {
        $new_fish = [];
        foreach ($this->fish_by_number as $number => $count) {
            $new_fish[$number - 1] = $count;
        }

        $new_fish[6] = bcadd($new_fish[6], $new_fish[-1]);
        $new_fish[8] = $new_fish[-1];
        unset($new_fish[-1]);

        $this->fish_by_number = $new_fish;
    }

    private function getFishCount()
    {
        $total = '0';
        foreach ($this->fish_by_number as $number => $count) {
            $total = bcadd($total, $count);
        }

        return $total;
    }

    public function getPartTwoAnswer(): int
    {
        $this->resetFish();
        $limit = 256;
        for ($days = 0; $days < $limit; $days++) {
            $this->evolveFish();
        }

        return $this->getFishCount();
    }
}
