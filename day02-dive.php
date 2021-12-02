<?php
$commands = file(__DIR__ . '/day02.input.txt');
$commands = array_map('trim', $commands);

/**
 *
 * Part One: get your final horizontal position multiplied by your final depth
 *
 */

$horizontal_position = 0;
$depth = 0;

foreach ($commands as $command) {
    [$direction, $distance] = explode(' ', $command);
    $distance = (int) $distance;

    switch ($direction) {
        case 'forward':
            $horizontal_position += $distance;
            break;
        case 'up':
            $depth -= $distance;
            break;
        case 'down':
            $depth += $distance;
            break;
    }
}

$multiplied_position = $horizontal_position * $depth;

echo $multiplied_position . "\n";
