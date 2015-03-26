<?php
namespace Tests\Entity;


use Entity\AutoIncrementFile;
use Furesz\App;
use Furesz\Config\Config;
use Symfony\Component\Finder\Finder;
use Tests\TestCase;

class AutoIncrementTest extends TestCase{

    public function testGetFirstValueWithNewDB()
    {
        $dbPath = $this->getDBPath() . ".test";
        $this->resetDB($dbPath);
        $autoIncrement = new AutoIncrementFile($dbPath);

        $this->assertEquals(1, $autoIncrement->getNextVal());
        $this->assertEquals(2, $autoIncrement->getNextVal());
        $this->assertEquals(3, $autoIncrement->getNextVal());

        $this->assertEquals(3, $autoIncrement->getCurrentVal());

        $autoIncrement2 = new AutoIncrementFile($dbPath);
        $this->assertEquals(4, $autoIncrement2->getNextVal());

        $this->assertEquals(4, $autoIncrement->getCurrentVal());

    }

    /**
     * @return string
     */
    protected function getConfigPath()
    {
        return App::getInstance()->getAppRoot() . "/config";
    }

    /**
     * @return string
     */
    protected function getDBPath()
    {
        $config = new Config(new Finder(), $this->getConfigPath());
        $config->setEnvPath(App::getInstance()->getAppRoot());

        $dbPath = App::getInstance()->getAppRoot() . "/" . $config->get("dataStore.dataStorePath");

        return $dbPath;
    }

    /**
     * @param $dbPath
     */
    protected function resetDB($dbPath)
    {
        if (file_exists($dbPath)){
            unlink($dbPath);
        }
    }
}