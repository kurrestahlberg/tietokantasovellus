<html>
    <head>
        <title>Nutrition management</title>
        <link rel="stylesheet" type="text/css" href="basestyle.css" />
    </head>
    <body>

<?php

    session_start() or die('FAILED TO START SESSION: ' . error_get_last());
    
    $dbconn = pg_connect("dbname=kestahlb user=kestahlb")
        or die('Could not connect: ' . pg_last_error());
    
    if(isset($_SESSION['user_id'])) {
        $name = $_SESSION['user_name'];
        $id = intval($_SESSION['user_id']);
        
    } else {
        
        if((!isset($_POST['email'])) || (!isset($_POST['pw']))) {
            header('Location: http://kestahlb.users.cs.helsinki.fi/tks/index.php');
        }
    
        $email = pg_escape_string($dbconn, $_POST['email']);
        $pw = pg_escape_string($dbconn, $_POST['password']);

        $query = "SELECT id,name FROM USER_DATA WHERE email = '{$email}' ";
        $query .= "AND pw_hash = md5('{$pw}')";

        $result = pg_query($query) or die('Query failed: ' . pg_last_error());

        if(pg_num_rows($result) != 1) {
?>
            Invalid email address or password! <a href="index.php">Try again</a>
<?php
        } else {
        
            $row = pg_fetch_row($result);
            $name = $row[1];
            $id = intval($row[0]);
            
            $_SESSION['user_id'] = strval($id);
            $_SESSION['user_name'] = $name;
        }
    }
    
    if(isset($id)) {
?>

        <h1>
            Welcome to Nutrition management <?php echo $name; ?>
        </h1>

        <div class="_100">
            <div id="latest_info" class="_25">
                <div class="_100">
                    <ul>
                        <lh>Latest news:</lh>
                        <li>(12.12.2012 12:12) Blah blah</li>
                        <li>(12.12.2012 12:12) Blah blah</li>
                    </ul>
                </div>

                <div class="_100">
                    <ul>
                        <lh>Your latest meals:</lh>
                        <li>(12.12.2012 08:12) Breakfast (612 cal)</li>
                        <li>(12.12.2012 12:12) Lunch (1023 cal)</li>
                    </ul>
                    <a href="add_meal.php" />Add a meal</a>
                </div>

                <div class="_100">
                    <ul>
                        <lh>Your latest activities:</lh>
<?php
    $query = "SELECT ACTIVITY.date,ACTIVITY_TYPE.name,ACTIVITY.duration,ACTIVITY_TYPE.consumption_per_minute "
            . "FROM ACTIVITY,ACTIVITY_TYPE WHERE ACTIVITY.user_id={$id} AND ACTIVITY.type_id=ACTIVITY_TYPE.id "
            . "ORDER BY ACTIVITY.date DESC LIMIT 5";
    
    $result = pg_query($query) or die('Activity query failed: ' . pg_last_error());
    
    while($row = pg_fetch_row($result)) {
       echo '<li>('.$row[0].') '.$row[1].'('.strval(intval($row[2])*intval($row[3])).' cal burned)</li>';
        
    }

?>
                    </ul>
                    <a href="add_activity.php">Add new activity</a>
                </div>
            </div>
            <div id="reports" class="_75">
                <div class="_50">
                    <div class="_100">
                        <p class="report_title">Intake this week:</p>
                        <p>Calories: 5235</p>
                        <p>Protein: 123g</p>
                        <p>Carbs: 126g</p>
                    </div>
                    <div class="_100">
                        <p class="report_title">Activity this week:</p>
<?php
    $query = "SELECT sum(ACTIVITY.duration),sum(ACTIVITY.duration * ACTIVITY_TYPE.consumption_per_minute) "
            . "FROM ACTIVITY, ACTIVITY_TYPE WHERE ACTIVITY.user_id={$id} AND ACTIVITY.type_id=ACTIVITY_TYPE.id "
            . "AND extract(week from ACTIVITY.date) = extract(week from CURRENT_DATE)";
            
    $result = pg_query($query) or die('Weekly activity query failed: ' . pg_last_error());
    $row = pg_fetch_row($result);
    $minutes = intval($row[0]);
    $hours = intval($minutes / 60);
    $minutes -= $hours * 60;

    echo "<p>Duration: {$hours}h {$minutes}min</p>";
    echo "<p>Calories burned: {$row[1]}</p>";
?>
                    </div>
                </div>
                <div class="_50">
                    <div class="_100">
                        <p class="report_title">Intake this month:</p>
                        <p>Calories: 20235</p>
                        <p>Protein: 423g</p>
                        <p>Carbs: 426g</p>
                    </div>
                    <div class="_100">
                        <p class="report_title">Activity this month:</p>
<?php
    $query = "SELECT sum(ACTIVITY.duration),sum(ACTIVITY.duration * ACTIVITY_TYPE.consumption_per_minute) "
            . "FROM ACTIVITY, ACTIVITY_TYPE WHERE ACTIVITY.user_id={$id} AND ACTIVITY.type_id=ACTIVITY_TYPE.id "
            . "AND extract(month from ACTIVITY.date) = extract(month from CURRENT_DATE)";
            
    $result = pg_query($query) or die('Weekly activity query failed: ' . pg_last_error());
    $row = pg_fetch_row($result);
    $minutes = intval($row[0]);
    $hours = intval($minutes / 60);
    $minutes -= $hours * 60;

    echo "<p>Duration: {$hours}h {$minutes}min</p>";
    echo "<p>Calories burned: {$row[1]}</p>";
?>
                    </div>
                </div>
            </div>
        </div>
    <a href="index.php?logout=1">logout</a>
<?php
    }
?>
    
    </body>
</html>
