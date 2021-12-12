<?php

namespace App\Puzzles;

class Day12PassagePathing extends AbstractPuzzle
{
    protected static int $day_number = 12;

    private array $caves = [];
    private array $paths = [];

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
        $this->findPaths('start', 'end', []);

        return count($this->paths);
    }

    private function findPaths(string $start, string $end, array $path_so_far)
    {
        $path_so_far[] = $start;

        if ($start === $end) {
            $this->paths[] = $path_so_far;
            // GOOD END: we reached our destination!
            return;
        }

        $options = $this->getOptions($start, $path_so_far);
        if (empty($options)) {
            // BAD END: we ran out of options before reaching our destination.
            return;
        }

        foreach ($options as $cave) {
            $this->findPaths($cave, $end, $path_so_far);
        }
    }

    private function getOptions(string $cave, array $path_so_far)
    {
        $options = $this->caves[$cave];
        return array_filter($options, fn ($cave) => ($this->isCaveBig($cave) || !in_array($cave, $path_so_far)));
    }


    private function isCaveBig(string $cave): bool
    {
        return strtoupper($cave) === $cave;
    }

    private function isCaveSmall(string $cave): bool
    {
        return !$this->isCaveBig($cave);
    }

    public function getPartTwoAnswer(): int
    {
        return 0;
    }
}
