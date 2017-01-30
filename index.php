<?php
include_once 'vendor/swiftmailer/swiftmailer/lib/swift_required.php';
/*
 * Create Database Tables (if needed) and a constant PDO connection:
 */
require_once "lib/includes/config.php";

use website_project\utilities\Validate;

/* Function folder of important and useful functions */
include 'lib/functions/functions.inc.php';

createTables(); // Create database tables if necessary:
$data = [];
$errMessage = FALSE;

$closeBox = filter_input(INPUT_GET, 'close', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if (isset($closeBox) && $closeBox === 'yes') {
    
}

$register = filter_input(INPUT_POST, 'action');
if (isset($register) && $register === 'register') {
    $data['name'] = filter_input(INPUT_POST, 'name');
    $data['email'] = filter_input(INPUT_POST, 'email');
    $data['password'] = filter_input(INPUT_POST, 'password');


    $data['confirmation'] = generateRandom();

    $valid = new Validate($data);
    $error = $valid->contentCheck();
    if (!is_array($error)) { // If it is not an array then send verification and save user data to database table:
        $result = send_email($data);
        if ($result) {
            registration($data, $pdo); // Save to db table mysimpleregistration calling registration function:
        }
    } else {
        $errMessage = TRUE;
    }
}
$login = filter_input(INPUT_POST, "action");
if (isset($login) && $login === 'login') {
    $failed = login($pdo); // Login function:
}

/*
 * Logout user:
 */

$logout = filter_input(INPUT_GET, 'logout');

if (isset($logout) && $logout === 'yes') {
    logout();
}


/*
 * Write to blog:
 */
$submit = filter_input(INPUT_POST, 'submit');

if (isset($submit) && $submit === "Submit") {
    /* Create a query using prepared statements */
    $query = 'INSERT INTO mysimpleblog( userid, name, title, message, dateCreated) VALUES ( :userid, :name, :title, :message, NOW())';
    /* Prepared the Statement */
    $stmt = $pdo->prepare($query);
    /* Excute the statement with the prepared values */
    $result = $stmt->execute([':userid' => $_SESSION['user']->id, ':name' => $_SESSION['user']->name, ':title' => filter_input(INPUT_POST, 'title'), ':message' => filter_input(INPUT_POST, 'message')]);
    /* Check to see it was successfully entered into the database table. */
    if ($result) {
        header("Location: index.php");
        exit();
    } else {
        echo 'Error, Something went wrong';
    }
}


/*
 * Display blog setup using PDO.
 */
$query = 'SELECT id, userid, name, title, message, dateCreated FROM mysimpleblog ORDER BY id DESC';
/*
 * Prepare the query 
 */
$stmt = $pdo->prepare($query);
/*
 * Execute the query 
 */
$result = $stmt->execute();
?>
<!doctype html>
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
            <h5>Welcome <?= isset($_SESSION['user']) ? $_SESSION['user']->name : NULL; ?> to Red-shouldered Hawk Blog!</h5>
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
                <form id="login" class="span7" name="index.php" method="post" autocomplete="off">
                    <input type="hidden" name="action" value="login">
                    <label for="loginEmail">email</label>
                    <input id="loginEmail" type="text" name="email" value="" tabindex="1" autofocus>
                    <label for="loginPassword">password</label>
                    <input id="loginPassword" type="password" name="password" tabindex="2">
                    <input type="submit" name="enter" value="login" tabindex="3">               
                </form>
<?php } ?>
        </nav>
        <div class="shadow <?php echo $errMessage ? 'shadowOn' : NULL; ?>">
            <div class="errorBox">
                <h4 class="errorHeading">Registration Errors, Please Correct!</h4>
                <ol>
                    <li <?php echo!$error['empty'] ? 'class="red"' : NULL; ?>>All input fields are required!</li>
                    <li <?php echo!$error['validEmail'] ? 'class="red"' : NULL; ?>>The email address must be valid one!</li>
                    <li <?php echo!$error['duplicate'] ? 'class="red"' : NULL; ?>>Email address must be unique!</li>
                    <li <?php echo!$error['validPassword'] ? 'class="red"' : NULL; ?>>Passwords
                        <ol>
                            <li <?php echo!$error['validPassword'] ? 'class="red"' : NULL; ?>>Must have one uppercase letter!</li>
                            <li <?php echo!$error['validPassword'] ? 'class="red"' : NULL; ?>>Must have one lowercase letter!</li>
                            <li <?php echo!$error['validPassword'] ? 'class="red"' : NULL; ?>>Must be 8 characters in length!</li>
                        </ol>
                    </li>
                </ol>
                <a href="index.php">Close</a>
            </div>
        </div>
<?php if (!isset($_SESSION['user'])) { ?>
            <div id="registerBox" class="container">
                <form id="register" name="index.php" method="post" autocomplete="off">
                    <fieldset>                        
                        <legend>Register</legend>
                        <input type="hidden" name="action" value="register">
                        <label for="name">name</label>
                        <input id="name" type="text" name="name" value="" tabindex="4">
                        <label for="email">email address</label>
                        <input id="email" type="text" name="email" value="" tabindex="5">
                        <label for="password">password</label>
                        <input id="password" type="password" name="password" tabindex="6">
                        <input  type="submit" name="enter" value="register" tabindex="7">
                    </fieldset>
                </form>
            </div>
<?php } else { ?>
            <div id="enterBlogBox" class="container">
                <form id="mySimpleBlogForm" action="index.php" method="post" autocomplete="off">
                    <fieldset>
                        <legend>Enter Blog</legend>
                        <label for="title">title</label>
                        <input id="title" type="text" name="title" tabindex="1">
                        <label id="labelTextarea"  for="message">Message</label>
                        <textarea id="message" name="message" tabindex="2"></textarea>
                        <input type="submit" name="submit" value="Submit" tabindex="3">
                    </fieldset>
                </form>
            </div>
        <?php } ?>
        <?php
        /*
         * Display the output of the blog.
         */
        echo "\n";
        while ($record = $stmt->fetch(PDO::FETCH_OBJ)) {
            echo "\t" . '<div class = "container mySimpleBlog span5">' . "\t\n";
            $myDate = new DateTime($record->dateCreated);
            echo "\t\t<h2>" . htmlspecialchars($record->title) . '<span>Created by ' . htmlspecialchars($record->name) . ' on  ' . $myDate->format("F j, Y") . "</span></h2>\n";
            echo "\t\t<p>" . nl2br(htmlentities($record->message)) . "</p>\n";
            echo "\t</div>\n";
        }
        ?>
        <footer class="container">
            <h2><?php echo '&copy; ' . gmdate( 'Y', time( )) . ' J.R. Pepp - All Rights Reserved'; ?></h2>
        </footer>
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