<?php

// https://adventofcode.com/2023/day/4
echo array_reduce(
    explode(PHP_EOL, trim(file_get_contents(__DIR__ . '/' . basename(__FILE__, '.php') . (isset($argv[1]) ? '.test.txt' : '.input.txt')))),
    function ($carry, $item) {

        if (!preg_match('/^Card\s+[0-9]+:(.+)\|(.+)$/', $item, $matches)) {
            throw new Exception(sprintf('Invalid row: %s', $item));
        }

        return $carry +
            (
                count(
                    $winnerNumbers = array_intersect(
                        preg_split('/\s+/', trim($matches[1])),
                        preg_split('/\s+/', trim($matches[2]))
                    )
                ) > 0 ?
                pow(2, count($winnerNumbers) - 1) :
                0
            );
    },
    0
);
