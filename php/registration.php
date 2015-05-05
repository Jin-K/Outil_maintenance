<?php
/**
 * Created by PhpStorm.
 * User: Maria
 * Date: 17-04-15
 * Time: 14:30
 */

//Inscription
if(isset($_POST['login']) && isset($_POST['bOLogin']) && isset($_POST['password']) && isset($_POST['password2']) && $_POST['login'] != "" && $_POST['bOLogin'] != "" && $_POST['password'] != "" && $_POST['password2'] != "") {
    if($_POST['password'] == $_POST['password2']) {
        $userManager = new UserManager();
        $user = new User(array('login' => $_POST['login'], 'loginBackOffice' => $_POST['bOLogin'], 'pw' => $_POST['password']));
        $result = $userManager->add($user);
        if($result == -1)
            echo'<script>window.location.replace("index.php?page=registration&prob=1");</script>';
        elseif($result == -2 )
            echo'<script>window.location.replace("index.php?page=registration&prob=2");</script>';
        else {
?>
<div id="hiddenRow" class="row" style="display: none; margin-top: 50px;">
    <div class="col-sm-6 col-sm-offset-3 col-md-offset-4 col-md-4">
        <div class="alert alert-success text-center" role="alert">Congratulations, you are a new member !<br>You can sign-in with your new login and password.</div>
    </div>
</div>
<script>
    $("#hiddenRow").fadeIn(800);

    setTimeout(function () {
        window.location.href = "index.php?page=registration&action=connection";
    }, 6000); //Redirection après 6 secondes
</script>
<?php
        }
    }
    else
        echo'<script>window.location.replace("index.php?page=registration&prob=3");</script>';
}
//Connexion
elseif(isset($_POST['login']) && $_POST['login'] != "" && isset($_POST['pw']) && $_POST['pw'] != "") {
    $userManager = new UserManager();
    $result = $userManager->getCountByLoginAndPassword($_POST['login'], $_POST['pw']);
    if($result == 1) {
        $_SESSION['login'] = $_POST['login'];
        if(isset($_POST['Remember'])) {
            $cookie = new Cookie();
            $cookie->addCookie('login', $_POST['login']);
        }
        echo'<script>window.location.replace("index.php");</script>';
    }
    else
        echo'<script>window.location.replace("index.php?page=registration&action=connection&prob=1");</script>';
}
//Page de connexion
elseif(isset($_GET['action']) && $_GET['action'] == 'connection') {
?>
    <div class="row" style="margin-top: 15px;">
        <div class="col-lg-6 col-lg-offset-3">
            <h4 class="text-center">Introduce your maintainer login and password</h4>

            <form class="form-horizontal small" role="form" action="?page=registration" method="post" style="margin-top: 30px;">

                <div class="form-group-sm">
                    <label for="inputLogin" class="col-sm-4 control-label">Login</label>

                    <div class="col-sm-6">
                        <input type="text" class="form-control input-sm" id="inputLogin" name="login" placeholder="Enter login">
                    </div>
                </div>
                <div class="form-group-sm">
                    <label for="inputPw" class="col-sm-4 control-label">Password</label>

                    <div class="col-sm-6">
                        <input type="password" class="form-control input-sm" id="inputPw" name="pw" placeholder="Enter password">
                        <p id="probleme" class="alert-danger text-center" style="margin: 0; display: none;">Bad login or password !</p>
                    </div>
                </div>
                <div class="form-group-sm">
                    <div class="col-sm-6 col-sm-offset-4 text-center">
                        <div class="checkbox">
                            <label>
                                <input class="checkbox-inline" name="Remember" type="checkbox"> Remember-me
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group-sm col-sm-6 col-sm-offset-4">
                    <button type="submit" class="form-control btn btn-primary" role="button">Sign-in</button>
                </div>
                <div class="form-group-sm col-sm-6 col-sm-offset-4" style="margin-top: 30px;">
                    <p class="text-center">OR</p>
                    <a href="?page=registration" class="form-control btn btn-info">Register</a>
                </div>

            </form>
        </div>
    </div>
    <script>
        <?php if(isset($_GET['prob']) && $_GET['prob'] == 1) { ?>
        $("#probleme").delay(250).show("slow").fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100).delay(6000).hide("slow");
        <?php } ?>
    </script>
<?php
}
//Page d'inscription
else {
    ?>
    <div class="row" style="margin-top: 15px;">
        <div class="col-lg-6 col-lg-offset-3">
            <h4 class="text-center">Fill this form in to register as maintainer</h4>

            <form class="form-horizontal small" role="form" action="?page=registration" method="post" style="margin-top: 30px;">

                <div class="form-group-sm">
                    <label for="inputLogin" class="col-sm-4 control-label">Login</label>

                    <div class="col-sm-6">
                        <input type="text" class="form-control input-sm" id="inputLogin" name="login" placeholder="Choose Login">
                    </div>
                </div>
                <div class="form-group-sm">
                    <label for="inputLoginBackOffice" class="col-sm-4 control-label">Back-office Login</label>

                    <div class="col-sm-6">
                        <input type="text" class="form-control input-sm" id="inputLoginBackOffice" name="bOLogin" placeholder="Enter your back-office login">
                    </div>
                </div>
                <div class="form-group-sm">
                    <label for="inputPassword" class="col-sm-4 control-label">Password</label>

                    <div class="col-sm-6">
                        <input type="password" class="form-control input-sm" id="inputPassword" name="password" placeholder="Choose password">
                    </div>
                </div>
                <div class="form-group-sm">
                    <label for="inputPassword" class="col-sm-4 control-label">Password confirmation</label>

                    <div class="col-sm-6">
                        <input type="password" class="form-control input-sm" id="inputPassword" name="password2" placeholder="Confirm password">
                        <p id="probleme1" class="alert-danger text-center" style="margin: 0; display: none">You or someone else is already registered with this maintenance login.</p>
                        <p id="probleme2" class="alert-danger text-center" style="margin: 0; display: none">This backoffice login is already registered.</p>
                        <p id="probleme3" class="alert-danger text-center" style="margin: 0; display: none">The password confirmation is not correct.</p>
                    </div>
                </div>
                <div class="form-group-sm col-sm-6 col-sm-offset-4">
                    <button type="submit" class="form-control btn btn-primary" role="button">Register</button>
                </div>
                <div class="form-group-sm col-sm-6 col-sm-offset-4">
                    <button type="reset" class="form-control btn btn-warning" role="button">Reset</button>
                </div>
                <div class="form-group-sm col-sm-6 col-sm-offset-4" style="margin-top: 30px;">
                    <p class="text-center">OR</p>
                    <a href="?page=registration&amp;action=connection" class="form-control btn btn-info">Connect</a>
                </div>
            </form>
        </div>
    </div>
    <script>
        <?php if(isset($_GET['prob']) && ($_GET['prob'] == 1 || $_GET['prob'] == 2 || $_GET['prob'] == 3)) { ?>
        $("#probleme" + <?php echo $_GET['prob']; ?>).delay(250).show("slow").fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100).delay(5000).hide("slow");
        <?php } ?>
    </script>
<?php
}