<?php
/**
 * Created by PhpStorm.
 * User: Maria
 * Date: 21-04-15
 * Time: 16:21
 */
session_start();
define('__ROOT__', dirname(dirname(dirname(__FILE__))));
spl_autoload_extensions(".class.php, .php");
spl_autoload_register(function ($class) {
    if(is_file(__ROOT__ .  '/classe/' . $class . '.class.php'))
        require_once(__ROOT__ .  '/classe/' . $class . '.class.php');
    else
        require_once(__ROOT__ .  '/classe/manager/' . $class . '.class.php');
});

if(isset($_POST['step']) && is_numeric($_POST['step']) && isset($_POST['idBox']) && is_numeric($_POST['idBox'])) {
    $stepManager = new StepManager();
    $nombreDeSteps = sizeof($stepManager->getList());
    if($_POST['step'] <= $nombreDeSteps) {
        $userManager = new UserManager();
        $user = $userManager->getByLogin($_SESSION['login']);
        $maintenanceManager = new MaintenanceManager();
        $result = $maintenanceManager->update($_POST['idBox'], $_POST['step'], $user->getId());
    }
}