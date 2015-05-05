<?php
/**
 * Created by PhpStorm.
 * User: Maria
 * Date: 17-04-15
 * Time: 14:58
 */
$boxManager = new BoxManager();
$maintenanceManager = new MaintenanceManager();
$stepManager = new StepManager();
$userManager = new UserManager();

$stepsCount = sizeof($stepManager->getList());
$lesBoxes = $boxManager->getList();
?>
<div class="row">
    <div class="col-lg-6 col-lg-offset-3" style="margin-top: 20px;">
        <table class="table table-hover table-condensed" style="color: #000">
            <thead style="color: #CCC">
                <tr>
                    <th>Box name</th>
                    <th>Model</th>
                    <th>Maintenance step</th>
                    <th>Last maintainer</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($lesBoxes as $box) {
                $maintenance = $maintenanceManager->getByIdBox($box->getId());

                if($maintenance->getIdStep() == $stepsCount)
                    $tableRowColor = 'label-success';
                elseif($maintenance->getIdStep() > 0)
                    $tableRowColor = 'label-warning';
                else
                    $tableRowColor = 'label-danger';

                if($userManager->getCountById($maintenance->getIdLastUser()) > 0) {
                    $user = $userManager->getById($maintenance->getIdLastUser());
                    $lastMaintainer = $user->getLoginBackOffice();
                }
                else
                    $lastMaintainer = 'No one';

            ?>
                <tr class="<?php echo $tableRowColor; ?>">
                    <td><?php echo $box->getName(); ?></td>
                    <td><?php echo $box->getModel(); ?></td>
                    <td>
                        <?php if($maintenance->getIdStep() < $stepsCount) {
                            if(isset($_SESSION['login'])) {
                        ?>
                        <a href="?page=maintenance&amp;step=<?php echo $maintenance->getIdStep()+1; ?>&amp;box=<?php echo $box->getId(); ?>" class="lienSteps" style="color: #000; text-decoration: none;"
                           onclick="return confirm('Do you really want to continue this maintenance, from step <?php echo $maintenance->getIdStep()+1; ?> ?')">
                        <?php
                            }
                            else {
                        ?>
                        <a href="?page=registration&amp;action=connection" class="lienSteps" style="color: #000; text-decoration: none;" onclick="return alert('Connect or register to be able to maintain a box')">
                        <?php
                            }
                            echo $maintenance->getIdStep() . '/' . $stepsCount; ?>
                        </a>
                        <?php }
                        else
                            echo $maintenance->getIdStep() . '/' . $stepsCount;
                        ?>
                    </td>
                    <td><?php echo $lastMaintainer; ?></td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    /*
    $('a[class=lienSteps]').click(function(e) {
        //alert('click sur un lien');

    });
    function clickBox($idBox) {
        alert("voulez vous faire la maintenance de la box "+idBox+"?");
    }
    */
</script>