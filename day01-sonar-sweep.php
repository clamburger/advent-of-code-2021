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



/**
 *
 * Part Two: count the number of times the depth increases using a three-measurement sliding window
 *
 */

$deeper_measurements = 0;

$measurement_window = [];

foreach ($depths as $index => $depth) {
    $previous_measurement = array_sum($measurement_window);
    $measurement_window[] = $depth;

    if ($index <= 2) {
        // We need a full measurement window before we can start comparing
        continue;
    }

    array_shift($measurement_window);
    $current_measurement = array_sum($measurement_window);

    if ($current_measurement > $previous_measurement) {
        $deeper_measurements++;
    }
}

echo $deeper_measurements . "\n";
