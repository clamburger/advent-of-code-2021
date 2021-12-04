<?php

namespace App\Puzzles;

use Exception;

class Day04GiantSquid extends AbstractPuzzle
{
    protected static int $day_number = 4;

    private array $number_order;
    private array $boards = [];

    public function __construct()
    {
        parent::__construct();

        $first_line = array_shift($this->inputs);
        $this->number_order = explode(',', $first_line);

        $board = [];

        // Parse the boards
        foreach ($this->inputs as $row) {
            if (empty($row) && !$board) {
                continue;
            }

            $row = str_replace('  ', ' ', $row);
            $board[] = explode(' ', $row);

            if (count($board) === 5) {
                $this->boards[] = $board;
                $board = [];
            }
        }
    }

    private function doesBoardHaveABingo(array $board, array $numbers_called): bool
    {
        // Check horizontals
        foreach ($board as $row) {
            $row_marked = array_filter($row, fn ($number) => in_array($number, $numbers_called));
            if (count($row_marked) === 5) {
                return true;
            }
        }

        for ($i = 0; $i < 5; $i++) {
            $column = array_column($board, $i);
            $column_marked = array_filter($column, fn ($number) => in_array($number, $numbers_called));
            if (count($column_marked) === 5) {
                return true;
            }
        }

        return false;
    }

    private function calculateBoardScore(array $board, array $numbers_called): int
    {
        $score = 0;

        foreach ($board as $row) {
            foreach ($row as $number) {
                if (!in_array($number, $numbers_called)) {
                    $score += $number;
                }
            }
        }

        return $score;
    }

    public function getPartOneAnswer(): int
    {
        $numbers_called_so_far = [];

        foreach ($this->number_order as $number) {
            $numbers_called_so_far[] = $number;

            foreach ($this->boards as $board) {
                $bingo = $this->doesBoardHaveABingo($board, $numbers_called_so_far);
                if ($bingo) {
                    $board_score = $this->calculateBoardScore($board, $numbers_called_so_far);
                    return $board_score * $number;
                }
            }
        }

        throw new Exception('Numbers exhausted without finding a winner.');
    }

    public function getPartTwoAnswer(): int
    {
        $numbers_called_so_far = [];
        $boards_won = [];

        foreach ($this->number_order as $number) {
            $numbers_called_so_far[] = $number;

            foreach ($this->boards as $index => $board) {
                if (in_array($index, $boards_won)) {
                    continue;
                }

                $bingo = $this->doesBoardHaveABingo($board, $numbers_called_so_far);
                if ($bingo) {
                    $boards_won[] = $index;
                }

                if (count($boards_won) === count($this->boards)) {
                    $board_score = $this->calculateBoardScore($board, $numbers_called_so_far);
                    return $board_score * $number;
                }
            }
        }

        throw new Exception('Numbers exhausted without finding the worst board.');
    }
}
