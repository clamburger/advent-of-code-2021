<?php
$depths = file(__DIR__ . '/day01.input.txt');
$depths = array_map('trim', $depths);

/**
 *
 * Part One: count the number of times the depth increases
 *
 */

$previous_depth = null;

$deeper_depths = 0;

foreach ($depths as $depth) {
    if ($previous_depth !== null && $depth > $previous_depth) {
        $deeper_depths++;
    }

    $previous_depth = $depth;
}

echo $deeper_depths . "\n";
