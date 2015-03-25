<?php

use Furesz\Config\Config;
use Symfony\Component\Finder\Finder;

class ConfigTest extends PHPUnit_Framework_TestCase
{
    /** @var  Config */
    private $config;

    public function setUp()
    {
        $this->config = new Config(new Finder(), "");
    }

    /**
     * @expectedException Illuminate\Contracts\Filesystem\FileNotFoundException
     * @expectedExceptionMessage /nonExistsPath
     */
    public function testGetConfigValueWithInvalidPath()
    {
        $this->config->setConfigRoot("/nonExistsPath");
        $this->config->get("nonExistsFile.nonExistsKey");
    }

    public function testGetConfigValueWithValidKey()
    {
        $mockConfigValues = [
            "key1"=>"val2"
        ];
        $path = $this->getMockPath();
        $mockConfigFileName = "mockConfigFile";
        $this->saveMockConfig($path . "/$mockConfigFileName.php", $mockConfigValues);

        $this->config->setConfigRoot($path);
        $val1 = $this->config->get("{$mockConfigFileName}.key1");

        $this->assertSame("val2", $val1);
    }

    public function testGetConfigValueWithInValidKey()
    {
        $mockConfigValues = [
            "key1"=>"val2"
        ];
        $path = $this->getMockPath();
        $mockConfigFileName = "mockConfigFile";
        $this->saveMockConfig($path . "/$mockConfigFileName.php", $mockConfigValues);

        $this->config->setConfigRoot($path);
        $val1 = $this->config->get("{$mockConfigFileName}-invalid.key1");

        $this->assertSame(null, $val1);
    }

    public function testGetEnvironmentWithInvalidPath(){
        $path = $this->getMockPath();
        $path .= "/nonExistsDir";
        $environment = $this->config->getEnvironment($path);

        $this->assertEquals("", $environment);
    }

    public function testGetEnvironmentWithValidPath(){
        $path = $this->getMockPath();
        $mockEnvironment = "development";

        file_put_contents($path . "/.env", "ENVIRONMENT=$mockEnvironment");
        $environment = $this->config->getEnvironment($path);

        $this->assertEquals($mockEnvironment, $environment);
    }

    /**
     * @param $dir
     *
     * @return bool
     */
    private function unlinkRecursive($dir) {
        $files = array_diff(scandir($dir), ['.','..']);

        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->unlinkRecursive("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir);
    }
    /**
     * @return string
     */
    private function getMockPath()
    {
        $suffix = "/tmp";
        $path = sys_get_temp_dir();
        if (is_dir($path . $suffix)){
            if ($path . $suffix !== "/"){
                $this->unlinkRecursive($path . $suffix);
            }
        }

        mkdir($path . $suffix);

        return $path . $suffix;
    }

    /**
     * @param $path
     * @param $mockConfigValues
     */
    private function saveMockConfig($path, $mockConfigValues)
    {
        file_put_contents($path, "<?php return " . var_export($mockConfigValues, true) . ";");
    }
}