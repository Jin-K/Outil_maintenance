<?php
/**
 * Created by PhpStorm.
 * User: Maria
 * Date: 15-04-15
 * Time: 09:55
 */

class ConnexionMySQL {

    //----------Options bdd----------
    private $_dbName = 'maintenance';
    //private $_dbName = 'heber_16005805_maintenance';
    private $_dbHost = '127.0.0.1';
    //private $_dbHost = 'sql113.hebergratuit.net';
    private $_dbPort = '';
    private $_user = 'root';
    //private $_user = 'heber_16005805';
    private $_password = '';
    //private $_password = '8kswolegna3';
    //--------------------------------
    //-----------Singleton------------
    private $_dbh;
    private static $_instance=null;
    //--------------------------------

    private function __construct() {
        try {
            $dsn = 'mysql:dbname=' . $this->_dbName . '; host=' . $this->_dbHost . '; port=' . $this->_dbPort;
            $this->_dbh = new PDO($dsn, $this->_user, $this->_password);
        }
        catch (PDOException $e) {
            echo 'Echec de la connexion : ' . $e->getMessage();
        }
    }
    public static function getInstance() {
        if(is_null(self::$_instance))
            self::$_instance = new ConnexionMySQL();

        return self::$_instance;
    }
    public function getDbh() {
        return $this->_dbh;
    }
}