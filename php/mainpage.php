<?php

    session_start() or die('FAILED TO START SESSION: ' . error_get_last());
    
    $dbconn = pg_connect("dbname=kestahlb user=kestahlb")
        or die('Could not connect: ' . pg_last_error());
    
    if(isset($_SESSION['user_id'])) {
        $name = $_SESSION['user_name'];
        $id = intval($_SESSION['user_id']);
        
    } else {
        
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
            exit;
        } else {
        
            $row = pg_fetch_row($result);
            $name = $row[1];
            $id = intval($row[0]);
            
            $_SESSION['user_id'] = strval($id);
            $_SESSION['user_name'] = $name;
        }
    }
    

    require('header-menu.php');

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
<?php
    $query = "SELECT MEAL.date,MEAL_TYPE.name,slct.cal_sum "
            ."FROM MEAL_TYPE,MEAL "
            ."INNER JOIN (SELECT M.id,SUM(FMM.quantity*F.calories_per_unit) "
            ."AS cal_sum FROM MEAL AS M,FOOD_MEAL_MAP AS FMM,FOOD AS F "
            ."WHERE M.user_id={$id} AND M.id=FMM.meal_id AND FMM.food_id=F.id "
            ."GROUP BY M.id) as slct ON MEAL.id=slct.id "
            ."WHERE MEAL_TYPE.id=MEAL.type_id "
            ."ORDER BY MEAL.date DESC, MEAL.type_id DESC LIMIT 5";
    
    $result = pg_query($query) or die('Meal query failed: ' . pg_last_error());
    
    while($row = pg_fetch_row($result)) {
       echo '<li>('.$row[0].') '.$row[1].' ('.$row[2].' cal)</li>';
        
    }

?>
                        
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
       echo '<li>('.$row[0].') '.$row[1].' ('.strval(intval($row[2])*intval($row[3])).' cal burned)</li>';
        
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
<?php
    $query = "SELECT SUM(slct.cal_sum),SUM(slct.prot_sum),SUM(carb_sum) "
            ."FROM MEAL INNER JOIN "
            ."(SELECT M.id,SUM(FMM.quantity*F.calories_per_unit) AS cal_sum,"
            ."SUM(FMM.quantity*F.protein_per_unit) AS prot_sum,"
            ."SUM(FMM.quantity*F.carbs_per_unit) AS carb_sum "
            ."FROM MEAL AS M,FOOD_MEAL_MAP AS FMM,FOOD AS F "
            ."WHERE M.user_id={$id} AND M.id=FMM.meal_id AND FMM.food_id=F.id "
            ."AND M.date::date > (CURRENT_DATE - INTERVAL '7 days')::date "
            ."GROUP BY M.id) as slct ON MEAL.id=slct.id ";
    
    $result = pg_query($query) or die('Weekly intake query failed: ' . pg_last_error());
    $row = pg_fetch_row($result);
    $weekly_intake = $row[0];
    
                        echo "Calories: {$row[0]}<br/>";
                        echo "Protein: {$row[1]}g<br/>";
                        echo "Carbs: {$row[2]}g";
?>                         
                    </div>
                    <div class="_100">
                        <p class="report_title">Activity this week:</p>
<?php
    $query = "SELECT sum(ACTIVITY.duration),sum(ACTIVITY.duration * ACTIVITY_TYPE.consumption_per_minute) "
            . "FROM ACTIVITY, ACTIVITY_TYPE WHERE ACTIVITY.user_id={$id} AND ACTIVITY.type_id=ACTIVITY_TYPE.id "
            . "AND date::date > (CURRENT_DATE - INTERVAL '7 days')::date";
            
    $result = pg_query($query) or die('Weekly activity query failed: ' . pg_last_error());
    $row = pg_fetch_row($result);
    $minutes = intval($row[0]);
    $hours = intval($minutes / 60);
    $minutes -= $hours * 60;
    $weekly_burn = $row[1];

    echo "Duration: {$hours}h {$minutes}min<br/>";
    echo "Calories burned: {$row[1]}";
?>
                    </div>
                    <div class="_100">
                        <p class="report_title">Total this week: 
<?php
                        $weekly_diff = $weekly_intake - $weekly_burn - 7*1750;
                        if($weekly_diff > 0) echo "<span class='_red'>+{$weekly_diff}cal</span>";
                        else echo "<span>{$weekly_diff}cal</span>";
?>
                        </p>
                    </div>
                </div>
                <div class="_50">
                    <div class="_100">
                        <p class="report_title">Intake this month:</p>
<?php
    $query = "SELECT SUM(slct.cal_sum),SUM(slct.prot_sum),SUM(carb_sum) "
            ."FROM MEAL INNER JOIN "
            ."(SELECT M.id,SUM(FMM.quantity*F.calories_per_unit) AS cal_sum,"
            ."SUM(FMM.quantity*F.protein_per_unit) AS prot_sum,"
            ."SUM(FMM.quantity*F.carbs_per_unit) AS carb_sum "
            ."FROM MEAL AS M,FOOD_MEAL_MAP AS FMM,FOOD AS F "
            ."WHERE M.user_id={$id} AND M.id=FMM.meal_id AND FMM.food_id=F.id "
            ."AND M.date::date > (CURRENT_DATE - INTERVAL '30 days')::date "
            ."GROUP BY M.id) as slct ON MEAL.id=slct.id ";
    
    $result = pg_query($query) or die('Monthly intake query failed: ' . pg_last_error());
    $row = pg_fetch_row($result);
    $monthly_intake = $row[0];
    
                        echo "Calories: {$row[0]}<br/>";
                        echo "Protein: {$row[1]}g<br/>";
                        echo "Carbs: {$row[2]}g";
?>                         
                    </div>
                    <div class="_100">
                        <p class="report_title">Activity this month:</p>
<?php
    $query = "SELECT sum(ACTIVITY.duration),sum(ACTIVITY.duration * ACTIVITY_TYPE.consumption_per_minute) "
            . "FROM ACTIVITY, ACTIVITY_TYPE WHERE ACTIVITY.user_id={$id} AND ACTIVITY.type_id=ACTIVITY_TYPE.id "
            . "AND ACTIVITY.date::date > (CURRENT_DATE - INTERVAL '30 days')::date";
            
    $result = pg_query($query) or die('Weekly activity query failed: ' . pg_last_error());
    $row = pg_fetch_row($result);
    $minutes = intval($row[0]);
    $hours = intval($minutes / 60);
    $minutes -= $hours * 60;
    $monthly_burn = $row[1];

    echo "Duration: {$hours}h {$minutes}min<br/>";
    echo "Calories burned: {$row[1]}";
?>
                    </div>
                    <div class="_100">
                        <p class="report_title">Total this month: 
<?php
                        $monthly_diff = $monthly_intake - $monthly_burn - 30*1750;
                        if($monthly_diff > 0) echo "<span class='_red'>+{$monthly_diff}cal</span>";
                        else echo "<span>{$monthly_diff}cal</span>";
?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    
    </body>
</html>
