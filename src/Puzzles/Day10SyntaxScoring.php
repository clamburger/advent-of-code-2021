<?php

namespace App\Puzzles;

class Day10SyntaxScoring extends AbstractPuzzle
{
    protected static int $day_number = 10;

    private array $valid_lines = [];

    private array $pairs;
    private array $reverse_pairs;

    public function __construct()
    {
        parent::__construct();

        $this->pairs = [
            '(' => ')',
            '[' => ']',
            '{' => '}',
            '<' => '>',
        ];

        $this->reverse_pairs = array_flip($this->pairs);
    }

    public function getPartOneAnswer(): int
    {
        $scores = [
            ')' => 3,
            ']' => 57,
            '}' => 1197,
            '>' => 25137
        ];

        $total_score = 0;

        foreach ($this->input->lines as $line) {
            $valid = $this->checkSyntax($line);
            if (is_string($valid)) {
                $total_score += $scores[$valid];
            } else {
                $this->valid_lines[] = $line;
            }
        }

        return $total_score;
    }

    private function checkSyntax(string $line): array|string
    {
        $chars = str_split($line);
        $stack = [];

        foreach ($chars as $char) {
            if (isset($this->pairs[$char])) {
                $stack[] = $char;
                continue;
            }

            $last_added = array_pop($stack);
            if ($this->reverse_pairs[$char] !== $last_added) {
                return $char;
            }
        }

        return $stack;
    }

    public function getPartTwoAnswer(): int
    {
        $all_scores = [];

        foreach ($this->valid_lines as $line) {
            $all_scores[] = $this->scoreLine($line);
        }

        sort($all_scores);

        return $all_scores[count($all_scores) / 2 - 0.5];
    }

    private function scoreLine(string $line): int
    {
        $score = 0;

        $scores = [
            ')' => 1,
            ']' => 2,
            '}' => 3,
            '>' => 4,
        ];

        $stack = $this->checkSyntax($line);
        while (!empty($stack)) {
            $top_of_stack = array_pop($stack);
            $end_char = $this->pairs[$top_of_stack];

            $score *= 5;
            $score += $scores[$end_char];
        }

        return $score;
    }
}
