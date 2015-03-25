<?php
namespace Entity;

interface IAutoIncrement
{
    /**
     * Get the current ID
     *
     * @return int
     */
    public function getCurrentVal();

    /**
     * Increase the ID and return the increased value
     *
     * @return int
     */
    public function getNextVal();

}