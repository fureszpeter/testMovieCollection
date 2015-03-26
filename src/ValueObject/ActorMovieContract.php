<?php

namespace ValueObject;

use Collection\CharacterCollection;
use Entity\Actor;

class ActorMovieContract implements IActorContract {

    /** @var Actor */
    private $Actor;

    /** @var  CharacterCollection */
    private $MovieCharacter;

    function __construct(Actor $Actor, CharacterCollection $MovieCharacter)
    {
        $this->Actor = $Actor;
        $this->MovieCharacter = $MovieCharacter;
    }

    /**
     * @return Actor
     */
    public function getActor()
    {
        // TODO: Implement getActor() method.
    }

    /** @return CharacterCollection */
    public function getCharacters()
    {
        // TODO: Implement getCharacters() method.
    }
}
