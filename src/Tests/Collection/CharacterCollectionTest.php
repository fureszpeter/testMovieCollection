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

        $character = new MovieCharacter("Terminator I");
        $character2 = new MovieCharacter("Terminator II");
        $collection->append($character);
        $collection->append($character2);

        $this->assertEquals($character, current($collection));
        $this->assertEquals($character2, next($collection));
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