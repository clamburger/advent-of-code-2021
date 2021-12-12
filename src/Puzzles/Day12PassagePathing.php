<?php

namespace App\Puzzles;

class Day12PassagePathing extends AbstractPuzzle
{
    protected static int $day_number = 12;

    private array $caves = [];
    private array $paths;

    public function __construct()
    {
        parent::__construct();

        foreach ($this->input->lines as $connection) {
            list($from, $to) = explode('-', $connection);
            $this->caves[$from][] = $to;
            $this->caves[$to][] = $from;
        }
    }

    public function getPartOneAnswer(): int
    {
        $this->paths = [];
        $this->findPaths('start', 'end', [], $this->getOptions(...));

        return count($this->paths);
    }

    private function findPaths(string $start, string $end, array $path_so_far, callable $options_callback)
    {
        $path_so_far[] = $start;

        if ($start === $end) {
            $this->paths[] = $path_so_far;
            // GOOD END: we reached our destination!
            return;
        }

        $options = $options_callback($start, $path_so_far);
        if (empty($options)) {
            // BAD END: we ran out of options before reaching our destination.
            return;
        }

        foreach ($options as $cave) {
            $this->findPaths($cave, $end, $path_so_far, $options_callback);
        }
    }

    private function getOptions(string $cave, array $path_so_far): array
    {
        $options = $this->caves[$cave];
        return array_filter($options, fn ($cave) => ($this->isCaveBig($cave) || !in_array($cave, $path_so_far)));
    }

    private function getOptionsPermissive(string $cave, array $path_so_far): array
    {
        $options = $this->caves[$cave];
        $visits_per_cave = array_count_values($path_so_far);

        $path_so_far_small_only = array_filter($path_so_far, fn ($cave) => !$this->isCaveBig($cave));
        $visits_per_cave_small_only = array_count_values($path_so_far_small_only);
        $small_visited_twice = max($visits_per_cave_small_only) >= 2;

        return array_filter($options, function ($cave) use ($visits_per_cave, $small_visited_twice) {
            // Big caves: unlimited
            if ($this->isCaveBig($cave)) {
                return true;
            }

            $visits_so_far = $visits_per_cave[$cave] ?? 0;

            // Start or end: one time maximum
            if ($cave === 'start' || $cave === 'end') {
                return $visits_so_far < 1;
            }

            // Other small caves: one time maximum OR two times if no other small cave has been visited twice yet
            return $visits_so_far < 1 || !$small_visited_twice;
        });
    }

    private function isCaveBig(string $cave): bool
    {
        return strtoupper($cave) === $cave;
    }

    public function getPartTwoAnswer(): int
    {
        $this->paths = [];
        $this->findPaths('start', 'end', [], $this->getOptionsPermissive(...));

        return count($this->paths);
    }
}
