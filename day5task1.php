<?php

// https://adventofcode.com/2023/day/5

// read Almanac
$almanac = new Almanac();
$currentMap = null;
foreach (file(__DIR__ . '/' . basename(__FILE__, '.php') . (isset($argv[1]) ? '.test.txt' : '.input.txt')) as $line) {
    // seeds
    if (preg_match('/^seeds: (.+)$/', $line, $matches)) {
        foreach (explode(' ', $matches[1]) as $seed) {
            $almanac->addSeed((int) $seed);
        }
        continue;
    }

    if (preg_match('/^(.+) map:$/', $line, $matches)) {
        // adding actual map to almanac
        if ($currentMap) {
            $almanac->addMap($currentMap);
        }
        $currentMap = new Map($matches[1]);
        continue;
    }

    if (preg_match('/^(\d+) (\d+) (\d+)$/', $line, $matches)) {
        $currentMap->addRange(new Range((int) $matches[2], (int) $matches[1], (int) $matches[3]));
    }
}
// last map
if ($currentMap) {
    $almanac->addMap($currentMap);
}

echo $almanac->calculateLowestLocation() . PHP_EOL;

class Range
{
    private int $length = 0;
    private int $sourceStart = 0;
    private int $destinationStart = 0;

    public function __construct(int $sourceStart, int $destinationStart, int $length)
    {
        $this->length = $length;
        $this->sourceStart = $sourceStart;
        $this->destinationStart = $destinationStart;
    }

    public function lookUpSeedNumber(int $seedNumber): ?int
    {
        if ($seedNumber < $this->sourceStart || $seedNumber > $this->sourceStart + $this->length - 1) {
            return null;
        }

        return $this->destinationStart + ($seedNumber - $this->sourceStart);
    }

    public function getSourceStart(): int
    {
        return $this->sourceStart;
    }

    public function getDestinationStart(): int
    {
        return $this->destinationStart;
    }
}

class Map
{
    private string $name;
    private array $ranges = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addRange(Range $range): self
    {
        $this->ranges[] = $range;

        // re-sort ranges
        $this->sortRanges();

        return $this;
    }

    private function sortRanges(): void
    {
        usort($this->ranges, function (Range $a, Range $b) {
            return $a->getSourceStart() <=> $b->getSourceStart();
        });
    }

    public function lookUpSeedNumber(int $seedNumber): ?int
    {
        foreach ($this->ranges as $range) {
            if ($destinationSeedNumber = $range->lookUpSeedNumber($seedNumber)) {
                return $destinationSeedNumber;
            }
        }

        return $seedNumber;
    }
}

class Almanac
{
    private array $seeds = [];

    private array $maps = [];

    public function addMap(Map $map): self
    {
        $this->maps[] = $map;

        return $this;
    }

    public function addSeed(int $seed): self
    {
        $this->seeds[] = $seed;

        return $this;
    }

    private function calculateLocation(int $seedNumber): int
    {
        foreach ($this->maps as $map) {
            $seedNumber = $map->lookUpSeedNumber($seedNumber);
        }

        return $seedNumber;
    }

    public function calculateLowestLocation(): int
    {
        return array_reduce($this->seeds, function ($lowestLocation, int $seed) {
            if ($lowestLocation === 0) {
                return $this->calculateLocation($seed);
            }
            return min($lowestLocation, $this->calculateLocation($seed));
        }, 0);
    }
}
