<?php
/**
 * Created by PhpStorm.
 * User: Maria
 * Date: 15-04-15
 * Time: 11:20
 */
spl_autoload_extensions(".class.php, .php");
spl_autoload_register();

class UserManager {

    private $_db;

    public function __construct() {
        $this->_db = ConnexionMySQL::getInstance()->getDbh();
        $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getList() {
        $requete = $this->_db->prepare('select * from user order by id');
        $requete->execute();

        $result = $requete->fetchAll(PDO::FETCH_ASSOC);
        $lesUsers = array();
        foreach($result as $donnee) {
            $lesUsers[] = new User($donnee);
        }
        return $lesUsers;
    }

    public function getById($id) {
        $requete = $this->_db->prepare('select * from user where id = :id');
        $requete->bindValue(':id', $id);
        $requete->execute();

        $result = $requete->fetch(PDO::FETCH_BOTH);
        $user = new User($result);
        return $user;
    }

    public function getCountById($idUser) {
        $requete = $this->_db->prepare('select count(*) from user where id like :id');
        $requete->bindValue(':id', $idUser);
        $requete->execute();

        $result = $requete->fetch();
        return $result[0];
    }

    public function getByLogin($login) {
        $requete = $this->_db->prepare('select * from user where login like :login');
        $requete->bindValue(':login', $login);
        $requete->execute();

        $result = $requete->fetch(PDO::FETCH_BOTH);
        $user = new User($result);
        return $user;
    }

    public function getCountByLoginAndPassword($login, $password) {
        $passwordChiffre = md5($password); //chiffrement

        $requete = $this->_db->prepare('select count(*) from user where login like :login and pw like :password');
        $requete->bindValue(':login', $login);
        $requete->bindValue(':password', $passwordChiffre);
        $requete->execute();

        $result = $requete->fetch();
        return $result[0];
    }

    public function add($user) {
        $requete = $this->_db->prepare('select ajoutUser(:login, :loginBO, :pw)');
        $requete->bindValue(':login', $user->getLogin());
        $requete->bindValue(':loginBO', $user->getLoginBackOffice());
        $requete->bindValue(':pw', $user->getPw());
        $requete->execute();

        $result = $requete->fetch(PDO::FETCH_BOTH);
        return $result[0];
    }

}