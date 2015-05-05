<?php
/**
 * Created by PhpStorm.
 * User: Maria
 * Date: 15-04-15
 * Time: 15:49
 */

class Help {
    private $_id;
    private $_idhelp;
    private $_idStep;
    private $_text;
    private $_urlImage;

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
    public function getIdStep()
    {
        return $this->_idStep;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->_text;
    }

    /**
     * @return mixed
     */
    public function getUrlImage()
    {
        return $this->_urlImage;
    }

    /**
     * @return mixed
     */
    public function getIdhelp()
    {
        return $this->_idhelp;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @param mixed $idStep
     */
    public function setIdStep($idStep)
    {
        $this->_idStep = $idStep;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->_text = $text;
    }

    /**
     * @param mixed $urlImage
     */
    public function setUrlImage($urlImage)
    {
        $this->_urlImage = $urlImage;
    }

    /**
     * @param mixed $idhelp
     */
    public function setIdhelp($idhelp)
    {
        $this->_idhelp = $idhelp;
    }

    private function hydrate(array $donnees) {
        foreach($donnees as $key => $value) {
            $setter = 'set' . ucfirst($key);
            if(method_exists($this, $setter))
                $this->$setter($value);
        }
    }
}