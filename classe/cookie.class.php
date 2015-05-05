<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 07-04-15
 * Time: 20:55
 */

class Cookie {

    public function addCookie($nomCookie, $valeurCookie) {
        setcookie($nomCookie, $valeurCookie, time() + 3600*24*7, '/', NULL, 0); //duréé de 7 jours
    }

}