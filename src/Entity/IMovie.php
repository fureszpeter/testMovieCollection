<?php

namespace Entity;

interface IMovie {
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
}