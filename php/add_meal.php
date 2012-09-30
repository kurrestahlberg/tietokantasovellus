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
                . "'{$_POST['date']}', '{$comment}')";

            $result = pg_query($dbconn, $query) or die('Meal adding failed: ' . pg_last_error());
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
                index = mealselect.options[mealselect.selectedIndex].value;
                switch(index) {
                    case '1':
                        text = 'yks';
                        break;
                    case '2':
                        text = 'kaks';
                        break;
                    case '3':
                        text = 'kolme';
                        break;
                    default:
                        text = 'default';
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
            
            <div>
                <select class="_75" type="text" name="unit1" id="unit1" onChange="javascript:setFoodUnit(event);">
                    <option value="1">jep1</option>
                    <option value="2">jep2</option>
                    <option value="3">jep3</option>
                </select>
                <span id="unit1div">kg</span>
            </div>    
            <div>
                <select class="_75" type="text" name="unit2" id="unit2" onChange="javascript:setFoodUnit(event);">
                    <option value="1">jep1</option>
                    <option value="2">jep2</option>
                    <option value="3">jep3</option>
                </select>
                <span id="unit2div">kg</span>
            </div>    
            <div>
                <select class="_75" type="text" name="unit3" id="unit3" onChange="javascript:setFoodUnit(event);">
                    <option value="1">jep1</option>
                    <option value="2">jep2</option>
                    <option value="3">jep3</option>
                </select>
                <span id="unit3div">kg</span>
            </div>    
            <input type="submit" value="Add" />
        </form>
    <a href="mainpage.php">Back</a>
    </body>
</html>
