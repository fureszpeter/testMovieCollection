<?php

namespace Tests\Entity;

use Collection\CharacterCollection;
use Collection\ContractCollection;
use Entity\Actor;
use Entity\Movie;
use Tests\TestCase;
use ValueObject\ActorMovieContract;

class MovieTest extends TestCase{

    public function testCreateNewEntityWithValidData()
    {
        $data = [
            Movie::FIELD_RELEASE_DATE => new \DateTime(),
            Movie::FIELD_RUNTIME => new \DateInterval("PT1H"),
            Movie::FIELD_TITLE => "Test Movie",
        ];

        $contractCollection = $this->getContractCollection();

        $movie = new Movie($data, $contractCollection);
        $this->assertInstanceOf(Movie::class, $movie);
    }

    /**
     * @expectedException \Exception\Validation\ValidationException
     * @expectedExceptionMessage Empty collection not allowed
     */
    public function testCreateNewEntityWithEmptyCollection()
    {
        $data = [
            Movie::FIELD_RELEASE_DATE => new \DateTime(),
            Movie::FIELD_RUNTIME => new \DateInterval("PT1H"),
            Movie::FIELD_TITLE => "Test Movie",
        ];

        $contractCollection = $this->getContractCollection(true);

        $movie = new Movie($data, $contractCollection);
        $this->assertInstanceOf(Movie::class, $movie);
    }


    /**
     * @expectedException \Exception\Validation\ValidationException
     */
    public function testCreateEntityWithInvalidDTO()
    {
        $data = [
            Movie::FIELD_RELEASE_DATE => "badFormat",
            Movie::FIELD_RUNTIME => 30,
            Movie::FIELD_TITLE => "make % char invalid here",
        ];

        $contractCollection = $this->getContractCollection();

        new Movie($data, $contractCollection);
    }

    /**
     * @param bool $empty
     *
     * @return ContractCollection
     */
    private function getContractCollection($empty = false)
    {
        $actor = new Actor("Mock Actor", new \DateTime());
        $characterCollection = new CharacterCollection();

        $contractCollection = new ContractCollection();
        if ($empty === false){
            $contractCollection->append(new ActorMovieContract($actor, $characterCollection));
        }

        return $contractCollection;
    }
}