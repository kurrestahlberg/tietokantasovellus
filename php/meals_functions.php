<?php

    define('MEALS_PER_PAGE', 15);
    
    function generate_meals_table_rows() {
        global $start_index, $id;

        $query = "SELECT M.date::date,MT.name,M.comment "
                . ",slct.cal_sum,slct.prot_sum,slct.carb_sum "
                . "FROM MEAL AS M,"
                . "(SELECT M.id,SUM(FMM.quantity*F.calories_per_unit) AS cal_sum,"
                . "SUM(FMM.quantity*F.protein_per_unit) AS prot_sum,"
                . "SUM(FMM.quantity*F.carbs_per_unit) AS carb_sum "
                . "FROM MEAL AS M, FOOD_MEAL_MAP AS FMM, FOOD AS F "
                . "WHERE M.id=FMM.meal_id AND FMM.food_id=F.id "
                . "GROUP BY M.id) slct,"                 
                . "MEAL_TYPE AS MT "
                . "WHERE M.user_id={$id} AND M.type_id=MT.id "
                . "AND M.id=slct.id "
                . "ORDER BY M.date DESC OFFSET {$start_index} LIMIT " . MEALS_PER_PAGE;
                 
        $result = pg_query($query) or die('Meal query failed: ' . pg_last_error());

        while($row = pg_fetch_row($result)) {
           echo "<tr>";
           echo '<td>'.$row[0].'</td>'
                   .'<td>'.$row[1].'</td>'
                   .'<td>'.$row[3].'</td>'
                   .'<td>'.$row[4].'</td>'
                   .'<td>'.$row[5].'</td>'
                   .'<td>'.$row[2].'</td>';
           echo "</tr>";
        }
    }
?>
