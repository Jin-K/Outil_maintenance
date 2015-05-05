<?php
/**
 * Created by PhpStorm.
 * User: Maria
 * Date: 15-04-15
 * Time: 10:26
 */

class User {
    private $_id;
    private $_login;
    private $_pw;
    private $_loginBackOffice;
    private $_admin;

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
    public function getLogin()
    {
        return $this->_login;
    }

    /**
     * @return mixed
     */
    public function getPw()
    {
        return $this->_pw;
    }

    /**
     * @return mixed
     */
    public function getLoginBackOffice()
    {
        return $this->_loginBackOffice;
    }

    /**
     * @return mixed
     */
    public function getAdmin()
    {
        return $this->_admin;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->_login = $login;
    }

    /**
     * @param mixed $pw
     */
    public function setPw($pw)
    {
        $this->_pw = $pw;
    }

    /**
     * @param mixed $loginBackOffice
     */
    public function setLoginBackOffice($loginBackOffice)
    {
        $this->_loginBackOffice = $loginBackOffice;
    }

    /**
     * @param mixed $admin
     */
    public function setAdmin($admin)
    {
        $this->_admin = $admin;
    }

    private function hydrate(array $donnees) {
        foreach($donnees as $key => $value) {
            $setter = 'set' . ucfirst($key);
            if(method_exists($this, $setter))
                $this->$setter($value);
        }
    }
}