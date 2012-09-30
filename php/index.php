<?php
    session_start() or die('FAILED TO START SESSION: ' . error_get_last());

    if($_GET['logout'] == '1') {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    } else if(isset($_SESSION['user_id'])) {
        header('Location: http://kestahlb.users.cs.helsinki.fi/tks/mainpage.php');
    }
?>

<html>
    <head>
        <title>Nutrition management</title>
        <link rel="stylesheet" type="text/css" href="basestyle.css" />
    </head>
    <body>
        <h1>
            Nutrition management
        </h1>

        <form action="mainpage.php" method="post" id="login_form">
            <label for="email">Email address:</label><input type="text" name="email" />
            <label for="password">Password:</label><input type="password" name="password" />
            <input type="submit" value="Login" />
        </form>
        <a href="register.php">Not a member? Register here</a>
    </body>
</html>
