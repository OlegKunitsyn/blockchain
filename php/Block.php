<?php

final class Block
{
    /**
     * @var string
     */
    private $previousHash;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var string
     */
    private $hash;

    /**
     * @param string $previousHash
     * @param mixed $data
     * @param string $hash
     */
    public function __construct($previousHash, $data, $hash)
    {
        $this->previousHash = (string)$previousHash;
        $this->data = $data;
        $this->hash = (string)$hash;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getPreviousHash()
    {
        return $this->previousHash;
    }
}
