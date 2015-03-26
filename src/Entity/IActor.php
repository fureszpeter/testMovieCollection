<?php

namespace Entity;

interface IActor extends \JsonSerializable{

    /** string */
    public function getName();

    /** integer */
    public function getAge();

    /** \DateTime */
    public function getDateOfBirth();
}