<?php

namespace Domino;

/**
 * Class Tile
 * @package Domino
 */
class Tile
{
    protected $head;
    protected $tail;

    /**
     * Tile constructor.
     * @param $head
     * @param $tail
     */
    public function __construct($head, $tail)
    {
        $this->head = $head;
        $this->tail = $tail;
    }

    /**
     * Checks if we can place this tile, flips it if necessary
     *
     * @param $head int First number on the board
     * @param $tail int Last number on the board
     * @return int POSITION_HEAD or POSITION_TAIL if we found a match, else POSITION_NONE
     */
    public function matches($head, $tail) {
        // compare our tail to board's head
        if ($this->tail() === $head || $this->flip()->tail() === $head) {
            return TileSet::POSITION_HEAD;
        }
        // compare our head to board's tail
        if ($this->head() === $tail || $this->flip()->head() === $tail) {
            return TileSet::POSITION_TAIL;
        }
        return TileSet::POSITION_NONE;
    }

    /**
     * @return int Number on the left side
     */
    public function head() {
        return $this->head;
    }

    /**
     * @return int Number on the right side
     */
    public function tail() {
        return $this->tail;
    }

    /**
     * Flips the tile
     *
     * @return $this
     */
    public function flip()
    {
        $tempHead = $this->head;
        $this->head = $this->tail;
        $this->tail = $tempHead;
        return $this;
    }

    public function __toString()
    {
        return "<{$this->head}:{$this->tail}>";
    }


}