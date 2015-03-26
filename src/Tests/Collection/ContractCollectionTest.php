<?php

namespace Tests\Collection;

use Collection\CharacterCollection;
use Collection\ContractCollection;
use Entity\Actor;
use Entity\IAutoIncrement;
use stdClass;
use ValueObject\ActorMovieContract;

class ContractCollectionTest extends \PHPUnit_Framework_TestCase {

    public function testObjectCreationWithValidData()
    {
        $contract = $this->getContract();
        $collection = new ContractCollection();
        $collection->append($contract);

        $this->assertInstanceOf(ContractCollection::class, $collection);
        $this->assertEquals($contract, current($collection));
    }

    /** @expectedException \InvalidArgumentException */
    public function testObjectCreationWithInvalidData()
    {
        $collection = new ContractCollection();
        $this->assertInstanceOf(ContractCollection::class, $collection);

        $contract = new stdClass();
        $collection->append($contract);
    }

    /**
     * @return ActorMovieContract
     */
    private function getContract()
    {
        $sequence = $this->getMock(IAutoIncrement::class);
        $sequence->expects($this->once())
            ->method("getNextVal")
            ->willReturn(1);

        $actor = new Actor($sequence, "Mock Actor", new \DateTime("now - 50 years"));
        $characterCollection = new CharacterCollection();
        $contract = new ActorMovieContract($actor, $characterCollection);

        return $contract;
    }
}