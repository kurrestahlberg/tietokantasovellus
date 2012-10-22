<?php

    function generate_reports_table_rows() {
        global $id;
        
        $query = "SELECT * FROM "
                ."((SELECT "
                ."EXTRACT(week from A.date) AS week,"
                ."SUM(A.duration*AT.consumption_per_minute) AS consumption "
                ."FROM ACTIVITY AS A,ACTIVITY_TYPE AS AT "
                ."WHERE A.type_id=AT.id AND A.user_id={$id}"
                ."GROUP BY week) AS S2 "
                ."NATURAL FULL JOIN "
                ."(SELECT "
                ."EXTRACT(week from M.date) AS week,"
                ."SUM(FMM.quantity*F.calories_per_unit) AS intake "
                ."FROM MEAL AS M,FOOD_MEAL_MAP AS FMM,FOOD AS F "
                ."WHERE M.user_id={$id} AND FMM.meal_id=M.id AND FMM.food_id=F.id "
                ."GROUP BY week) AS S1) "
                ."AS J ORDER BY week DESC";

        $result = pg_query($query) or die('Intake query failed: ' . pg_last_error());
        //$row = pg_fetch_row($result);

        while($row = pg_fetch_row($result)) {
            echo "<tr>";
            echo '<td>'.$row[0].'</td>';
            echo '<td>'.$row[2].'</td>';
            echo '<td>'.$row[1].'</td>';
            $total = intval($row[2])-(intval($row[1]) + 7*1750);
            if($total > 0) {
                echo '<td class="_red">'.$total.'</td>';
            } else {
                echo '<td>'.$total.'</td>';
            }
            echo "</tr>";
        }
       
    }

?>
