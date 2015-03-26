<?php

namespace Collection;

use ValueObject\IActorContract;

class ContractCollection extends \ArrayIterator{
    /**
     * @param IActorContract $object
     * @param null $data
     */
    public function append($object)
    {
        if (!$object instanceof IActorContract){
            throw new \InvalidArgumentException("We accept IActorContract implementations only!");
        }

        parent::append($object);
    }
}
