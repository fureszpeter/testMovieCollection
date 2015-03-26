<?php

namespace Tests\Collection;

use Collection\CharacterCollection;
use Collection\ContractCollection;
use Entity\Actor;
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
        $actor = new Actor("Mock Actor", new \DateTime());
        $characterCollection = new CharacterCollection();
        $contract = new ActorMovieContract($actor, $characterCollection);

        return $contract;
    }
}