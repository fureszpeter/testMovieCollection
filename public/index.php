<?php

/** @var Furesz\App $app */
$app = require_once __DIR__ . "/../src/bootstrap.php";

use Controller\DefaultController;

$controller = new DefaultController();
$res=$controller->indexAction();
foreach ($res as $v){
    var_dump($v);
}