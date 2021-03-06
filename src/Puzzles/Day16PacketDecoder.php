<?php

namespace App\Puzzles;

class Day16PacketDecoder extends  AbstractPuzzle
{
    const PACKET_TYPES = [
        0 => 'sum',
        1 => 'product',
        2 => 'minimum',
        3 => 'maximum',
        4 => 'literal',
        5 => 'greater than',
        6 => 'less than',
        7 => 'equal to'
    ];

    protected static int $day_number = 16;

    private string $bits;

    public function __construct()
    {
        parent::__construct();
        $this->bits = $this->packetToBits($this->input->lines[0]);
    }

    private function getPacketValue(array $packet): int
    {
        $subvalues = array_column($packet['subpackets'], 'value');

        return match ($packet['type_id']) {
            0 => array_sum($subvalues),
            1 => array_product($subvalues),
            2 => min($subvalues),
            3 => max($subvalues),
            5 => $subvalues[0] > $subvalues[1],
            6 => $subvalues[0] < $subvalues[1],
            7 => $subvalues[0] == $subvalues[1],
        };
    }

    private function parsePacket(string $bits)
    {
        $packet = [];
        $packet['raw'] = $bits;
        $packet['length'] = strlen($bits);
        $packet['version'] = $this->bitsToNumber($this->shiftBits($bits, 3));
        $packet['type_id'] = $this->bitsToNumber($this->shiftBits($bits, 3));
        $packet['type'] = self::PACKET_TYPES[$packet['type_id']];

        if ($packet['type_id'] === 4) {
            $packet['value'] = $this->parseLiteralValue($bits);
            $packet['unprocessed'] = $bits;
            return $packet;
        }

        $packet['type'] = 'operator';
        $packet['length_type_id'] = $this->shiftBits($bits, 1);
        $packet['subpackets'] = [];

        if ($packet['length_type_id'] === '0') {
            $packet['subpacket_length'] = $this->bitsToNumber($this->shiftBits($bits, 15));
            $unprocessed_bits = $this->shiftBits($bits, $packet['subpacket_length']);

            while (!empty($unprocessed_bits)) {
                $subpacket = $this->parsePacket($unprocessed_bits);
                $unprocessed_bits = $subpacket['unprocessed'];
                $packet['subpackets'][] = $subpacket;
            }
        } else {
            $packet['subpacket_count'] = $this->bitsToNumber($this->shiftBits($bits, 11));

            while (count($packet['subpackets']) < $packet['subpacket_count']) {
                $subpacket = $this->parsePacket($bits);
                $bits = $subpacket['unprocessed'];
                $packet['subpackets'][] = $subpacket;
            }
        }

        $packet['value'] = $this->getPacketValue($packet);
        $packet['unprocessed'] = $bits;
        return $packet;

    }

    private function shiftBits(string &$bits, int $number): string
    {
        $shifted_bits = substr($bits, 0, $number);
        $bits = substr($bits, $number);
        return $shifted_bits;
    }

    private function parseLiteralValue(string &$bits): int
    {
        $number = '';

        while (true) {
            $five_bits = $this->shiftBits($bits, 5);
            $instruction = $this->shiftBits($five_bits, 1);

            $number .= $five_bits;
            if ($instruction === '0') {
                break;
            }
        }

        return $this->bitsToNumber($number);
    }

    private function bitsToNumber(string $bits): int
    {
        return base_convert($bits, 2, 10);
    }

    private function packetToBits(string $packet): string
    {
        $packets = '';
        for ($i = 0; $i < strlen($packet); $i++) {
            $binary = str_pad(base_convert($packet[$i], 16, 2), 4, '0', STR_PAD_LEFT);
            $packets .= $binary;
        }

        return $packets;
    }

    public function getVersionSum(array $packet)
    {
        if (empty($packet['subpackets'])) {
            return $packet['version'];
        }

        $sum = $packet['version'];

        foreach ($packet['subpackets'] as $subpacket) {
            $sum += $this->getVersionSum($subpacket);
        }

        return $sum;
    }

    public function getPartOneAnswer(): int
    {
        $packet = $this->parsePacket($this->bits);
        return $this->getVersionSum($packet);
    }

    public function getPartTwoAnswer(): int
    {
        $packet = $this->parsePacket($this->bits);
        return $packet['value'];
    }
}
