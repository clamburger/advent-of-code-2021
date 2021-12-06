<?php

namespace App\Puzzles;

class Day06Lanternfish extends AbstractPuzzle
{
    protected static int $day_number = 6;

    private array $fish;

    public function getPartOneAnswer(): int
    {
        $this->fish = explode(',', $this->input->lines[0]);

        $limit = 80;
        for ($days = 0; $days < $limit; $days++) {
            $this->evolveFish();
        }

        return count($this->fish);
    }

    private function evolveFish()
    {
        $this->fish = array_map(fn ($fish) => $fish - 1, $this->fish);

        $evolved_count = count(array_filter($this->fish, fn ($fish) => $fish === -1));
        $this->fish = array_merge($this->fish, array_fill(0, $evolved_count, 8));
        $this->fish = array_map(fn ($fish) => $fish === -1 ? 6 : $fish, $this->fish);
    }

    public function getPartTwoAnswer(): int
    {
        return 0;
    }
}
