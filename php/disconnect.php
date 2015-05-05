<?php
/**
 * Created by PhpStorm.
 * User: Maria
 * Date: 21-04-15
 * Time: 13:49
 */
$_SESSION=array();
session_destroy();
if(isset($_COOKIE['login']))
    setcookie('login', NULL, time() - 3600*24*7, '/', NULL, 0); //suppression du cookie
?>
    <div id="hiddenRow" class="row" style="display: none; margin-top: 50px;">
        <div class="col-sm-6 col-sm-offset-3 col-md-offset-4 col-md-4">
            <div class="alert alert-warning text-center" role="alert">Disconnecting, Redirection ...</div>
        </div>
    </div>
    <script>
        $("#hiddenRow").fadeIn(600);

        setTimeout(function () {
            window.location.href = "index.php?page=registration&action=connection";
        }, 2000); //Redirection après 2.5 secondes
    </script>