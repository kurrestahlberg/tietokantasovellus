<?php
    session_start() or die('FAILED TO START SESSION: ' . error_get_last());

    if(isset($_SESSION['user_id'])) {
        header('Location: http://kestahlb.users.cs.helsinki.fi/tks/mainpage.php');
    }

?>

<html>
    <head>
        <title>Register</title>
        <link rel="stylesheet" type="text/css" href="basestyle.css" />
    </head>
    <body>
        <h1>
            Register to Nutrition management
        </h1>
        
        <form action="register-check.php" method="post" id="registration_form">
            Name: <input type="text" name="name" /><br />
            Email address: <input type="email" name="email" /><br />
            Age: <input type="text" name="age" /><br />
            Weight: <input type="text" name="weight" /><br />
            Height: <input type="text" name="height" /><br />
            Password: <input type="password" name="password" /><br />
            Password again: <input type="password" name="password2" /><br />
            <input type="submit" value="Register" />
        </form>
    </body>
</html>
