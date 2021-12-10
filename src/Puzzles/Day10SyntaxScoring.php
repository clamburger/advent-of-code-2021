<?php

namespace App\Puzzles;

class Day10SyntaxScoring extends AbstractPuzzle
{
    protected static int $day_number = 10;

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
            if ($valid !== true) {
                $total_score += $scores[$valid];
            }
        }

        return $total_score;
    }

    private function checkSyntax(string $line): bool|string
    {
        $chars = str_split($line);
        $stack = [];

        $pairs = [
            '(' => ')',
            '[' => ']',
            '{' => '}',
            '<' => '>',
        ];

        $reverse_pairs = array_flip($pairs);

        foreach ($chars as $char) {
            if (isset($pairs[$char])) {
                $stack[] = $char;
                continue;
            }

            $last_added = array_pop($stack);
            if ($reverse_pairs[$char] !== $last_added) {
                return $char;
            }
        }

        return true;
    }

    public function getPartTwoAnswer(): int
    {
        return 0;
    }
}
