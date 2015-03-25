<?php

namespace Entity;

use Furesz\Singleton;

abstract class AutoIncrement implements IAutoIncrement
{
    /** @var  integer */
    private $currentId;

    /**
     * @return bool
     * @throws \Exception
     */
    abstract protected function save($id);

    /**
     * @return int
     */
    abstract protected function load();

    /**
     * @return int
     */
    protected function getCurrentId()
    {
        return (int)$this->currentId;
    }

    /**
     * @return int
     * @throws \Exception
     */
    protected function initDb()
    {
        $initValue = "0";
        $this->save($initValue);

        return $initValue;
    }

    /**
     * @param int $currentId
     *
     * @return bool
     * @throws \Exception
     */
    protected function setCurrentId($currentId)
    {
        if (!is_integer($currentId)) {
            throw new \Exception("Id is not integer: " . $currentId);
        }
        $this->currentId = $currentId;

        return true;
    }

    /**
     * Get the current ID
     *
     * @return int
     */
    public function getCurrentVal()
    {
        $this->load();

        return $this->currentId;
    }

    /**
     * Increase the ID and return the increased value
     *
     * @return int
     * @throws \Exception
     */
    public function getNextVal()
    {
        if (!$this->currentId) {
            $this->load();
        }

        $id = $this->currentId + 1;
        if ($this->save($id)) {
            $this->currentId = $id;
        }

        return $id;
    }
}
