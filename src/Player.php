<?php

namespace Domino;

/**
 * Class Player
 * @package Domino
 */
class Player
{
    /** @var string Name of this player */
    private $name;

    /** @var TileSet The tiles we are holding. */
    private $tiles;

    /**
     * Player constructor.
     * @param string $name Name for the player
     * @param TileSet $tiles Our 7 starting tiles
     */
    public function __construct(string $name, TileSet $tiles)
    {
        $this->name = $name;
        $this->tiles = $tiles;
    }

    /**
     * @param $head int The left number on the board
     * @param $tail int The right number on the board
     * @return array A position and the matching tile or POSITION_NONE and null if no match is found
     */
    public function move($head, $tail) {
        $position = TileSet::POSITION_NONE;
        $result = null;
        // find the first matching tile
        /** @var Tile $tile */
        foreach ($this->tiles->all() as $key => $tile) {
            $position = $tile->matches($head, $tail);
            if ($position != TileSet::POSITION_NONE) {
                $result = $tile;
                $this->tiles->remove($key);
                break;
            }
        }
        return [$position, $result];
    }

    public function isOutOfTiles() {
        return $this->tiles->isEmpty();
    }

    public function prependTile(Tile $tile) {
        $this->tiles->prepend($tile);
    }

    public function __toString()
    {
        return $this->name;
    }


}