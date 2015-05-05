<?php
/**
 * Created by PhpStorm.
 * User: Maria
 * Date: 17-04-15
 * Time: 11:02
 */

class MaintenanceManager {
    private $_db;

    public function __construct() {
        $this->_db = ConnexionMySQL::getInstance()->getDbh();
        $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getByIdBox($idBox) {
        $requete = $this->_db->prepare('select * from maintenance where idBox = :idBox');
        $requete->bindValue(':idBox', $idBox);
        $requete->execute();

        $result = $requete->fetch(PDO::FETCH_BOTH);
        if($result)
            $maintenance = new Maintenance($result);
        else
            $maintenance = new Maintenance(array('idBox' => 0, 'idStep' => 0));

        return $maintenance;
    }

    public function update($idBox, $idStep, $idLastUser) {
        $requete = $this->_db->prepare('select modifMaintenance(:idBox, :idStep, :idLastUser)');
        $requete->bindValue(':idBox', $idBox);
        $requete->bindValue(':idStep', $idStep);
        $requete->bindValue(':idLastUser', $idLastUser);
        $requete->execute();

        $result = $requete->fetch();
        return $result[0];
    }

    public function getList() {
        $requete = $this->_db->prepare('select * from maintenance order by idBox');
        $requete->execute();

        $result = $requete->fetchAll(PDO::FETCH_ASSOC);
        $lesMaintenances = array();
        foreach ($result as $donnee) {
            $lesMaintenances[] = new Maintenance($donnee);
        }

        return $lesMaintenances;
    }
}