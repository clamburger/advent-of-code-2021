<?php

namespace App\Puzzles;

class Probe
{
    public int $id;

    public array $position = ['x' => 0, 'y' => 0];
    public array $target;
    public array $velocity;
    public array $journey = [];
    public int $highest_point = 0;

    public bool $finished = false;
    public bool $success;

    public function __construct(int $id, int $x_velocity, int $y_velocity, array $target)
    {
        $this->id = $id;
        $this->velocity['x'] = $x_velocity;
        $this->velocity['y'] = $y_velocity;
        $this->target = $target;
    }

    public function nextTick()
    {
        $this->position['x'] += $this->velocity['x'];
        $this->position['y'] += $this->velocity['y'];

        $this->journey[] = $this->position;
        $this->highest_point = max($this->position['y'], $this->highest_point);

        if ($this->velocity['x'] !== 0) {
            $this->velocity['x'] += ($this->velocity['x'] > 0 ? -1 : 1);
        }

        $this->velocity['y'] -= 1;

        $assessment = $this->assessPosition($this->position['x'], $this->position['y']);

//        printf("%-10s  %-12s  %s [x %s, y %s]\n", $this->position['x'] . ", " . $this->position['y'], "{$this->velocity['x']}m/s, {$this->velocity['y']}m/s", $assessment[0], $assessment[1], $assessment[2]);

        if ($assessment[0] === 'success') {
            $this->success = true;
            $this->finished = true;
        } elseif ($assessment[0] === 'failure') {
            $this->success = false;
            $this->finished = true;
        }
    }

    public function simulate(): bool
    {
        while (!$this->finished) {
            $this->nextTick();
        }

        return $this->success;
    }

    private function assessPosition(int $x, int $y)
    {
        if ($x < $this->target['x1']) {
            $x_pos = 'not yet';
        } elseif ($x > $this->target['x2']) {
            $x_pos = 'too far';
        } else {
            $x_pos = 'in range';
        }

        if ($y > $this->target['y2']) {
            $y_pos = 'not yet';
        } elseif ($y < $this->target['y1']) {
            $y_pos = 'too far';
        } else {
            $y_pos = 'in range';
        }

        if ($x_pos === 'in range' && $y_pos === 'in range') {
            $result = 'success';
        } elseif ($x_pos === 'too far' || $y_pos === 'too far') {
            $result = 'failure';
        } else {
            $result = 'pending';
        }

        return [$result, $x_pos, $y_pos];
    }
}

class Day17TrickShot extends AbstractPuzzle
{
    protected static int $day_number = 17;

    private array $target;

    private int $probe_count;
    private int $highest_probe;
    private array $successful_probes;

    public function __construct()
    {
        parent::__construct();
        preg_match('/target area: x=([0-9-]+)..([0-9-]+), y=([0-9-]+)..([0-9-]+)/', $this->input->lines[0], $matches);

        $this->target = [
            'x1' => $matches[1],
            'x2' => $matches[2],
            'y1' => $matches[3],
            'y2' => $matches[4],
        ];

        $this->fireAllProbes();
    }

    private function fireAllProbes()
    {
        $this->probe_count = 0;
        $this->highest_probe = 0;
        $this->successful_probes = [];

        for ($x = 0; $x <= $this->target['x2']; $x++) {
            for ($y = abs($this->target['y1']) * 2; $y >= $this->target['y1']; $y--) {
                $this->probe_count++;
//                echo "Probe $this->probe_count: $x, $y\n";
                $probe = new Probe($this->probe_count, $x, $y, $this->target);
                $success = $probe->simulate();

                if ($success) {
//                    echo "Probe $this->probe_count: $x, $y\n";
                    $this->highest_probe = max($this->highest_probe, $probe->highest_point);
                    $this->successful_probes[] = $probe;
                }
            }
        }
    }

    public function getPartOneAnswer(): int
    {
        return $this->highest_probe;
    }

    public function getPartTwoAnswer(): int
    {
        return count($this->successful_probes);
    }
}
