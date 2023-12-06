<?php

// https://adventofcode.com/2023/day/4#part2
/** @var Card[] $cards */
$cards = array_reduce(explode(PHP_EOL, trim(file_get_contents(__DIR__ . '/' . basename(__FILE__, '.php') . (isset($argv[1]) ? '.test.txt' : '.input.txt')))), function ($carry, $item) {
    $card = new Card($item);
    $carry[$card->number] = $card;
    return $carry;
}, []);

// filling up wonCards recursively
foreach ($cards as $i => $card) {
    for ($j = 1; $j <= $card->winners; $j++) {
        $card->wonCards[] = $cards[$i + $j];
    }
}

// counting cards
echo array_reduce($cards, function ($carry, $item) {
    return $carry + $item->count();
}, 0);

class Card {

    public ?int $number;
    public int $winners = 0;
    public ?array $wonCards = [];

    public function __construct($data)
    {
        if (!preg_match('/^Card\s+([0-9]+):(.+)\|(.+)$/', $data, $matches)) {
            throw new Exception(sprintf('Invalid row: %s', $data));
        }
        $this->number = (int)$matches[1];
        $this->winners = count(array_intersect(
            preg_split('/\s+/', trim($matches[2])),
            preg_split('/\s+/', trim($matches[3]))
        ));
    }

    /**
     * Recursive function to count itself and all won cards
     *
     * @return int
     */
    public function count(): int
    {
        return 1 + array_reduce($this->wonCards, function ($carry, $item) {
            return $carry + $item->count();
        }, 0);
    }
}
