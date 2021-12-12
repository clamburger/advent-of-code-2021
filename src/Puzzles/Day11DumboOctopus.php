<?php

namespace App\Puzzles;

use App\Utilities;

class Day11DumboOctopus extends AbstractPuzzle
{
    protected static int $day_number = 11;

    private array $octopuses;
    private array $flashed_this_step;
    private int $height;
    private int $width;
    private int $flashes = 0;

    public function __construct()
    {
        parent::__construct();
        $this->resetOctopuses();
        $this->height = count($this->octopuses);
        $this->width = count($this->octopuses[0]);
    }

    private function resetOctopuses()
    {
        $this->octopuses = $this->input->grid;
    }

    public function getPartOneAnswer(): int
    {
        for ($step = 1; $step <= 193; $step++) {
            echo "Step $step\n";
            $this->performStep();
            echo Utilities::gridToString($this->octopuses) . "\n";
        }

        return $this->flashes;
    }

    private function performStep()
    {
        $this->flashed_this_step = [];
        for ($x = 0; $x < $this->width; $x++) {
            for ($y = 0; $y < $this->height; $y++) {
                $this->incrementOctopus($x, $y);
            }
        }
    }

    private function incrementOctopus(int $x, int $y)
    {
        if (!isset($this->octopuses[$y][$x]) || isset($this->flashed_this_step["$x,$y"])) {
            return;
        }

        $this->octopuses[$y][$x]++;

        if ($this->octopuses[$y][$x] > 9) {
            $this->flashed_this_step["$x,$y"] = true;
            $this->flashes++;
            $this->octopuses[$y][$x] = 0;

            foreach ($this->getNeighbours($x, $y) as ['x' => $xn, 'y' => $yn]) {
                $this->incrementOctopus($xn, $yn);
            }
        }
    }

    private function getNeighbours(int $x, int $y)
    {
        $neighbours = [
           ['x' => $x-1, 'y' => $y-1],
           ['x' => $x  , 'y' => $y-1],
           ['x' => $x+1, 'y' => $y-1],
           ['x' => $x-1, 'y' => $y  ],
           ['x' => $x+1, 'y' => $y  ],
           ['x' => $x-1, 'y' => $y+1],
           ['x' => $x  , 'y' => $y+1],
           ['x' => $x+1, 'y' => $y+1],
        ];

        return array_filter($neighbours, fn ($coords) => isset($this->octopuses[$coords['y']][$coords['x']]));
    }

    public function getPartTwoAnswer(): int
    {
        $this->resetOctopuses();
        $step = 0;
        while (count($this->flashed_this_step) !== 100) {
            $step++;
            $this->performStep();
        }

        return $step;
    }
}
