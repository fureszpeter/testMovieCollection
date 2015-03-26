<?php

namespace ValueObject;

use Collection\CharacterCollection;
use Entity\Actor;

interface IActorContract {
    /**
     * @return Actor
     */
    public function getActor();

    /** @return CharacterCollection */
    public function getCharacters();
}
