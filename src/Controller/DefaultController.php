<?php

namespace Controller;

use Furesz\App;

class DefaultController {

    public function indexAction()
    {
        echo App::getInstance()->getAppRoot();

        $movie = new Movie();
    }
}