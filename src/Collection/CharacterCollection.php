<?php

namespace Collection;

use ValueObject\ICharacter;

class CharacterCollection extends \ArrayIterator{
    /**
     * @param ICharacter $object
     * @param null $data
     *
     * @return void
     */
    public function append($object)
    {
        if (!$object instanceof ICharacter){
            throw new \InvalidArgumentException("We accept ICharacter implementations only!");
        }
        parent::append($object);
    }
}