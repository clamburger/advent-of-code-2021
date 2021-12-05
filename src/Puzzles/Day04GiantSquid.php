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

        $lines = $this->input->lines;

        $first_line = array_shift($lines);
        $this->number_order = explode(',', $first_line);

        $board = [];

        // Parse the boards
        foreach ($lines as $row) {
            if (empty($row) && !$board) {
                continue;
            }

            // Numbers are aligned within columns which means single digit numbers will have an additional space
            // next to them.
            $row = str_replace('  ', ' ', $row);
            $board[] = explode(' ', trim($row));

            if (count($board) === 5) {
                $this->boards[] = $board;
                $board = [];
            }
        }
    }

    /**
     * Checks if a board has a bingo.
     *
     * A board has a bingo if it has five numbers in a row (either horizontally or vertically) that are in the array
     * of called numbers. Diagonals do not count as a bingo.
     *
     * @param array $board
     * @param array $numbers_called
     * @return bool True if the board has a bingo, false if not.
     */
    private function doesBoardHaveABingo(array $board, array $numbers_called): bool
    {
        // Check horizontals
        foreach ($board as $row) {
            $row_marked = array_filter($row, fn ($number) => in_array($number, $numbers_called));
            if (count($row_marked) === 5) {
                return true;
            }
        }

        // Check verticals
        for ($i = 0; $i < 5; $i++) {
            $column = array_column($board, $i);
            $column_marked = array_filter($column, fn ($number) => in_array($number, $numbers_called));
            if (count($column_marked) === 5) {
                return true;
            }
        }

        return false;
    }

    /**
     * Calculate the score of a winning board.
     *
     * The score of a board is calculated by summing up the numbers that weren't called.
     *
     * @param array $board
     * @param array $numbers_called
     * @return int
     */
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

    /**
     * Find the score of the winning board.
     *
     * @throws Exception Throws an exception if no winning boards can be found.
     */
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

        // This shouldn't happen unless the list of numbers or the boards are malformed.
        throw new Exception('Numbers exhausted without finding a winner.');
    }

    /**
     * Find the score of the board that wins last
     *
     * @throws Exception Throws an exception if the winning order of all boards can't be calculated.
     */
    public function getPartTwoAnswer(): int
    {
        $numbers_called_so_far = [];
        $boards_won = [];

        foreach ($this->number_order as $number) {
            $numbers_called_so_far[] = $number;

            foreach ($this->boards as $index => $board) {
                // No need to check boards that have already won.
                if (in_array($index, $boards_won)) {
                    continue;
                }

                // Once a board wins, add it to the list of won boards.
                $bingo = $this->doesBoardHaveABingo($board, $numbers_called_so_far);
                if ($bingo) {
                    $boards_won[] = $index;
                }

                // When all boards have won, we know that the last board we checked is the board that won last,
                // so use it to calculate the score.
                if (count($boards_won) === count($this->boards)) {
                    $board_score = $this->calculateBoardScore($board, $numbers_called_so_far);
                    return $board_score * $number;
                }
            }
        }

        // This shouldn't happen unless the list of numbers or the boards are malformed.
        throw new Exception('Numbers exhausted without finding the worst board.');
    }
}
