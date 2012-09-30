<?php
    session_start() or die('FAILED TO START SESSION: ' . error_get_last());

    if(isset($_SESSION['user_id'])) {
        $name = $_SESSION['user_name'];
        $id = intval($_SESSION['user_id']);
        
    } else {
        header('Location: http://kestahlb.users.cs.helsinki.fi/tks/index.php');
    }

    $dbconn = pg_connect("dbname=kestahlb user=kestahlb")
        or die('Could not connect: ' . pg_last_error());
    
    $success = false;
    $default_type = 0;
    $default_duration = 60;
    $default_comment = "";
    $default_date = date("Y-m-d");
    
    
    if(isset($_POST['type'])) {
        if(is_numeric($_POST['duration']) && is_numeric($_POST['type'])) {
            $comment = pg_escape_string($_POST['comment']);

            $query = "INSERT INTO ACTIVITY VALUES (DEFAULT, {$_POST['type']}, {$id}, {$_POST['duration']}, "
                . "'{$_POST['date']}', '{$comment}')";

            $result = pg_query($dbconn, $query) or die('Activity adding failed: ' . pg_last_error());
            $success = true;
        } else {
            $default_type = $_POST['type'];
            $default_duration = $_POST['duration'];
            $default_comment = $_POST['comment'];
            $default_date = $_POST['date'];
        }
    }
    

?>

<html>
    <head>
        <title>Add Activity</title>
        <link rel="stylesheet" type="text/css" href="basestyle.css" />
    </head>
    <body>
        <h1>
            <?php
                if(isset($_POST['type'])) {
                    if($success == true) {
                        echo 'Add another activity';
                    } else {
                        echo 'Adding failed!';
                    }
                } else {
                    echo 'Add an activity';
                }
            ?>
        </h1>
        <form action="add_activity.php" method="post" id="activity_form">
            Type: 
            <select type="text" name="type" >
<?php
        $query = "SELECT id,name FROM ACTIVITY_TYPE";
        $result = pg_query($query) or die('Activity type query failed: ' . pg_last_error());

        $count = 0;
        while($row = pg_fetch_row($result)) {
            $sel_text = "";
            if($row[0] == $default_type) $sel_text = "selected";
           echo "<option value=\"{$row[0]}\" {$sel_text}>{$row[1]}</option>";

        }

?>
            </select><br />
            Duration in minutes: <input type="number" name="duration" value="<?php echo $default_duration; ?>" min="1" /><br />
            Date: <input type="date" name="date" value="<?php echo $default_date; ?>"/><br />
            Comment: <textarea name="comment"><?php echo $default_comment; ?></textarea><br />
            <input type="submit" value="Add" />
        </form>
    <a href="mainpage.php">Back</a>
    </body>
</html>
