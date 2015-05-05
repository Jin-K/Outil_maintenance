<?php
/**
 * Created by PhpStorm.
 * User: Maria
 * Date: 17-04-15
 * Time: 13:34
 */
$stepManager = new StepManager();
$maintenanceManager = new MaintenanceManager();
$lesSteps = $stepManager->getList();
$nombreSteps = sizeof($lesSteps);
?>
    <div class="page-header text-center" id="intro">
        <h1>IT maintenance of the sharingbox fleet</h1>
        <p>Before maintaining a sharingbox, you have to verify that all the components are connected. If one of the following devices (camera/webcam or printer..) is not connected, the maintenance remains do-able, because most of the procedures have to be done on the embarked screen/computer. An Internet connection is needed as well.</p>
    </div>

<?php
if(!isset($_GET['step'])) {
    if(isset($_SESSION['login'])) {
?>
    <div class="row text-center" id="stepsLaunch">
        <div class="col-lg-12">
            <p class="alert-info">Select a box to maintain and click on "Start maintenance"</p>
        </div>
        <div class="col-lg-12">
            <select class="input-sm" name="box" id="idSelectedBox" style="color: #1d5e86;">
                <?php
                $boxManager = new BoxManager();
                $lesBoxes = $boxManager->getList();
                foreach ($lesBoxes as $box) {
                    $maintenanceStep = $maintenanceManager->getByIdBox($box->getId())->getIdStep();
                    ?>
                    <option value="<?php echo $box->getId() . '-' . $maintenanceStep; ?>"><?php echo $box->getName() . ' (Step ' . $maintenanceStep . '/' . $nombreSteps . ')'; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div class="col-lg-12">
            <button type="button" class="btn btn-sm btn-warning" role="button" id="stepsLaunchButton" style="margin-top: 10px;">Start maintenance</button>
        </div>
    </div>
<?php
    } else {
?>
    <div class="row text-center">
        <div class="col-lg-12">
            <a href="?page=registration&amp;action=connection" class="btn btn-sm btn-info" style="margin-top: 10px;">Sign-in</a>
        </div>
    </div>
<?php
    }
}
?>

        <input type="hidden" id="nombreSteps" value="<?php echo $nombreSteps; ?>" />
        <input type="hidden" id="idBox" value="0" />
    <!------------------- STEPS START ---------------------->
    <?php
    $helpManager = new HelpManager();

    foreach ($lesSteps as $step) {
        if(($step->getId() % 2) == 1) //Si ligne impaire
            $rowColor = '#375D81';
        else                          //ligne paire
            $rowColor = '#303381';
    ?>
        <div id="step<?php echo $step->getStep(); ?>" class="row sr-only" style="border: solid 1px #ccc; padding: 0">
            <div class="col-lg-12" style="background: <?php echo $rowColor; ?>;">

                <div class="row" style="background: linear-gradient(to bottom, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0)  /*rgba(196, 215, 237, 0)*/);">
                    <div class="col-lg-12">
                        <div class="row" style="padding-left: 8px; text-decoration: underline;">
                            <b style="">Step <?php echo $step->getStep() . '/' . $nombreSteps; ?></b> : <?php echo $step->getTitle(); ?>
                        </div>
                        <div class="row">
                            <div class="col-lg-10" style="padding-left: 15px;">
                                <?php echo $step->getContent(); ?>
                            </div>
                            <div class="col-lg-2" style="padding: 15px;">
                                <div class="btn-group pull-right" data-toggle="buttons">
                                    <label id="undone<?php echo $step->getStep(); ?>" class="btn btn-xs btn-primary active">
                                        <input type="radio" name="options" class="undone" value="<?php echo $step->getStep(); ?>">Undone
                                    </label>
                                    <label id="done<?php echo $step->getStep(); ?>" class="btn btn-xs btn-primary">
                                        <input type="radio" name="options" class="done" value="<?php echo $step->getStep(); ?>">Done
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row sr-only" id="help<?php echo $step->getStep(); ?>" style="border-top: dotted 1px #ccc;">
                    <div class="col-lg-8 col-lg-offset-2">

                        <div id="carousel<?php echo $step->getStep(); ?>" class="carousel slide" style="border: solid 2px #ccc;">

                            <!-- Indicators -->
                            <ol class="carousel-indicators" >
                                <?php
                                $lesHelps = $helpManager->getListByIdStep($step->getId());
                                for($i=0; $i<sizeof($lesHelps); $i++) {
                                    ?>
                                    <li data-target="#carousel<?php echo $step->getStep(); ?>" data-slide-to="<?php echo $i; ?>" <?php if($i==0) echo 'class="active"'; ?>><?php echo ($i+1); ?></li>
                                <?php
                                }
                                ?>
                            </ol>

                            <!-- Wrapper for slides -->
                            <div class="carousel-inner" role="listbox">
                                <?php
                                for($i=0; $i<sizeof($lesHelps); $i++) {
                                    ?>
                                    <div class="item <?php if($i==0) echo "active"; ?>">
                                        <img src="<?php echo $lesHelps[$i]->getUrlImage(); ?>" alt="...">
                                        <div class="carousel-caption">
                                            <p><?php echo $lesHelps[$i]->getText(); ?></p>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>

                            <!-- Controls -->
                            <a class="left carousel-control" href="#carousel<?php echo $step->getStep(); ?>" role="button" data-slide="prev">
                                <span class="glyphicon glyphicon-chevron-left"></span>
                            </a>
                            <a class="right carousel-control" href="#carousel<?php echo $step->getStep(); ?>" role="button" data-slide="next">
                                <span class="glyphicon glyphicon-chevron-right"></span>
                            </a>

                            <!-- Buttons -->
                            <button id="pauseButton-<?php echo $step->getStep(); ?>" type="button" class="btn btn-default btn-xs pauseButton">
                                <span id="span<?php echo $step->getStep(); ?>" class="glyphicon glyphicon-pause"></span>
                            </button>

                        </div>

                    </div>
                </div>

            </div>
            <input type="hidden" id="nombreSteps" value="<?php echo $nombreSteps; ?>" />
        </div>
    <?php
    }
    ?>
    <!-------------------- STEPS END ----------------------->

    <script>
        //***********************************CAROUSEL***************************************//
        $('.carousel').carousel({
            interval: 7500 //7.5 secondes pour un slide auto
        });

        $('.pauseButton').click(function () {
            var id = $(this).attr('id').split('-');
            if($("#span" + id[1]).hasClass("glyphicon-pause")) {
                $('#carousel' + id[1]).carousel('pause');
                $("#span" + id[1]).removeClass("glyphicon-pause").addClass("glyphicon-play");
            }
            else {
                $('#carousel' + id[1]).carousel('cycle');
                $("#span" + id[1]).removeClass("glyphicon-play").addClass("glyphicon-pause");
            }
        });
        //**********************************************************************************//

        //***********************************LANCEMENT**************************************//
        $("#stepsLaunchButton").click(function() {
            var strSelectedBox = $("#idSelectedBox option:selected").val();
            var valuesSelectedBox = strSelectedBox.split("-");
            var idBox = valuesSelectedBox[0];
            var idStep = valuesSelectedBox[1];
            if(idStep != 0) {
                if(idStep == $("#nombreSteps").attr('value'))
                    --idStep;
                window.location.replace("index.php?page=maintenance&step="+(++idStep)+"&box="+idBox);
            }
            else {
                $("#stepsLaunch").addClass("sr-only");
                $("#step1").removeClass("sr-only");
                $("#help1").removeClass("sr-only");
                $("#idBox").attr("value", idBox);
                $("#idStep").attr("value", idStep);
                window.scrollTo(0,document.body.scrollHeight);
            }
        });
        //**********************************************************************************//

        //*************************************ROWS*****************************************//
        $(".undone").change(function() {
            var idStep = $(this).attr('value');
            $('#carousel' + idStep).carousel(0);
            $("#help" + idStep).removeClass("sr-only");
            window.scrollTo(0,document.body.scrollHeight);
            var nombreSteps = $("#nombreSteps").attr('value');
            updateMaintenance(idStep-1);
            for(var i=++idStep;i<=nombreSteps;i++) {
                $("#step" + i).addClass("sr-only");
                $("#undone" + i).addClass("active");
                $("#done" + i).removeClass("active");
            }
        });

        $(".done").change(function() {
            var idStep = $(this).attr('value');
            $("#help" + idStep).addClass("sr-only");
            updateMaintenance(idStep);
            idStep++;
            $('#carousel' + idStep).carousel(0);
            $("#help" + idStep).removeClass("sr-only");
            $("#step" + (idStep)).removeClass("sr-only");
            window.scrollTo(0,document.body.scrollHeight);
        });
        //**********************************************************************************//

        //****************************MISE A JOUR MAINTENANCE*******************************//
        function updateMaintenance(idStep) {
            $.ajax({
                url: 'php/traitement/update_maintenance.php',
                type: "POST",
                data: 'step='+idStep+'&idBox='+$("#idBox").attr('value')
            });
        }
        //**********************************************************************************//

        //****************************REPRISE MAINTENANCE***********************************//
<?php
if(isset($_GET['step']) && is_numeric($_GET['step']) && $_GET['step'] > 0 && $_GET['step'] <= $nombreSteps) {
?>
        for(var i=1; i<<?php echo $_GET['step']; ?>; i++) {
            $("#step" + i).removeClass("sr-only");
            $("#done" + i).addClass("active");
            $("#undone" + i).removeClass("active");

        }
        $("#idBox").attr("value", <?php echo $_GET['box']; ?>);
        $('#carousel' + <?php echo $_GET['step']; ?>).carousel(0);
        $("#help" + <?php echo $_GET['step']; ?>).removeClass("sr-only");
        $("#step" + <?php echo $_GET['step']; ?>).removeClass("sr-only");
        window.scrollTo(0, document.body.scrollHeight);
<?php
}
?>
        //**********************************************************************************//
    </script>
