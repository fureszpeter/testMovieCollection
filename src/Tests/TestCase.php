<?php

namespace Tests;

use Furesz\App;

class TestCase extends \PHPUnit_Framework_TestCase{
    /** @var App */
    protected $app;

    public function __construct()
    {
        $this->app = require_once(__DIR__ . "/../bootstrap.php");
    }
}
