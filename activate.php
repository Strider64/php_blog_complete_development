<?php
include_once 'vendor/swiftmailer/swiftmailer/lib/swift_required.php';
/*
 * Create Database Tables (if needed) and a constant PDO connection:
 */
require_once "lib/includes/config.php";
/* Function folder of important and useful functions */
include 'lib/functions/functions.inc.php';

$confirmation = filter_input(INPUT_GET, 'confirmation');

$verify = filter_input(INPUT_POST, 'action');

if (isset($verify) && $verify==='verify') {
    $email = filter_input(INPUT_POST, 'email');
    $confirm_number = filter_input(INPUT_POST, 'confirm_num');
    
    $id = verifyConfirmation($email, $confirm_number, $pdo);
    
    if ($id) {
        $status = upgradeAccount($id, $pdo);
        if ($status) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error, Something went wrong!";
        }
    }
    
}
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Red-shouldered Hawk Blog</title>
        <link rel="stylesheet" href="lib/css/reset.css">
        <link rel="stylesheet" href="lib/css/grids.css">
        <link rel="stylesheet" href="lib/css/stylesheet.css">
    </head>
    <body>
        <header class="container">
            <h5>Welcome <?= isset($_SESSION['user']) ? $_SESSION['user']->name : NULL; ?> to the Activation Page!</h5>
        </header>
        <nav class="container nav-bar">
            <ul class="span5 topnav" id="myTopnav">
                <li><a class="top-link" href="#" >&nbsp;</a></li>
                <li><a href="index.php">Home</a></li>
                <?php
                if (isset($_SESSION['user'])) {
                    if ($_SESSION['user']->security === 'member' || $_SESSION['user']->security === 'admin') {
                        echo '<li><a id="enterBlogBtn" href="enterblog.php">Enter Blog</a></li>';
                    }
                    echo '<li><a id="logoutBtn" href="index.php?logout=yes">Logout</a></li>';
                } else {
                    echo '<li><a id="registerBtn" href="register.php">Register</a></li>';
                    echo '<li><a id="loginBtn" href="login.php">Login</a></li>';
                }
                ?>
                <li class="icon">
                    <a href='#'>&#9776;</a>
                </li>    
            </ul>
            <?php if (!isset($_SESSION['user'])) { ?>
                <form id="login" class="span7" name="activate.php" method="post" autocomplete="off">
                    <input type="hidden" name="action" value="login">
                    <label for="loginEmail">email</label>
                    <input id="loginEmail" type="text" name="email" value="" tabindex="1" autofocus>
                    <label for="loginPassword">password</label>
                    <input id="loginPassword" type="password" name="password" tabindex="2">
                    <input type="submit" name="enter" value="login" tabindex="3">               
                </form>
            <?php } ?>
        </nav>
        <div class="container">
            <form id="activate" class="span6" action="activate.php" method="post" autocomplete="off">
                <fieldset>
                    <legend>Activation Page</legend>
                    <input type="hidden" name="action" value="verify">                    
                    <label for="email">Email Address</label>
                    <input id="email" type="text" name="email" placeholder="Enter Email Address here..." value="" tabindex="1">
                    <label for="confirmation">Confirmation Number</label>
                    <input id="confirmation" name="confirm_num" value="<?= (isset($confirmation)) ? $confirmation : NULL; ?>" tabindex="2">
                    <input type="submit" name="submit" value="confirm" tabindex="3">
                </fieldset>
            </form>
        </div>

        <script src="lib/js/jquery-3.1.1.min.js"></script>
        <script>
            $(function () {
                var $registerBtn = $('#registerBtn'),
                        $register = $('#register'),
                        $loginBtn = $('#loginBtn'),
                        $login = $('#login'),
                        $enterBlogBtn = $('#enterBlogBtn'),
                        $enterBlog = $('#mySimpleBlogForm');

                $register.css('position', 'absolute');
                $register.hide();

                $registerBtn.on('click', function (e) {
                    e.preventDefault();
                    $register.slideToggle('slow');
                });

                $login.hide();

                $loginBtn.on('click', function (e) {
                    e.preventDefault();
                    $login.animate({width: 'toggle'}, 'slow');
                });

                $enterBlog.css('position', 'absolute');
                $enterBlog.hide();

                $enterBlogBtn.on('click', function (e) {
                    e.preventDefault();
                    $enterBlog.slideToggle('slow');
                });

            }); // End of Document Ready Function:
        </script>
    </body>
</html>






