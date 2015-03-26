<?php

namespace Entity;

interface IActor extends \JsonSerializable{

    /**
     * @return string
     */
    public function getName();

    /**
     * @return integer
     */
    public function getAge();

    /**
     * @return \DateTime
     */
    public function getDateOfBirth();

    /**
     * @return string
     */
    public function __toString();
}