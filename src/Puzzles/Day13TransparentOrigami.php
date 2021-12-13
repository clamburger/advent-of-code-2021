<?php

namespace App\Puzzles;

class Day13TransparentOrigami extends AbstractPuzzle
{
    protected static int $day_number = 13;

    private array $dots = [];
    private array $coords;
    private array $instructions;

    private int $height;
    private int $width;

    public function __construct()
    {
        parent::__construct();

        $coords = explode("\n", $this->input->raw_blocks[0]);
        $this->coords = array_map(function ($line) {
            [$x, $y] = explode(',', $line);
            return ['x' => (int)$x, 'y' => (int)$y];
        }, $coords);

        $this->sortCoords();

        $this->height = max(array_column($this->coords, 'y')) + 1;
        $this->width = max(array_column($this->coords, 'x')) + 1;

        $instructions = str_replace('fold along ', '', $this->input->raw_blocks[1]);
        $instructions = explode("\n", $instructions);
        $this->instructions = array_map(fn ($line) => explode("=", $line), $instructions);
    }

    private function sortCoords()
    {
        usort($this->coords, fn ($a, $b) => $a['y'] <=> $b['y'] ?: $a['x'] <=> $b['x']);
    }

    private function cleanCoords()
    {
        $coords = array_map(fn ($coord) => "{$coord['x']},{$coord['y']}", $this->coords);
        $coords = array_unique($coords);
        $this->coords = array_map(function ($line) {
            [$x, $y] = explode(',', $line);
            return ['x' => (int)$x, 'y' => (int)$y];
        }, $coords);
    }

    public function getPartOneAnswer(): int
    {
        $instruction = $this->instructions[0];
        $this->performInstruction($instruction[0], $instruction[1]);
        $this->sortCoords();
        $this->cleanCoords();

        return count($this->coords);
    }

    private function performInstruction(string $axis, int $fold_position): void
    {
        foreach ($this->coords as &$coord) {
            $axis_position = $coord[$axis];
            // Dots to the left of or above the fold remain unchanged
            if ($axis_position <= $fold_position) {
                continue;
            }

            // Determine how far we are from the fold: the dot moves 2 * that distance + 1
            $coord[$axis] -= (2 * ($axis_position - $fold_position));
        }
    }

    public function getPartTwoAnswer(): int
    {
        return 0;
    }
}
