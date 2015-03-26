<?php

namespace Entity;

abstract class AbstractEntity implements \JsonSerializable
{
    const FIELD_ID = "id";

    /** @var  integer */
    protected $id;

    function __construct(IAutoIncrement $sequence)
    {
        $this->id = $sequence->getNextVal();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    abstract function getRequiredFields();

    /**
     * @return array
     */
    abstract protected function getSerializableFields();

    /**
     * @return string
     */
    public function jsonSerialize()
    {
        return json_encode($this->getSerializableFields());
    }
}