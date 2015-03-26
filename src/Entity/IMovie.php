<?php

namespace Entity;

interface IMovie
{
    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return \DateTime
     */
    public function getReleaseDate();

    /**
     * @return \DateInterval
     */
    public function getRuntime();

    /**
     * @param int $sortOrder
     *
     * @return Actor[]
     */
    public function getActors($sortOrder = SORT_DESC);

    /**
     * @return string
     */
    public function __toString();
}