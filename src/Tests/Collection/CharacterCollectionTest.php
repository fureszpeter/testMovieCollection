<?php

namespace Tests\Collection;

use Collection\CharacterCollection;
use stdClass;
use ValueObject\MovieCharacter;

class CharacterCollectionTest extends \PHPUnit_Framework_TestCase {

    public function testObjectCreationWithValidData()
    {
        $collection = new CharacterCollection();
        $this->assertInstanceOf(CharacterCollection::class, $collection);

        $character = new MovieCharacter();
        $collection->append($character);
        $current = current($collection);
        $this->assertEquals($character, $current);
    }

    /** @expectedException \InvalidArgumentException */
    public function testObjectCreationWithInvalidData()
    {
        $collection = new CharacterCollection();
        $this->assertInstanceOf(CharacterCollection::class, $collection);

        $contract = new stdClass();
        $collection->append($contract);
    }
}