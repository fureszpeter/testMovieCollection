<?php
require_once __DIR__ . "/../vendor/autoload.php";

use Furesz\App;

$app = App::getInstance();
$app->setAppRoot(__DIR__ . "/../");

return $app;
