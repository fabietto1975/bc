<?php
require_once('../backend/libraries/autoload.php');
//include_once (APP_ROOT . 'controllers/AuthenticationController');

use baccarat\common\rest\library\Request;

$request = new Request ();
use baccarat\common\rest\controllers\AuthenticationController;
$AuthenticationController = new AuthenticationController($request);

if (isset($_POST['action']) && isset($_POST['username']) && isset($_POST['password'])){
    $res = $AuthenticationController->postAction();
}
//var_dump($res);
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <title>Login</title>
        <link href="<?php echo BASE_URL ?>/styles/bootstrap.css" rel="stylesheet">
        <link href="<?php echo BASE_URL ?>/styles/google-code-prettify/prettify.css" rel="stylesheet">
        <link href="<?php echo BASE_URL ?>/styles/signin.css" rel="stylesheet">
        <script src="<?php echo BASE_URL ?>/scripts/jquery-1.10.2.js"></script>
        <script src="<?php echo BASE_URL ?>/scripts/jquery-ui-1.10.3.custom.js"></script>
        <script src="<?php echo BASE_URL ?>/scripts/bootstrap.js"></script>
        <link rel="icon" type="image/png" href="<?php echo BASE_URL ?>/img/favicon.ico">
    </head>
    <body>
        <br />
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <img src="<?php echo BASE_URL ?>img/logo-big.png" />
                </div>
                <br />
            </div>
            <form class="form-signin" method="POST">
                <h2 class="form-signin-heading">Login</h2>
                <input type="hidden" name="action" value="postAction">
                <input type="text" class="form-control" name="username" placeholder="Username" />
                <input type="password" class="form-control" name="password" placeholder="Password" />
                <!--         <label class="checkbox"> -->
                <!--           <input type="checkbox" value="remember-me"> Remember me -->
                <!--         </label> -->
                <button class="btn btn-lg btn-primary btn-block" type="submit">Sigin</button>
            </form>
        </div>
    </body>

</html>