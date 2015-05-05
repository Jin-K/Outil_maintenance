<?php
/**
 * Created by PhpStorm.
 * User: Maria
 * Date: 15-04-15
 * Time: 13:22
 */

class Box {
    private $_id;
    private $_name;
    private $_model;

    public function __construct(array $donnees) {
        $this->hydrate($donnees);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->_model;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model)
    {
        $this->_model = $model;
    }



    private function hydrate(array $donnees) {
        foreach($donnees as $key => $value) {
            $setter = 'set' . ucfirst($key);
            if(method_exists($this, $setter))
                $this->$setter($value);
        }
    }
}