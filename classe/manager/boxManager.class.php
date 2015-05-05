<?php
/**
 * Created by PhpStorm.
 * User: Maria
 * Date: 15-04-15
 * Time: 13:34
 */

class BoxManager {
    private $_db;

    public function __construct() {
        $this->_db = ConnexionMySQL::getInstance()->getDbh();
        $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getList() {
        $requete = $this->_db->prepare('select * from box order by id');
        $requete->execute();

        $result = $requete->fetchAll(PDO::FETCH_ASSOC);
        $lesBox = array();
        foreach($result as $donnee) {
            $lesBox[] = new Box($donnee);
        }
        return $lesBox;
    }
}