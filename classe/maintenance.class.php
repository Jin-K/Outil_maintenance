<?php
/**
 * Created by PhpStorm.
 * User: Maria
 * Date: 17-04-15
 * Time: 10:59
 */

class Maintenance {
    private $_idBox;
    private $_idStep;
    private $_idLastUser;

    public function __construct(array $donnees) {
        $this->hydrate($donnees);
    }

    /**
     * @return mixed
     */
    public function getIdBox()
    {
        return $this->_idBox;
    }

    /**
     * @return mixed
     */
    public function getIdStep()
    {
        return $this->_idStep;
    }

    /**
     * @return mixed
     */
    public function getIdLastUser()
    {
        return $this->_idLastUser;
    }

    /**
     * @param mixed $idBox
     */
    public function setIdBox($idBox)
    {
        $this->_idBox = $idBox;
    }

    /**
     * @param mixed $idStep
     */
    public function setIdStep($idStep)
    {
        $this->_idStep = $idStep;
    }

    /**
     * @param mixed $idLastUser
     */
    public function setIdLastUser($idLastUser)
    {
        $this->_idLastUser = $idLastUser;
    }

    private function hydrate(array $donnees) {
        foreach ($donnees as $key => $value) {
            $setter = 'set' . ucfirst($key);
            if(method_exists($this, $setter))
                $this->$setter($value);
        }

    }
}