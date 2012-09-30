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
    $default_comment = "";
    $default_date = date("Y-m-d");
    
    
    if(isset($_POST['type'])) {
        if(is_numeric($_POST['type'])) {
            $comment = pg_escape_string($_POST['comment']);

            $query = "INSERT INTO MEAL VALUES (DEFAULT, {$_POST['type']}, {$id}, "
                . "'{$_POST['date']}', '{$comment}') RETURNING MEAL.id";

            $result = pg_query($dbconn, $query) or die('Meal adding failed: ' . pg_last_error());
            
            $meal_id = pg_fetch_row($result);
            
            $foodcount = 5;
            if(isset($_POST['foodcount'])) {
                $foodcount = intval($_POST['foodcount']);
            }
            $count = 1;
            while($count <= $foodcount) {
                if(isset($_POST['food'.$count]) && is_numeric($_POST['food'.$count])) {
                    $food_id = $_POST['food'.$count];
                    $food_amount = $_POST['food'.$count.'amount'];
                    
                    $query = "INSERT INTO FOOD_MEAL_MAP VALUES (DEFAULT, ".$meal_id[0].",".$food_id.",".$food_amount.")";
                    $result = pg_query($dbconn, $query) or die('Adding food to meal failed: '.  pg_last_error());
                }
                $count++;
            }
            
            $success = true;
        } else {
            $default_type = $_POST['type'];
            $default_comment = $_POST['comment'];
            $default_date = $_POST['date'];
        }
    }
    

?>

<html>
    <head>
        <title>Add Meal</title>
        <link rel="stylesheet" type="text/css" href="basestyle.css" />
        <script language ="javascript">
            function setFoodUnit(e) {
                mealselect = e.target;
                meallabel = e.target.id + "div";
                text = 'jep';
                index = mealselect.options[mealselect.selectedIndex].id;
                switch(index) {
<?php
        $query = "SELECT id,name FROM FOOD_UNIT_TYPE";
        $result = pg_query($query) or die('Food unit type query failed: ' . pg_last_error());

        
        $food_unit_types = array();
        
        while($row = pg_fetch_row($result)) {
            $food_unit_types[$row[0]] = $row[1];
        }
        
        foreach($food_unit_types as $key => $unit_type) {
            echo "case '{$key}':\n"
                ."text = '{$unit_type}';\n"
                ."break;\n";

        }
?>
                    default:
                        text = '';
                        break;
                }
                document.getElementById(meallabel).innerHTML = text;
            }
        </script>
    </head>
    <body>
        <h1>
            <?php
                if(isset($_POST['type'])) {
                    if($success == true) {
                        echo 'Add Another Meal';
                    } else {
                        echo 'Adding Failed!';
                    }
                } else {
                    echo 'Add A Meal';
                }
            ?>
        </h1>
        <form action="add_meal.php" method="post" id="activity_form">
            Type: 
            <select type="text" name="type" >
<?php
        $query = "SELECT id,name FROM MEAL_TYPE";
        $result = pg_query($query) or die('Meal type query failed: ' . pg_last_error());

        $count = 0;
        while($row = pg_fetch_row($result)) {
            $sel_text = "";
            if($row[0] == $default_type) $sel_text = "selected";
           echo "<option value=\"{$row[0]}\" {$sel_text}>{$row[1]}</option>";

        }

?>
            </select><br />
            Date: <input type="date" name="date" value="<?php echo $default_date; ?>"/><br />
            Comment: <textarea name="comment"><?php echo $default_comment; ?></textarea><br />
            
<?php
        $query = "SELECT id,name,unit_type_id FROM FOOD";
        $result = pg_query($query) or die('Food query failed: ' . pg_last_error());

        $foods = array();
        $food_unit_type_ids = array();
        
        while($row = pg_fetch_row($result)) {
            $foods[$row[0]] = $row[1];
            $food_unit_type_ids[$row[0]] = $row[2];
        }
        
        for($i = 1; $i < 6; $i++) {
?>
            <div class="_100">
                <select class="_50" type="text" name="food<?php echo $i; ?>" 
                        id="food<?php echo $i; ?>"
                        onChange="javascript:setFoodUnit(event);">
                    <option></option>
<?php
            foreach($foods as $key => $food) {
                echo "<option value=\"{$key}\" id=\"{$food_unit_type_ids[$key]}\">{$food}</option>\n";

            }
?>
                </select>
                <input class="_25" type ="text" name="food<?php echo $i; ?>amount" value="1" />
                <span id="food<?php echo $i; ?>div"></span>
            </div>
<?php
        }
?>
            <input type="submit" value="Add" />
        </form>
    <a href="mainpage.php">Back</a>
    </body>
</html>
