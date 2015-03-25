<?php

namespace Tests;

use Furesz\App;

class TestCase extends \PHPUnit_Framework_TestCase{

    public function __construct()
    {
        /** @var App $app */
        $app = App::getInstance();
        $app->bootstrap();
    }
}
