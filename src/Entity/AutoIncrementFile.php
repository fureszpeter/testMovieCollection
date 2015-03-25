<?php

namespace Entity;

class AutoIncrementFile extends AutoIncrement
{
    /** @var string */
    private $dbPath;

    function __construct($dbPath)
    {
        $this->dbPath = $dbPath;
    }

    /**
     * @param string $dbPath
     * @throws \Exception
     */
    public function setDbPath($dbPath)
    {
        if ($dbPath=="" || is_dir(dirname($dbPath))){
            throw new \Exception("Invalid DB Path: " . $dbPath);
        }

        $this->dbPath = $dbPath;
    }


    /**
     * @return bool
     * @throws \Exception
     */
    protected function save($id)
    {
        $this->validateDBPath();
        file_put_contents($this->dbPath, $id);

        return true;
    }

    /**
     * @return int
     */
    protected function load()
    {
        if (!file_exists($this->dbPath)) {
            $this->initDb();
        }

        $this->validateDBPath();
        $this->setCurrentId((int)file_get_contents($this->dbPath));

        return $this->getCurrentId();
    }

    protected function validateDBPath()
    {
        if ($this->dbPath == "") {
            throw new \Exception("DB File path cannot be empty");
        }

        if (
            file_exists($this->dbPath) && !is_numeric(file_get_contents($this->dbPath))
        ) {
            throw new \Exception("DB File is not exists or invalid, please delete: " . $this->dbPath);
        }
    }
}