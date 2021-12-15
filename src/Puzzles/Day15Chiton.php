<?php

namespace App\Puzzles;

class Day15Chiton extends AbstractPuzzle
{
    protected static int $day_number = 15;

    private array $fastest_paths = [];
    private array $grid;
    private int $height;
    private int $width;

    private array $nodes_to_update = [];

    public function __construct()
    {
        parent::__construct();
        $this->grid = $this->input->grid;
        $this->height = count($this->grid);
        $this->width = count($this->grid[0]);

        $this->fastest_paths[0][0] = [0];
        $this->nodes_to_update = ['0,0' => ['x' => 0, 'y' => 0]];
    }

    public function getPartOneAnswer(): int
    {
        while (!empty($this->nodes_to_update)) {
            $node_to_update = array_shift($this->nodes_to_update);
            $this->updateFastestPathToNeighbours($node_to_update['x'], $node_to_update['y'], []);
        }

        return array_sum($this->fastest_paths[$this->height-1][$this->width-1]);
    }

    private function updateFastestPathToNeighbours(int $x, int $y, array $path_so_far)
    {
        $path_so_far["$x,$y"] = true;
        $fastest_to_current = $this->fastest_paths[$y][$x];
        $total = array_sum($fastest_to_current);

//        echo "Updating path to $x, $y (path so far $total)\n";

        $neighbours = $this->getNeighbours($x, $y);

        foreach ($neighbours as $neighbour) {
            $total_to_neighbour = $total + $neighbour['value'];

            if ($neighbour['fastest_path'] === null || $total_to_neighbour < $neighbour['fastest_total']) {
                // We have a new fastest route to this neighbour
                $new_fastest_path = [...$fastest_to_current, $neighbour['value']];
                $this->fastest_paths[$neighbour['y']][$neighbour['x']] = $new_fastest_path;

                if (isset($this->nodes_to_update["{$neighbour['x']},{$neighbour['y']}"])) {
                    continue;
                }
                $this->nodes_to_update["{$neighbour['x']},{$neighbour['y']}"] = $neighbour;
            }
        }
    }

    private function getNeighbours(int $x, int $y): array
    {
        $modifiers = [
            [0,-1], [-1,0], [0,1], [1,0]
        ];

        $neighbours = [];

        if ($x == $this->width - 1 && $y == $this->height - 1) {
            return [];
        }

        foreach ($modifiers as $modifier) {
            $x2 = $x + $modifier[0];
            $y2 = $y + $modifier[1];

            if (isset($this->grid[$y2][$x2])) {
                $neighbours[] = [
                    'x' => $x2,
                    'y' => $y2,
                    'value' => $this->grid[$y2][$x2],
                    'fastest_path' => $this->fastest_paths[$y2][$x2] ?? null,
                    'fastest_total' => array_sum($this->fastest_paths[$y2][$x2] ?? []),
                ];
            }
        }

        return $neighbours;
    }

    public function getPartTwoAnswer(): int
    {
        return 0;
    }
}
