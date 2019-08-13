<?php

namespace Domino;

/**
 * Handles a set of tiles for our boar, stock and players
 *
 * Class TileSet
 * @package Domino
 */
class TileSet
{
    // Constants for positioning on the board, left = head, right = tail
    const POSITION_NONE = 0;
    const POSITION_HEAD = 1;
    const POSITION_TAIL = 2;

    /** @var array The tiles we are holding. */
    private $tiles;

    /**
     * Add's a tile at the appropriate position
     *
     * @param $position
     * @param $tile
     * @throws \Exception
     */
    public function add($position, $tile) {
        switch ($position) {
            case self::POSITION_NONE:
                throw new \Exception("Adding $tile in an unknown position");
            case self::POSITION_HEAD:
                $this->prepend($tile);
                break;
            case self::POSITION_TAIL:
                $this->append($tile);
                break;
        }
    }

    /**
     * TileSet constructor.
     * @param array $tiles Array of Tile's
     */
    public function __construct(array $tiles = [])
    {
        $this->tiles = $tiles;
    }

    /**
     * Add a tile to the start
     *
     * @param Tile $tile
     * @return TileSet
     */
    public function prepend(Tile $tile)
    {
        array_unshift($this->tiles, $tile);
        return $this;
    }

    /**
     * Add a tile at the end
     *
     * @param Tile $tile
     * @return TileSet
     */
    public function append(Tile $tile)
    {
        $this->tiles[] = $tile;
        return $this;
    }

    /**
     * Gets a random tile and removes it from the set
     *
     * @return Tile
     */
    public function getRandomTile(): Tile
    {
        $result = $this->getRandomTiles(1);
        return $result[0];
    }

    /**
     * Gets an amount of random tiles and removes them from the set
     *
     * @param $amount
     * @return array
     */
    public function getRandomTiles($amount): array
    {
        $result = [];
        $keys = array_rand($this->tiles, $amount);
        if ($amount == 1) {
            $keys = [$keys];
        }
        foreach ($keys as $key) {
            $result[] = $this->tiles[$key];
            unset($this->tiles[$key]);
        }
        return $result;
    }

    public function isEmpty() {
        return empty($this->tiles);
    }

    public function head() {
        return $this->tiles[0]->head();
    }

    public function tail() {
        return $this->tiles[count($this->tiles) - 1]->tail();
    }

    public function remove($key) {
        if (isset($this->tiles[$key])) {
            unset($this->tiles[$key]);
        }
    }

    public function all() {
        return $this->tiles;
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        return implode(' ', $this->tiles);
    }
}