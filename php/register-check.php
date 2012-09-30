<?php
    session_start() or die('FAILED TO START SESSION: ' . error_get_last());

    if(isset($_SESSION['user_id'])) {
        header('Location: http://kestahlb.users.cs.helsinki.fi/tks/mainpage.php');
    }

    $dbconn = pg_connect("dbname=kestahlb user=kestahlb")
        or die('Could not connect: ' . pg_last_error());


    $register_data_okay = TRUE;
    $email_okay = TRUE;
    $name_okay = TRUE;
    $password_okay = TRUE;
    $height_okay = TRUE;
    $weight_okay = TRUE;

    if(strlen($_POST['email']) < 5) {
        $register_data_okay = FALSE;
        $email_okay = FALSE;
    } else {
        $escaped_email = pg_escape_string($dbconn, $_POST['email']);
        $query = "SELECT id FROM USER_DATA where email='{$escaped_email}'";
        $result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());

        if(pg_num_rows($result) > 0) {
            $register_data_okay = FALSE;
            $email_okay = FALSE;
        }
    }

    if(strlen($_POST['name']) < 3) {
        $register_data_okay = FALSE;
        $name_okay = FALSE;
    }

    if(strcmp($_POST['password'], $_POST['password2']) != 0) {
        $register_data_okay = FALSE;
        $password_okay = FALSE;
    }

    if(!is_numeric($_POST['height'])) {
        $register_data_okay = FALSE;
        $height_okay = FALSE;
    }

    if(!is_numeric($_POST['weight'])) {
        $register_data_okay = FALSE;
        $weight_okay = FALSE;
    }

    if($register_data_okay == FALSE) {
    
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
<?php 
        if($email_okay == FALSE) echo 'Email exists!<br/>';
        if($name_okay == FALSE) echo 'Invalid name!<br/>';
        if($weight_okay == FALSE) echo 'Invalid weight!<br/>';
        if($height_okay == FALSE) echo 'Invalid height!<br/>';
        if($password_okay == FALSE) echo 'Passwords don\'t match!<br/>';
?>        
        <form action="register-check.php" method="post" id="registration_form">
            Name: <input type="text" name="name" value="<?php echo $_POST['name'] ?>"/><br />
            Email address: <input type="text" name="email" value="<?php echo $_POST['email'] ?>"/><br />
            Age: <input type="text" name="age" value="<?php echo $_POST['age'] ?>" /><br />
            Weight: <input type="text" name="weight" value="<?php echo $_POST['weight'] ?>" /><br />
            Height: <input type="text" name="height" value="<?php echo $_POST['height'] ?>" /><br />
            Password: <input type="password" name="password" value="<?php echo $_POST['password'] ?>" /><br />
            Password again: <input type="password" name="password2" value="<?php echo $_POST['password2'] ?>" /><br />
            <input type="submit" value="Register" />
        </form>
    </body>
</html>

<?php

    } else {
        $name = pg_escape_string($_POST['name']);
        $email = pg_escape_string($_POST['email']);
        $pw = pg_escape_string($_POST['password']);

        $query = "INSERT INTO USER_DATA VALUES (DEFAULT, '{$name}', '1980-04-12', {$_POST['weight']}, ";
        $query .= "{$_POST['height']}, '{$email}', md5('{$pw}'))";

        $result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
    
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
        Thank you! You can now <a href="index.php">login</a> with your email address and password!
    </body>
</html>

<?php

    }

    pg_free_result($result);
    pg_close($dbconn);

?>