<?php

namespace Tests\Entity;

use Entity\Actor;
use Entity\IAutoIncrement;
use Exception\Validation\ValidationException;
use Tests\TestCase;

class ActorTest extends TestCase
{

    /**
     * @param Actor $actor
     * @param array $expected
     *
     * @dataProvider actorProvider
     */
    public function testCreateNewObjectWithValidValues(Actor $actor, array $expected)
    {
        $this->assertInstanceOf(Actor::class, $actor);
        $this->assertEquals($expected[Actor::FIELD_NAME], $actor->getName());
        $this->assertEquals($expected[Actor::FIELD_AGE], $actor->getAge());
        $this->assertEquals($expected[Actor::FIELD_DOB], $actor->getDateOfBirth());
        $this->assertEquals($expected[Actor::FIELD_ID], $actor->getId());
    }

    /**
     * @param $name
     * @param $dateOfBirth
     * @param $expectedException
     * @param $message
     *
     * @dataProvider invalidActorDataProvider
     */
    public function testCreateActorWithInvalidData($name, $dateOfBirth, $expectedException, $message)
    {
        $sequence = $this->getMock(IAutoIncrement::class);
        $sequence->expects($this->never())
            ->method("getNextVal");

        $this->setExpectedException($expectedException, $message);
        new Actor($sequence, $name, $dateOfBirth);
    }

    public function invalidActorDataProvider()
    {
        return [
            [
                "Invalid name because of this sign %",  //Invalid name
                new \DateTime("now - 20 years"),
                ValidationException::class, "name"
            ],
            [
                "Mel Gibson",
                new \DateTime("now - 1 year"),  //Invalid year, too young
                ValidationException::class, "Age"
            ],
        ];
    }

    /**
     * @return array
     */
    public function actorProvider()
    {
        $sequence1 = $this->getMock(IAutoIncrement::class);
        $sequence1->expects($this->once())
            ->method("getNextVal")
            ->willReturn(1);

        $sequence2 = $this->getMock(IAutoIncrement::class);
        $sequence2->expects($this->once())
            ->method("getNextVal")
            ->willReturn(1);

        $sequence3 = $this->getMock(IAutoIncrement::class);
        $sequence3->expects($this->once())
            ->method("getNextVal")
            ->willReturn(1);

        return [
            [
                new Actor($sequence1, "Bruce Willis", $dob = new \DateTime("now - 50 years")),
                [Actor::FIELD_NAME => "Bruce Willis", Actor::FIELD_AGE => 50, Actor::FIELD_DOB => $dob, Actor::FIELD_ID => 1]
            ],
            [
                new Actor($sequence2, "Pamela Anderson", $dob = new \DateTime("now - 40 years")),
                [Actor::FIELD_NAME => "Pamela Anderson", Actor::FIELD_AGE => 40, Actor::FIELD_DOB => $dob, Actor::FIELD_ID => 1]
            ],
            [
                new Actor($sequence3, "David Hasselfhoff", $dob = new \DateTime("now - 55 years")),
                [Actor::FIELD_NAME => "David Hasselfhoff", Actor::FIELD_AGE => 55, Actor::FIELD_DOB => $dob, Actor::FIELD_ID => 1]
            ],
        ];
    }

}