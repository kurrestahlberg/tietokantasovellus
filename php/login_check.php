<?php

    if(isset($id)) {
        header('Location: http://kestahlb.users.cs.helsinki.fi/tks/mainpage.php');
        exit;
    }

    session_start() or die('FAILED TO START SESSION: ' . error_get_last());
    
    $dbconn = pg_connect("dbname=kestahlb user=kestahlb")
        or die('Could not connect: ' . pg_last_error());
    
    if((!isset($_POST['email'])) || (!isset($_POST['password']))) {
        header('Location: http://kestahlb.users.cs.helsinki.fi/tks/index.php');
        exit;
    }

    $email = pg_escape_string($dbconn, $_POST['email']);
    $pw = pg_escape_string($dbconn, $_POST['password']);

    $query = "SELECT id,name FROM USER_DATA WHERE email = '{$email}' ";
    $query .= "AND pw_hash = md5('{$pw}' || USER_DATA.pw_salt)";

    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
    
    if(pg_num_rows($result) != 1) {
        
?>
<html>
    <head>
        <title>Nutrition management</title>
        <link rel="stylesheet" type="text/css" href="basestyle.css" />
    </head>
    <body>
            Invalid email address or password! <a href="index.php">Try again</a>
    </body>
</html>
<?php

    } else {
        
        $row = pg_fetch_row($result);
        $name = $row[1];
        $id = intval($row[0]);

        $_SESSION['user_id'] = strval($id);
        $_SESSION['user_name'] = $name;
        
        header('Location: http://kestahlb.users.cs.helsinki.fi/tks/index.php');
        exit;
    }
    
?>
