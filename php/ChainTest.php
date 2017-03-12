<?php
require_once 'Chain.php';

class ChainTest extends \PHPUnit_Framework_TestCase
{
    public function testAdd()
    {
        $chain = new Chain();
        $chain = $chain->add(array(3, "4"));
        $block = $chain->toArray()[1];
        $this->assertEquals(array(3, "4"), $block->getData());
    }

    public function testSize()
    {
        $chain = new Chain();
        $chain->add(null)->add(null);
        $this->assertEquals(3, $chain->size());
    }

    public function testToString()
    {
        $chain = new Chain();
        $this->assertTrue(is_string($chain->toString()));
    }

    public function testToArray()
    {
        $chain = new Chain();
        $this->assertTrue(is_array($chain->toArray()));
    }

    public function testMerge()
    {
        $chainShort = new Chain();
        $chainLong = new Chain();
        $blocks = $chainLong->add("data")->toArray();
        $chainShort->merge($blocks);
        $blocks = $chainShort->toArray();
        $this->assertEquals(null, $blocks[0]->getData());
        $this->assertEquals("data", $blocks[1]->getData());
    }

    public function testMergeExceptionChain()
    {
        $this->setExpectedException('InvalidArgumentException', 'Invalid chain');
        $chainShort = new Chain();
        $chainLong = new Chain();
        $chainLong->add(null)->add(null)->merge($chainShort->toArray());
    }

    public function testMergeExceptionBlockHash()
    {
        $this->setExpectedException('InvalidArgumentException', 'Invalid block hash');
        $chain = new Chain();
        $blocks = serialize($chain->add(null)->toArray());
        $blocks = str_replace('ec09', 'dc09', $blocks);
        $blocks = unserialize($blocks);
        $chain->merge($blocks);
    }

    public function testMergeExceptionBlockType()
    {
        $this->setExpectedException('InvalidArgumentException', 'Invalid block type');
        $chain = new Chain();
        $blocks = $chain->toArray();
        array_push($blocks, array());
        $chain->merge($blocks);
    }

    public function testMergeExceptionBlockGenesis()
    {
        $this->setExpectedException('InvalidArgumentException', 'Invalid genesis block');
        $chain = new Chain();
        $blocks = serialize($chain->add(null)->toArray());
        $blocks = str_replace('ad94', 'ad95', $blocks);
        $blocks = unserialize($blocks);
        $chain->merge($blocks);
    }
}
