<?php

namespace Domino;

/**
 * The main Game class
 *
 * Class Game
 * @package Domino
 */
class Game
{

    /** @var bool Flag to check if we are done playing */
    private $finished = false;

    /** @var TileSet Tiles on the stock */
    private $stock;

    /** @var TileSet Tiles on the table */
    private $board;

    /** @var array Our favorite players, Alice and Bob */
    private $players;

    /**
     * Our main game loop
     */
    public function play()
    {
        $this->initialize();
        $this->output("Game starting with first tile: {$this->board}");

        while (!$this->finished) {
            /** @var Player $player */
            foreach ($this->players as $player) {
                try {
                    // each player gets a turn
                    $this->turn($player);

                    // let's show some output
                    $this->output("Board is now {$this->board}.");

                    // check our win conditions
                    $this->checkForWinner($player);

                    // check our stock for tiles
                    $this->checkForTilesInStock();

                    // and finish if we need to
                    if ($this->finished) {
                        break;
                    }
                } catch (\Exception $exception) {
                    // this should never happen, if it does, i screwed up :(
                    $this->output($exception->getMessage());
                }
            }
        }
    }

    /**
     * Runs a single turn for a player
     *
     * @param Player $player
     * @throws \Exception
     */
    private function turn(Player $player) {
        $tile = null;
        $position = TileSet::POSITION_NONE;
        // while we haven't found a matching tile and there are tiles in stock
        while (empty($tile) && !$this->stock->isEmpty()) {
            // try to move
            list($position, $tile) = $player->move($this->board->head(), $this->board->tail());
            // if no possible move
            if (empty($tile)) {
                // assign a tile from stock
                $tileFromStock = $this->stock->getRandomTile();
                $player->prependTile($tileFromStock);
                $this->output("$player can't play, drawing tile $tileFromStock");
            }
        }
        // If we found a matching tile, add it to the board
        if (!empty($tile)) {
            $this->output("$player plays $tile");
            $this->board->add($position, $tile);
        }
    }

    /**
     * Initializes players and sets up the stock and board.
     */
    private function initialize()
    {
        // Lets set up the stock with tiles from 0:0 to 6:6
        $this->stock = new TileSet();
        for ($tail = 0; $tail <= 6; $tail++) {
            for ($head = 0; $head <= $tail; $head++) {
                $tile = new Tile($head, $tail);
                $this->stock->append($tile);
            }
        }

        // set up the board
        $this->board = new TileSet();
        $this->board->append($this->stock->getRandomTile());

        // and then our players, Alice and Bob.
        $this->players[] = new Player(
            'Alice',
            new TileSet($this->stock->getRandomTiles(7))
        );
        $this->players[] = new Player(
            'Bob',
            new TileSet($this->stock->getRandomTiles(7))
        );
    }

    private function checkForWinner(Player $player)
    {
        if ($player->isOutOfTiles()) {
            // We have a winner!!
            $this->output("Player $player has won.");
            $this->finished = true;
        }
    }

    private function checkForTilesInStock()
    {
        if ($this->stock->isEmpty()) {
            $this->output("Looks like we are out of stock, nobody wins.");
            $this->finished = true;
        }
    }

    private function output($message)
    {
        echo "$message\n";
    }

}