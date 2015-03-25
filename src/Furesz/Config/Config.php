<?php
namespace Furesz\Config;


use Dotenv;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Symfony\Component\Finder\Finder;

class Config extends Repository
{
    /** @var  Finder */
    private $finder;

    /** @var string */
    private $configRoot;

    /** @var  bool */
    private $isLoaded;

    /** @var string */
    private $envPath;

    /**
     * @param Finder $finder
     * @param $configRoot
     */
    function __construct(Finder $finder, $configRoot, $envPath = null)
    {
        $this->isLoaded = false;
        $this->finder = $finder;
        $this->configRoot = $configRoot;
        $this->envPath = $envPath;

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    public function get($key, $default = null)
    {
        if (!$this->isLoaded) {
            $this->loadConfigFiles();
        }

        return parent::get($key, $default);
    }

    /**
     * @return string
     */
    public function getConfigRoot()
    {
        return $this->configRoot;
    }

    /**
     * @param string $configPath
     */
    public function setConfigRoot($configPath)
    {
        $this->configRoot = $configPath;
    }

    /**
     * @param string $envPath
     */
    public function setEnvPath($envPath)
    {
        $this->envPath = $envPath;
    }

    /**
     * @param array $files
     *
     * @return void
     */
    private function loadConfigFiles(array $files = [])
    {
        $path = $this->getConfigRoot();
        $environment = $this->getEnvironment();

        $configFiles = $this->getConfigFiles($path, $environment);

        foreach ($configFiles as $fileKey => $path) {
            if ($files==[] || $files != [] && in_array($fileKey, $files)){
                $this->set($fileKey, $this->parseConfigFile($path));
            }
        }
    }

    /**
     * @param $path
     * @param string $environment
     *
     * @return mixed
     * @throws FileNotFoundException
     */
    private function getConfigFiles($path, $environment = "")
    {
        if (!is_dir($path . "/" . $environment)) {
            throw new FileNotFoundException("Path not exists: " . $path . "/" . $environment);
        }

        $phpFiles = $this->finder->create()->files()->name("*.php")->in($path . "/" . $environment)->depth(0);

        $files =[];
        /** @var Finder $file */
        foreach ($phpFiles as $file) {
            $files[basename($file->getRealPath(), '.php')] = $file->getRealPath();
        }

        return $files;
    }

    /**
     * @param $path
     */
    public function getEnvironment($path = null)
    {
        $path = ($path ?: $this->envPath);

        if (!$path) {
            return "";
        }

        try {
            \Dotenv::load($path);

            return trim(getenv('ENVIRONMENT')) ?: 'production';
        } catch (\Exception $e) {
            return "";
        }
    }

    /**
     * @param $path
     *
     * @return array
     */
    private function parseConfigFile($path)
    {
        //@TODO do some security check here
        $configArray = include($path);

        return $configArray;
    }
}