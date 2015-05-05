<?php
/**
 * Created by PhpStorm.
 * User: Maria
 * Date: 15-04-15
 * Time: 13:53
 */
session_start();
define('__ROOT__', dirname(__FILE__));
spl_autoload_extensions(".class.php, .php");
spl_autoload_register(function ($class) {
    if(is_file(__ROOT__ .  '/classe/' . $class . '.class.php'))
        require_once(__ROOT__ .  '/classe/' . $class . '.class.php');
    else
        require_once(__ROOT__ .  '/classe/manager/' . $class . '.class.php');
});

//if(isset($_COOKIE['login']))
//    $_SESSION['login'] = $_COOKIE['login']; //C'est peut-être pas la meilleure solution car quelqu'un de vif pourrait aller modifier son cookie
                                            //pour être connnecté comme un autre utilisateur. à ameilliorer
//$_SESSION['login'] = 'seelis';

$userManager = new UserManager();
?>
<!DOCTYPE html>
<html>
    <head lang="en">
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="img/icon/icon.jpg">

        <title>Maintenance of the sharingbox fleet</title>

        <!-- CSS BOOTSTRAP -->
        <link href="bootstrap-3.3.4/css/bootstrap.css" rel="stylesheet">
        <!-- <link href="bootstrap-3.3.4/css/bootstrap-theme.min.css" rel="stylesheet"> -->

        <!-- CSS PERSO -->
        <link href="style.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-2.1.3.js"></script>
    <script src="js/jquery.form.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap-3.3.4/js/bootstrap.js"></script>

    <body role="document">

        <div class="row">
            <ul class="nav nav-tabs" id="myTab">
                <li class="<?php if(!isset($_GET['page']) || $_GET['page'] == 'maintenance') echo 'active'; ?> small"><a href="?page=maintenance">Maintenance</a></li>
                <li class="<?php if(isset($_GET['page']) && $_GET['page'] == 'boxes') echo 'active'; ?> small"><a href="?page=boxes">Boxes List</a></li>
                <?php if(!isset($_SESSION['login'])) { ?>
                <li class="<?php if(isset($_GET['page']) && $_GET['page'] == 'registration') echo 'active'; ?> small"><a href="?page=registration">Registration/Connection</a></li>
                <?php }
                    else {
                        $user = $userManager->getByLogin($_SESSION['login']);
                        if($user->getAdmin() == 1) {
                ?>
                <li class="<?php if(isset($_GET['page']) && $_GET['page'] == 'admin') echo 'active'; ?> small"><a href="?page=admin">Administration</a></li>
                <?php
                        }
                ?>
                <li class="small"><a href="?page=disconnect">Disconnect</a></li>
                <?php } ?>
            </ul>
        </div>
        <div class="container" role="main">
            <div class="tab-content">
                <?php
                if(isset($_GET['page']) && ($_GET['page'] == 'boxes' || $_GET['page'] == 'registration' || $_GET['page'] == 'disconnect' || $_GET['page'] == 'admin')) {
                    if($_GET['page'] == 'boxes')
                        include('php/boxes.php');
                    elseif($_GET['page'] == 'registration')
                        include('php/registration.php');
                    elseif($_GET['page'] == 'disconnect')
                        include('php/disconnect.php');
                    elseif($_GET['page'] == 'admin') {
                        if(isset($_SESSION['login']) && $userManager->getByLogin($_SESSION['login'])->getAdmin() == 1)
                            include('php/administration.php');
                        else
                            header('Location: index.php?page=registration');
                    }
                }
                else
                    include('php/maintenance.php');
                ?>
            </div>
        </div>


        <!-- Scripts perso -->
        <script type="text/javascript"></script>
    </body>
</html>