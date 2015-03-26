<?php

namespace Tests\Entity;

use Entity\Actor;
use Tests\TestCase;

class ActorTest extends TestCase
{

    /**
     * @param Actor $actor
     * @param $expected
     *
     * @dataProvider actorProvider
     */
    public function testCreateNewObjectWithValidValues(Actor $actor, array $expected)
    {
        $this->assertInstanceOf(Actor::class, $actor);
        $this->assertEquals($expected["name"], $actor->getName());
        $this->assertEquals($expected["age"], $actor->getAge());
        $this->assertEquals($expected["dob"], $actor->getDateOfBirth());
    }

    /**
     * @return array
     */
    public function actorProvider()
    {
        return [
            [
                new Actor("Bruce Willis", $dob = new \DateTime("now - 50 years")),
                ["name" => "Bruce Willis", "age" => 50, "dob" => $dob]
            ],
            [
                new Actor("Pamela Anderson", $dob = new \DateTime("now - 40 years")),
                ["name" => "Pamela Anderson", "age" => 40, "dob" => $dob]
            ],
            [
                new Actor("David Hasselfhoff", $dob = new \DateTime("now - 55 years")),
                ["name" => "David Hasselfhoff", "age" => 55, "dob"=>$dob]
            ],
        ];
    }

}