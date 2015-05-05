<?php
/**
 * Created by PhpStorm.
 * User: Maria
 * Date: 15-04-15
 * Time: 11:26
 */
define('__ROOT__', dirname(dirname(__FILE__)));
spl_autoload_extensions(".class.php, .php");
spl_autoload_register(function ($class) {
    if(is_file(__ROOT__ .  '/classe/' . $class . '.class.php'))
        require_once(__ROOT__ .  '/classe/' . $class . '.class.php');
    else
        require_once(__ROOT__ .  '/classe/manager/' . $class . '.class.php');
});
$userManager = new UserManager();
$lesUsers = $userManager->getList();
foreach($lesUsers as $user) {
    echo $user->getLogin() . '<br>';
}

$boxManager = new BoxManager();
$lesBox = $boxManager->getList();
foreach($lesBox as $box) {
    echo $box->getNomBox() . '<br>';
}

$stepManager = new StepManager();
$lesSteps = $stepManager->getList();
foreach($lesSteps as $step) {
    echo $step->getTitle() . '<br>';
}

$helpManager = new HelpManager();
$lesHelps = $helpManager->getListByIdStep(1);
foreach($lesHelps as $help) {
    echo $help->getUrlImage() . '<br>';
}

$maintenanceManager = new MaintenanceManager();
$maintenance = $maintenanceManager->getByIdBox(1);
echo $maintenance->getIdStep();