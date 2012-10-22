<?php

    require('header-menu.php');

    $added = FALSE;
    
    if(isset($_POST["name"]) && isset($_POST["consumption"])) {
        $act_name = pg_escape_string($_POST["name"]);
        $consumption = $_POST["consumption"];
        
        $query = "INSERT INTO ACTIVITY_TYPE VALUES (DEFAULT, '{$act_name}', {$consumption})";
        $result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
        $added = TRUE;
    }

    echo "<h1>";
    if($added) {
        echo "Added. Add another?";
    } else {
        echo "Add an activity type";
    }
?>
        </h1>

        <form action="add_activity_type.php" method="post" id="registration_form">
            Name: <input type="text" name="name" /><br />
            Consumption per minute: <input type="number" name="consumption" /><br />
            <input type="submit" value="Add" />
        </form>
    </body>
</html>
