<?php
/**
 * Created by PhpStorm.
 * User: Maria
 * Date: 15-04-15
 * Time: 15:37
 */

class StepManager {
    private $_db;

    public function __construct() {
        $this->_db = ConnexionMySQL::getInstance()->getDbh();
        $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getList() {
        $requete = $this->_db->prepare('select * from step order by step');
        $requete->execute();

        $result = $requete->fetchAll(PDO::FETCH_ASSOC);
        $lesSteps = array();
        foreach($result as $donnee) {
            $lesSteps[] = new Step($donnee);
        }
        return $lesSteps;
    }

    public function getByStep($step) {
        $requete = $this->_db->prepare('select * from step where step = :step');
        $requete->bindValue(':step', $step);
        $requete->execute();

        $result = $requete->fetch(PDO::FETCH_BOTH);
        $step = new Step($result);
        return $step;
    }

    public function update($step) {
        $requete = $this->_db->prepare('update step set title=:title, content=:content where step=:step ');
        $requete->bindValue(':step', $step->getStep());
        $requete->bindValue(':content', $step->getContent());
        $requete->bindValue(':title', $step->getTitle());
        $requete->execute();
    }

    public function add($step) {
        $requete = $this->_db->prepare('select count(*) from step'); //Compte nombre de steps
        $requete->execute();

        $result = $requete->fetch();
        $nombreDeSteps = $result[0];

        for ($i=$nombreDeSteps; $i>=$step->getStep(); $i--) {
            $requete = $this->_db->prepare('update step set step=:newStep where step=:oldStep');
            $requete->bindValue(':newStep', $i+1);
            $requete->bindValue(':oldStep', $i);
            $requete->execute();
        }

        $requete = $this->_db->prepare('insert into step values(null, :step, :title, :content)');
        $requete->bindValue(':step', $step->getStep());
        $requete->bindValue(':title', $step->getTitle());
        $requete->bindValue(':content', $step->getContent());
        $requete->execute();
    }

    public function del($step) {
        //On compte le nombre d'étapes existantes
        $requete = $this->_db->prepare('select count(*) from step'); //Compte nombre de steps
        $requete->execute();
        $result = $requete->fetch();
        $nombreDeSteps = $result[0];

        //On supprime l'étape passée en paramètre
        $requete = $this->_db->prepare('delete from step where step = :step');
        $requete->bindValue(':step', $step->getStep());
        $requete->execute();

        //On descend le numéro d'étape de chaque étape depuis celle qui a été supprimée
        for($i=$step->getStep(); $i<$nombreDeSteps; $i++) {
            $requete = $this->_db->prepare('update step set step=:newStep where step=:oldStep');
            $requete->bindValue(':newStep', $i);
            $requete->bindValue(':oldStep', $i+1);
            $requete->execute();
        }
    }
}