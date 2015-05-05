<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 27-04-15
 * Time: 20:14
 */
define('__ROOT__', dirname(dirname(dirname(__FILE__))));
spl_autoload_extensions(".class.php, .php");
spl_autoload_register(function ($class) {
    if(is_file(__ROOT__ .  '/classe/' . $class . '.class.php'))
        require_once(__ROOT__ .  '/classe/' . $class . '.class.php');
    else
        require_once(__ROOT__ .  '/classe/manager/' . $class . '.class.php');
});

if(isset($_POST['step'])) {
    $stepManager = new StepManager();
    $helpManager = new HelpManager();
    $maintenanceManager = new MaintenanceManager();

    $step = $stepManager->getByStep($_POST['step']);
    $stepManager->del($step); //suppression de l'étape

    $nbHelpSupp = $helpManager->delByIdStep($step->getId()); //Suppréssion des aides

    //Modif des maintenances qui ont atteint ou dépassé cette étape
    $listeMaintenances = $maintenanceManager->getList();
    foreach ($listeMaintenances as $maintenance)
        if($maintenance->getIdStep() >= $_POST['step'])
            $maintenanceManager->update($maintenance->getIdBox(), $maintenance->getIdStep()-1, $maintenance->getIdLastUser());

    header('Content-Type: application/json');
    echo json_encode($nbHelpSupp);
}