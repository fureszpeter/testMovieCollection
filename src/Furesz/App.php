<?php

namespace Furesz;

class App extends Singleton{

    /**
     * @return void
     */
    public function bootstrap(){
        $this->setTimezone();
    }

    /**
     * @return void
     */
    private function setTimezone()
    {
        if (ini_get("date.timezone")) {
            ini_set("date.timezone", "Europe/Budapest");
        }
    }

}