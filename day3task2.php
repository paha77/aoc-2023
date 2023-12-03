<?php

$width = 0;
$height = 0;

$symbols = [];
$numbers = [];

$allDirections = [
    'left' => [[-1, 0]],
    'right' => [[1, 0]],
    'top' => [[-1, -1], [0, -1], [1, -1]],
    'bottom' => [[-1, 1], [0, 1], [1, 1]],
];

// read the input
foreach (explode(PHP_EOL, trim(file_get_contents(__DIR__ . '/' . basename(__FILE__, '.php') . (isset($argv[1]) ? '.test.txt' : '.input.txt')))) as $line) {

    $actualNumber = null;

    for ($column=0; $column < mb_strlen($line); $column++) {
        $symbol = mb_substr($line, $column, 1);

        // number
        if (preg_match('/[0-9]/', $symbol)) {
            if (is_null($actualNumber)) {
                $actualNumber = $symbol;
            } else {
                $actualNumber .= $symbol;
            }
            continue;
        } else {
            // number end
            if (!is_null($actualNumber)) {
                // save it to all coordinates
                for ($i = $column - mb_strlen($actualNumber); $i < $column; $i++) {
                    $numbers[$i][$height] = [$actualNumber, $column, $height];
                }
                $actualNumber = null;
            }
        }

        // skip the empty cell
        if ($symbol === '.') {
            continue;
        }
        // *
        if ($symbol === '*') {
            $symbols[$column][$height] = $symbol;
        }
    }

    // number end
    if (!is_null($actualNumber)) {
        // save it to all coordinates
        for ($i = $column - mb_strlen($actualNumber); $i < $column; $i++) {
            $numbers[$i][$height] = [$actualNumber, $column, $height];
        }
    }

    $width = max(mb_strlen($line), $width);
    $height++;
}

$sum = 0;
foreach ($symbols as $x => $column) {
    foreach ($column as $y => $symbol) {
        $neighbours = [];
        foreach ($allDirections as $direction => $directions) {
            foreach ($directions as $direction) {
                $neighbourX = $x + $direction[0];
                $neighbourY = $y + $direction[1];
                if (isset($numbers[$neighbourX][$neighbourY])) {
                    $neighbour = $numbers[$neighbourX][$neighbourY];
                    $neighbours[$neighbour[1] . 'x' . $neighbour[2]] = $neighbour[0];
                }
            }
        }
        if (count($neighbours) === 2) {
            $neighbours = array_values($neighbours);
            $sum += $neighbours[0] * $neighbours[1];
        }
    }
}

echo $sum . PHP_EOL;
