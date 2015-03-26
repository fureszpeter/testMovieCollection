<?php

namespace Entity;


class Actor extends AbstractEntity implements IActor{

    /** @var  string */
    private $name;

    /** @var  \DateTime */
    private $dateOfBirth;

    function __construct($name, \DateTime $dateOfBirth)
    {
        $this->name = $name;
        $this->dateOfBirth = $dateOfBirth;
    }

    function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }

    /** string */
    public function getName()
    {
        return $this->name;
    }

    /** integer */
    public function getAge()
    {
        return $this->getDateOfBirth()->diff(new \DateTime())->format("%Y");
    }

    /** \DateTime */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }
}