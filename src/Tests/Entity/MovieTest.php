<?php

namespace Tests\Entity;

use Collection\CharacterCollection;
use Collection\ContractCollection;
use Entity\Actor;
use Entity\IAutoIncrement;
use Entity\Movie;
use Exception\Validation\ValidationException;
use Tests\TestCase;
use ValueObject\ActorMovieContract;

class MovieTest extends TestCase
{

    public function testCreateNewEntityWithValidData()
    {
        $data = [
            Movie::FIELD_RELEASE_DATE => new \DateTime(),
            Movie::FIELD_RUNTIME      => new \DateInterval("PT1H"),
            Movie::FIELD_TITLE        => "Test Movie",
        ];

        $contractCollection = $this->getContractCollection();

        $movie = new Movie($this->getMockSequence(1, 123), $data, $contractCollection);
        $this->assertInstanceOf(Movie::class, $movie);
        $this->assertEquals(123, $movie->getId());
    }

    /**
     * @expectedException \Exception\Validation\ValidationException
     * @expectedExceptionMessage Empty collection not allowed
     */
    public function testCreateNewEntityWithEmptyCollection()
    {
        $data = [
            Movie::FIELD_RELEASE_DATE => new \DateTime(),
            Movie::FIELD_RUNTIME      => new \DateInterval("PT1H"),
            Movie::FIELD_TITLE        => "Test Movie",
        ];

        $contractCollection = $this->getContractCollection(true);

        $movie = new Movie($this->getMockSequence(0), $data, $contractCollection);
        $this->assertInstanceOf(Movie::class, $movie);
    }


    /**
     * @expectedException \Exception\Validation\ValidationException
     * @dataProvider invalidDataProvider
     */
    public function testCreateEntityWithInvalidDTO(array $dto, $expectedException, $message)
    {
        $contractCollection = $this->getContractCollection();
        $this->setExpectedException($expectedException, $message);

        new Movie($this->getMockSequence(0), $dto, $contractCollection);
    }

    public function invalidDataProvider()
    {
        return [
            [
                [
                    Movie::FIELD_RELEASE_DATE => new \DateTime("now - 1 year"),
                    Movie::FIELD_RUNTIME      => new \DateInterval("PT30M"),
                    Movie::FIELD_TITLE        => "Invalid Title because of this sign: %",   //Invalid title
                ],
                ValidationException::class,
                "Title"
            ],
            [
                [
                    Movie::FIELD_RELEASE_DATE => new \DateTime("now - 1 year"),
                    Movie::FIELD_RUNTIME      => new \DateInterval("PT3H"), //Invalid runtime, too long movie
                    Movie::FIELD_TITLE        => "Valid Title",
                ],
                ValidationException::class,
                "runtime"
            ],
            [
                [
                    Movie::FIELD_RELEASE_DATE => new \DateTime("now + 1 year"), //Future release not allowed
                    Movie::FIELD_RUNTIME      => new \DateInterval("PT1H"),
                    Movie::FIELD_TITLE        => "Valid Title",
                ],
                ValidationException::class,
                "release"
            ],
        ];

    }

    /**
     * @param bool $empty
     *
     * @return ContractCollection
     */
    private function getContractCollection($empty = false)
    {
        $actor = new Actor($this->getMockSequence(1), "Mock Actor", new \DateTime("now - 50 years"));
        $characterCollection = new CharacterCollection();

        $contractCollection = new ContractCollection();
        if ($empty === false) {
            $contractCollection->append(new ActorMovieContract($actor, $characterCollection));
        }

        return $contractCollection;
    }

    /**
     * @param $execTimes
     * @param int $return
     *
     * @return IAutoIncrement
     */
    public function getMockSequence($execTimes, $return = 1)
    {
        $sequence = $this->getMock(IAutoIncrement::class);
        $sequence->expects($this->exactly($execTimes))
            ->method("getNextVal")
            ->willReturn($return);

        return $sequence;
    }
}