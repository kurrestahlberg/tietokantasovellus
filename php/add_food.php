<?php

    function generate_unit_type_select() {
        $query = "SELECT id,name FROM FOOD_UNIT_TYPE";
        $result = pg_query($query) or die('Food unit type query failed: ' . pg_last_error());

        $count = 0;
        while($row = pg_fetch_row($result)) {
           echo "<option value=\"{$row[0]}\">{$row[1]}</option>";

        }
    }

    require('header-menu.php');

    $added = FALSE;
    
    if(isset($_POST["name"]) && isset($_POST["carbs"]) && isset($_POST["calories"])
             && isset($_POST["protein"]) && isset($_POST["type"])) {
        $food_name = pg_escape_string($_POST["name"]);
        $carbs = intval($_POST["carbs"]);
        $protein = intval($_POST["protein"]);
        $calories = intval($_POST["calories"]);
        $type = intval($_POST["type"]);
        
        $query = "INSERT INTO FOOD VALUES (DEFAULT, '{$food_name}', "
                . "{$carbs}, {$protein}, {$calories}, {$type})";

        $result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
        $added = TRUE;
    }

    echo "<h1>";
    if($added) {
        echo "Added. Add another?";
    } else {
        echo "Add a food";
    }
?>
        </h1>

        <form action="add_food.php" method="post" id="registration_form">
            Name: <input type="text" name="name" /><br />
            Calories per unit: <input type="number" name="calories" /><br />
            Protein per unit: <input type="number" name="protein" /><br />
            Carbs per unit: <input type="number" name="carbs" /><br />
            Unit: <select type="text" name="type" >
            <?php generate_unit_type_select(); ?>
            </select>
            <input type="submit" value="Add" />
        </form>
    </body>
</html>
