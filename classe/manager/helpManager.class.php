<?php
/**
 * Created by PhpStorm.
 * User: Maria
 * Date: 15-04-15
 * Time: 15:52
 */

class HelpManager {
    private $_db;

    public function __construct() {
        $this->_db = ConnexionMySQL::getInstance()->getDbh();
        $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getList() {
        $requete = $this->_db->prepare('select * from help order by id');
        $requete->execute();

        $result = $requete->fetchAll(PDO::FETCH_ASSOC);
        $lesStepImage = array();
        foreach ($result as $donnee) {
            $lesStepImage[] = new Help($donnee);
        }
        return $lesStepImage;
    }

    public function getListByIdStep($idStep) {
        $requete = $this->_db->prepare('select * from help where idStep = :idStep order by id');
        $requete->bindValue(':idStep', $idStep);
        $requete->execute();

        $result = $requete->fetchAll(PDO::FETCH_ASSOC);
        $lesStepImage = array();
        foreach ($result as $donnee) {
            $lesStepImage[] = new Help($donnee);
        }
        return $lesStepImage;
    }

    public function getById($id) {
        $requete = $this->_db->prepare('select * from help where id = :id');
        $requete->bindValue(':id', $id);
        $requete->execute();

        $result = $requete->fetch(PDO::FETCH_BOTH);
        $help = new Help($result);

        return $help;
    }

    public function delByIdStep($idStep) {
        $requete = $this->_db->prepare('delete from help where idStep = :idStep');
        $requete->bindValue(':idStep', $idStep);
        $requete->execute();
        return $requete->rowCount(); //Nombre de helps supprimés
    }

    public function add($help) {
        $requete = $this->_db->prepare('insert into help values (null, :idHelp, :idStep, :text, :urlImage)');
        $requete->bindValue(':idHelp', $help->getIdHelp());
        $requete->bindValue(':idStep', $help->getIdStep());
        $requete->bindValue(':text', $help->getText());
        $requete->bindValue(':urlImage', $help->getUrlImage());
        $requete->execute();
    }
}