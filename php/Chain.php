<?php
require_once 'Block.php';

final class Chain
{
    /**
     * @var array
     */
    private $blocks;

    /**
     * Genesis block
     */
    public function __construct()
    {
        $this->blocks = array(new Block("", null, $this->hash(0, "", null)));
    }

    /**
     * @param mixed $data
     * @return Chain
     */
    public function add($data)
    {
        $lastBlock = end($this->blocks);
        array_push(
            $this->blocks,
            new Block($lastBlock->getHash(), $data, $this->hash($lastBlock->getHash(), $data))
        );
        return $this;
    }

    /**
     * @return integer
     */
    public function size()
    {
        return count($this->blocks);
    }

    /**
     * @param array $blocks
     * @return Chain
     */
    public function merge(array $blocks)
    {
        $blocks = array_values($blocks);
        if (count($blocks) < $this->size()) {
            throw new \InvalidArgumentException("Invalid chain");
        }
        if ($blocks[0]->getHash() !== $this->blocks[0]->getHash()) {
            throw new \InvalidArgumentException("Invalid genesis block");
        }
        for ($i = 1; $i < count($blocks); $i++) {
            if (!$blocks[$i] instanceof Block) {
                throw new \InvalidArgumentException("Invalid block type");
            }
            if (!$this->isBlockValid($blocks[$i], $blocks[$i - 1])) {
                throw new \InvalidArgumentException("Invalid block hash");
            }
        }
        $this->blocks = $blocks;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array_values($this->blocks);
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->__toString();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return serialize($this->blocks);
    }

    /**
     * @param Block $block
     * @param Block $previousBlock
     * @return bool
     */
    private function isBlockValid(Block $block, Block $previousBlock)
    {
        if ($previousBlock->getHash() !== $block->getPreviousHash()) {
            return false;
        }
        if ($this->hash($block->getPreviousHash(), $block->getData()) !== $block->getHash()) {
            return false;
        }
        return true;
    }

    /**
     * @param string $previousHash
     * @param mixed $data
     * @return string
     */
    private function hash($previousHash, $data)
    {
        return hash('sha256',  hash('sha256', serialize($data)) . $previousHash);
    }

    private function __sleep()
    {
    }

    private function __wakeup()
    {
    }
}
