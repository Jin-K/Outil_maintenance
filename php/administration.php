<?php
/**
 * Created by PhpStorm.
 * User: Maria
 * Date: 23-04-15
 * Time: 11:57
 */

//Pour voir si on a le droit d'accéder à cette page
$droitAcces = false;
if(isset($_SESSION['login'])) {
    $user = $userManager->getByLogin($_SESSION['login']);
    if($user->getAdmin() == 1)
        $droitAcces = true;
}
//-------------------------------------------------

//ADMIN
if($droitAcces) {
    $stepManager = new StepManager();

    //Enregistrement modif étape
    if(isset($_POST['stepUpdate'])) {
        $step = new Step(array('id' => $_POST['idStep'], 'step' => $_POST['stepUpdate'], 'title' => $_POST['titleInput'], 'content' => nl2br($_POST['contentTextArea'])));
        $stepManager->update($step);

        //Mise en tableau de toutes les aides
        $arrayText = array();
        $arrayUrl = array();
        for($i=0;$i<$_POST['helpCount'];$i++) {
            $arrayText[$i] = $_POST['helpTextArea'.($i+1)];
            $arrayUrl[$i] = $_POST['HelpUrl'.($i+1)];
        }

        $helpManager = new HelpManager();
        $helpManager->delByIdStep($step->getId());

        for ($i=1;$i<=$_POST['helpCount'];$i++) {
            $help = new Help(array('idHelp' => $i, 'idStep' => $step->getId(), 'text' => $arrayText[$i-1], 'urlImage' => $arrayUrl[$i-1]));
            $helpManager->add($help);
        }
?>
    <div id="hiddenRow" class="row" style="display: none; margin-top: 50px;">
        <div class="col-sm-6 col-sm-offset-3 col-md-offset-4 col-md-4">
            <div class="alert alert-success text-center" role="alert">Step <?php echo $_POST['stepUpdate']; ?> successfully updated ! <br>Wait a moment to be redirected ...</div>
        </div>
    </div>
    <script>
        $("#hiddenRow").fadeIn(800);

        setTimeout(function () {
            window.location.href = "?page=admin";
        }, 3000); //Redirection après 3 secondes
    </script>
<?php
    }
    //Enregistrement nouvelle étape
    elseif(isset($_POST['stepAdd']) && isset($_POST['stepNumber']) && is_numeric($_POST['stepNumber']) && $_POST['stepNumber'] != ""
    && isset($_POST['titleInput']) && $_POST['titleInput'] != "" && isset($_POST['contentTextArea']) && $_POST['contentTextArea'] != "" ) {

        $step = new Step(array('step' => $_POST['stepNumber'], 'title' => $_POST['titleInput'], 'content' => nl2br($_POST['contentTextArea'])));
        $stepManager->add($step);

        //Modif des maintenances qui ont atteint ou dépassé cette étape
        $maintenanceManager = new MaintenanceManager();
        $listeMaintenances = $maintenanceManager->getList();
        foreach ($listeMaintenances as $maintenance)
            if($maintenance->getIdStep() >= $_POST['stepNumber'])
                $maintenanceManager->update($maintenance->getIdBox(), $_POST['stepNumber']-1, $maintenance->getIdLastUser());
?>
        <div id="hiddenRow" class="row" style="display: none; margin-top: 50px;">
            <div class="col-sm-6 col-sm-offset-3 col-md-offset-4 col-md-4">
                <div class="alert alert-success text-center" role="alert">Step successfully added ! <br>Wait a moment to be redirected ...</div>
            </div>
        </div>
        <script>
            $("#hiddenRow").fadeIn(800);

            setTimeout(function () {
                window.location.href = "?page=admin";
            }, 3000); //Redirection après 3 secondes
        </script>
<?php
    }
    //Formulaire modification étape
    elseif(isset($_GET['step']) && is_numeric($_GET['step']) && $_GET['step'] <= sizeof($stepManager->getList()) && $_GET['step'] > 0) {
        $step = $stepManager->getByStep($_GET['step']);
?>
    <form class="form-horizontal" id="updateForm" action="?page=admin" method="post">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">

                <fieldset>

                    <!-- Form Name -->
                    <legend class="text-center" style="color: #CCC;">Step <i><b><?php echo $_GET['step']; ?></b></i> modification</legend>

                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-3 col-md-offset-0 col-sm-3 col-sm-offset-0 col-xs-2 control-label" for="titleInput">Title</label>
                        <div class="col-md-6 col-sm-6 col-xs-8">
                            <input id="titleInput" name="titleInput" type="text" class="form-control" value="<?php echo $step->getTitle(); ?>">
                        </div>
                    </div>

                    <!-- Textarea -->
                    <div class="form-group">
                        <label class="col-md-3 col-md-offset-0 col-sm-3 col-sm-offset-0 col-xs-2 control-label" for="contentTextArea">Content</label>
                        <div class="col-md-6 col-sm-6 col-xs-8">
                            <textarea class="form-control" id="contentTextArea" name="contentTextArea"><?php echo $step->getContent(); ?></textarea>
                        </div>
                    </div>

                    <!-- Select Basic -->
                    <div class="form-group">
                        <label class="col-md-3 col-md-offset-0 col-sm-3 col-sm-offset-0 col-xs-2 control-label" for="helpStep">Help step</label>
                        <div class="col-md-6 col-sm-6 col-xs-8">
                            <select id="helpStep" name="helpStep" class="form-control">
                                <?php
                                $helpManager = new HelpManager();
                                $lesHelps = $helpManager->getListByIdStep($step->getId());
                                foreach($lesHelps as $help) {
                                ?>
                                <option id="optionHelp<?php echo $help->getIdHelp(); ?>" value="<?php echo $help->getIdHelp(); ?>" class="option">Help <?php echo $help->getIdHelp(); ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <button type="button" id="addHelp" class="btn btn-primary btn-xs col-md-6 col-sm-6 col-xs-6" data-toggle="modal" data-target="#myModal">
                                <span class="glyphicon glyphicon-plus"></span> New
                            </button>
                            <button type="button" id="removeHelp" class="btn btn-danger btn-xs col-md-6 col-sm-6 col-xs-6">
                                <span class="glyphicon glyphicon-remove"></span> Del
                            </button>
                        </div>
                    </div>

                </fieldset>

            </div>
        </div>
        <input type="hidden" id="helpCount" name="helpCount" value="<?php echo sizeof($lesHelps); ?>">
        <div class="sr-only" id="divArray"></div>
        <?php
        foreach($lesHelps as $help) {
        ?>
        <div class="row divHelp sr-only" id="divHelp<?php echo $help->getIdHelp(); ?>">
            <div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1" style="padding: 0 10px 0 10px;">
                <img src="<?php echo $help->getUrlImage(); ?>" class="img-responsive" style="border: solid 2px #555;"/>
                <textarea class="form-control" name="helpTextArea<?php echo $help->getIdHelp(); ?>" style="margin-top: 10px;"><?php echo $help->getText(); ?></textarea>
            </div>
        </div>
        <?php
        }
        ?>
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1" style="padding: 0 10px 0 10px;">
            </div>
        </div>
        <div class="form-group text-center" style="margin-top: 10px;">
            <input type="hidden" name="idStep" value="<?php echo $step->getId(); ?>">
            <button id="buttonUpdate" type="submit" name="stepUpdate" class="btn btn-sm btn-warning" value="<?php echo $_GET['step']; ?>">Update</button>
        </div>
    </form>

    <!-- POPUP ajout HELP -->
    <!-- Modal HTML -->
    <div id="myModal" class="modal fade" style="color: #1e216d;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">New help window</h4>
                </div>
                <div class="modal-body">
                        <div class="form-group">
                            <label for="helpNumber" class="control-label">Help number : </label>
                            <input type="number" class="form-control" id="helpNumber" min="1">
                        </div>
                        <div class="form-group">
                            <form id="myForm" action="php/traitement/image_upload.php" method="post" enctype="multipart/form-data">
                                <label class="control-label">Image (16/9): </label><br>
                                <label class="radio-inline">
                                    <input type="radio" name="imgChoose" id="urlChoose" value="url" checked>
                                    <label for="urlChoose">Url</label>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="imgChoose" id="uploadChoose" value="upload">
                                    <label for="uploadChoose">Upload</label>
                                </label>
                                <div id="formUrl">
                                    <input type="url" class="form-control">
                                    <p class="help-block">Insert a correct url</p>
                                </div>
                                <div id="formUpload" class="sr-only">
                                    <input type="file" class="form-control" id="imageUpload" name="fileToUpload">
                                    <input id="uploadButton" type="submit" value="Upload" name="submit" class="btn btn-success btn-xs" style="margin-top:10px;">
                                    <p class="help-block uploadHelpBlock">Upload it before sending the new help !</p>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-success progress-bar-striped active"  role="progressbar" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                            <span class="pourcentage">0% Complete</span>
                                        </div>
                                    </div>
                                    <p id="uploadSuccess" class="alert-success text-center" style="display: none;">Image successfully uploaded !</p>
                                </div>
                            </form>
                        </div>
                        <div class="form-group">
                            <label for="newHelpContent" class="control-label">Help content : </label>
                            <textarea class="form-control" id="newHelpContent"></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" id="newHelpAdd" class="btn btn-primary">Send</button>
                </div>
            </div>
        </div>
    </div>
    <!-- POPUP ajout HELP -->

    <script>
        //*************************************************INIT*******************************************************//
        var helpCount = $("#helpCount").attr('value');
        var helpUrl = [];
        var helpText = [];

        for(var i=1; i<=helpCount; i++) {
            helpUrl[i-1] = $("#divHelp"+i).find('img').attr('src');
            helpText[i-1] = $("#divHelp"+i).find('textarea').val();
        }

        $(".divHelp:first").removeClass("sr-only");
        //*************************************************INIT*******************************************************//

        //********************************************HELP STEP SELECT************************************************//
        $("#helpStep").change(function() {
            var idHelp = $(this).find(':selected').val();
            $(".divHelp").addClass("sr-only");
            $("#divHelp" + idHelp).removeClass("sr-only");
        });

        $("#addHelp").click( function() {
            $("#helpNumber").attr('max', (++helpCount)).val(helpCount); helpCount--;
            $("#imageUpload").val("");
            $("#newHelpContent").val("");
            $('.uploadHelpBlock').show();
            $('#uploadButton').show();
            $("#uploadSuccess").hide();
        });

        $("#removeHelp").click( function() {
            var idHelp = $("#helpStep").find(':selected').val();

            //suppression html
            $("#helpStep option:last").remove();
            $("#divHelp"+idHelp).remove();

            //modif valeurs cachées
            for(var i=idHelp; i<helpCount; i++) {
                helpUrl[--i] = helpUrl[++i];
                helpText[--i] = helpText[++i];
                $("#divHelp"+(++i)).attr('id', 'divHelp'+(--i));
                $("#divHelp"+i).find('textarea').attr("name", "helpTextArea"+i);
            }

            helpCount--;
            $("#helpStep").change();

            alert("suppression de l'aide nr "+idHelp);
        });
        //********************************************HELP STEP SELECT************************************************//

        //*********************************************POPUP ADD HELP*************************************************//
        $("form input:radio").change(function () {
            if ($(this).val() == "url") {
                $("#formUpload").addClass("sr-only");
                $("#formUrl").removeClass("sr-only");
            }
            else {
                $("#formUrl").addClass("sr-only");
                $("#formUpload").removeClass("sr-only");
            }
        });

        $(".progress").hide();

        $("#newHelpAdd").click(function() {
            $('#myModal').modal('hide');
            helpCount++;
            var newIdHelp = $("#helpNumber").val();
            var newHelpUrl = $("#imageUpload").val().replace("C:\\fakepath\\", "img/upload/");
            var newHelpText = $("#newHelpContent").val();
            //alert(newHelpText);
            for(var i = helpCount; i>newIdHelp; i--) {
                $("#optionHelp"+(i-1)).attr("id", "optionHelp"+i).attr("value", i).text("Help "+i);
                $("#divHelp"+(i-1)).attr("id", "divHelp"+i).find('textarea').attr("name", "helpTextArea"+i);
                helpUrl[i-1] = helpUrl[i-2];
                helpText[i-1] = helpText[i-2];
            }
            if(newIdHelp == 1) {
                $("<option id=\"optionHelp"+1+"\" value="+1+" class=\"option\">Help "+1+"</option>").insertBefore($("#optionHelp2")); //new <select>

                $('<div class="row divHelp sr-only" id="divHelp'+1+'"></div>').insertBefore($("#divHelp2")); //new helpDiv
                $("#divHelp1").append('<div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1" style="padding: 0 10px 0 10px;"><img src="'+newHelpUrl+'" class="img-responsive" style="border: solid 2px #555;"/> <textarea class="form-control" name="helpTextArea'+1+'" style="margin-top: 10px;">'+newHelpText+'</textarea> </div>');
                helpUrl[0] = newHelpUrl;
                helpText[0] = newHelpText;
            }
            else {
                $("<option id=\"optionHelp"+newIdHelp+"\" value="+newIdHelp+" class=\"option\">Help "+newIdHelp+"</option>").insertAfter($("#optionHelp"+(newIdHelp-1))); //new <select>

                $('<div class="row divHelp sr-only" id="divHelp'+newIdHelp+'"></div>').insertAfter($("#divHelp"+(newIdHelp-1))); //new helpDiv
                $("#divHelp"+newIdHelp).append('<div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1" style="padding: 0 10px 0 10px;"><img src="'+newHelpUrl+'" class="img-responsive" style="border: solid 2px #555;"/> <textarea class="form-control" name="helpTextArea'+newIdHelp+'" style="margin-top: 10px;">'+newHelpText+'</textarea> </div>');
                helpUrl[newIdHelp-1] = newHelpUrl;
                helpText[newIdHelp-1] = newHelpText;
            }
        });

        $('#myForm').ajaxForm({           //New help image upload
            beforeSend:function(){
                $(".progress").show();
            },
            uploadProgress:function(event, position, total, percentComplete){
                $(".progress-bar").width(percentComplete+'%');
                $(".pourcentage").html(percentComplete+'%');
            },
            success:function(){
                $(".progress").delay(1000).hide();
            },
            complete:function(response){
                if(response.responseText == '-1')
                    alert("File is not an image.");
                else if(response.responseText == '-2')
                    alert("Sorry, your file is too large.");
                else if(response.responseText == '-3')
                    alert("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
                else {
                    //alert("File : \""+response.responseText+"\" uploaded !");
                    $('#uploadHelpBlock').hide();
                    $('#uploadButton').hide();
                    $("#uploadSuccess").delay(250).show("slow").fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
                }
            }
        });
        //*********************************************POPUP ADD HELP*************************************************//

        //**********************************************STEP UPDATE***************************************************//
        $("#updateForm").submit(function() {
            for(var i=1; i<=helpCount; i++) {
                $("#divArray").append('<input type="hidden" name="HelpUrl'+i+'" value="'+helpUrl[i-1]+'">');
            }
            $("#helpCount").attr('value', helpCount);
            //return false;
        });
        //**********************************************STEP UPDATE***************************************************//
    </script>
<?php
    }
    //Page d'accueil d'administration
    else {
?>
    <div class="row" style="margin-top: 25px;">
        <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
            <table class="table table-condensed table-hover">
                <thead>
                <tr>
                    <th>Step</th>
                    <th>Title</th>
                    <th colspan="2">Content</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $lesSteps = $stepManager->getList();
                foreach ($lesSteps as $step) {
                ?>
                    <tr id="row<?php echo $step->getStep(); ?>">
                        <td>#<?php echo $step->getStep(); ?></td>
                        <td><?php echo $step->getTitle(); ?></td>
                        <td><?php echo substr($step->getContent(), 0, 47) . '...'; ?></td>
                        <td>
                            <a href="?page=admin&amp;step=<?php echo $step->getStep(); ?>" class="btn btn-xs btn-primary">Modify</a>
                            <button id="<?php echo $step->getStep(); ?>" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target=".confirmDialog"><span class="glyphicon glyphicon-trash"></span></button>
                        </td>
                    </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row" style="margin-top: 15px;">
        <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
            <form class="form-horizontal" action="?page=admin" method="post">
                <legend class="text-center" style="color: #CCC;">Add a step</legend>
                <div class="form-group">
                    <label class="col-md-3 col-md-offset-0 col-sm-3 col-sm-offset-0 col-xs-2 control-label" for="stepNumber">Number</label>
                    <div class="col-md-6 col-sm-6 col-xs-8">
                        <input id="stepNumber" name="stepNumber" type="number" class="form-control" placeholder="Step number" min="1" max="<?php echo sizeof($lesSteps)+1; ?>" value="<?php echo sizeof($lesSteps)+1; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-offset-0 col-sm-3 col-sm-offset-0 col-xs-2 control-label" for="titleInput">Title</label>
                    <div class="col-md-6 col-sm-6 col-xs-8">
                        <input id="titleInput" name="titleInput" type="text" class="form-control" placeholder="Step title">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-offset-0 col-sm-3 col-sm-offset-0 col-xs-2 control-label" for="contentTextArea">Content</label>
                    <div class="col-md-6 col-sm-6 col-xs-8">
                        <textarea class="form-control" id="contentTextArea" name="contentTextArea" placeholder="Explain the step here ..."></textarea>
                    </div>
                </div>
                <div class="form-group text-center">
                    <button type="submit" name="stepAdd" class="btn btn-sm btn-success" style="//margin-left: 25px;">Add step</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Delete confirm dialog -->
    <div id="myModal" class="modal fade confirmDialog" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content" style="background: #e0af07;">
                <div class="modal-header" style="padding: 5px;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title text-center" style="color: #b13019;"> Confirm</h4>
                </div>
                <div class="modal-body" style="padding: 10px 10px 2px 10px;">
                    <p class="text-center" style="color: #B13019;"></p>
                </div>
                <div class="modal-footer" style="padding: 5px;">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" id="deleteStep" class="btn btn-sm btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete confirm dialog -->
    <script>
        var stepToDelete;
        $('#myModal').on('show.bs.modal', function (e) {
            stepToDelete = $(e.relatedTarget).attr('id'); //Recup de l'id du bouton qui a cliqué pour le detele confirm dialog
            var stepTitle = $("#row"+stepToDelete).find("td:eq(1)").text();
            $(this).find("p").html("Are you sure you want to remove <b>step "+stepToDelete+" ?<br>("+stepTitle+")</b> <br>All the data will be lost");
        });

        $('#deleteStep').click(function() {

            $('#myModal').modal('hide');
            $.ajax({
                url: 'php/traitement/delete_step.php',
                type: "POST",
                dataType: "html",
                data: {step: stepToDelete},
                success: function(data){
                    var retour = $.parseJSON(data);
                    alert('"'+retour+' helps" deleted corresponding to this step');
                }

            });
        })
    </script>
<?php
    }
}
//PAS ADMIN
else {
?>
    <div class="row text-center" style="margin-top: 50px;">
        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
            <h4 class="alert-danger">You're not admin !</h4>
        </div>
    </div>
<?php
}
?>