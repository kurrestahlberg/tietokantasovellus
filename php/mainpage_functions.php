<?php

    function generate_latest_meals($item_count) {
        global $id;
        
        $query = "SELECT MEAL.date::date,MEAL_TYPE.name,slct.cal_sum "
                ."FROM MEAL_TYPE,MEAL "
                ."INNER JOIN (SELECT M.id,SUM(FMM.quantity*F.calories_per_unit) "
                ."AS cal_sum FROM MEAL AS M,FOOD_MEAL_MAP AS FMM,FOOD AS F "
                ."WHERE M.user_id={$id} AND M.id=FMM.meal_id AND FMM.food_id=F.id "
                ."GROUP BY M.id) as slct ON MEAL.id=slct.id "
                ."WHERE MEAL_TYPE.id=MEAL.type_id "
                ."ORDER BY MEAL.date DESC, MEAL.type_id DESC LIMIT " . $item_count;

        $result = pg_query($query) or die('Meal query failed: ' . pg_last_error());

        while($row = pg_fetch_row($result)) {
           echo '<li>('.$row[0].') '.$row[1].' ('.$row[2].' cal)</li>';

        }
    }
    
    function generate_latest_activities($item_count) {
        global $id;
        
        $query = "SELECT ACTIVITY.date::date,ACTIVITY_TYPE.name,ACTIVITY.duration,ACTIVITY_TYPE.consumption_per_minute "
                . "FROM ACTIVITY,ACTIVITY_TYPE WHERE ACTIVITY.user_id={$id} AND ACTIVITY.type_id=ACTIVITY_TYPE.id "
                . "ORDER BY ACTIVITY.date DESC LIMIT " . $item_count;

        $result = pg_query($query) or die('Activity query failed: ' . pg_last_error());

        while($row = pg_fetch_row($result)) {
           echo '<li>('.$row[0].') '.$row[1].' ('.strval(intval($row[2])*intval($row[3])).' cal burned)</li>';

        }
    }
    
    function generate_intake_summary($days) {
        global $id;
        
        $query = "SELECT SUM(slct.cal_sum),SUM(slct.prot_sum),SUM(slct.carb_sum) "
                ."FROM MEAL INNER JOIN "
                ."(SELECT M.id,SUM(FMM.quantity*F.calories_per_unit) AS cal_sum,"
                ."SUM(FMM.quantity*F.protein_per_unit) AS prot_sum,"
                ."SUM(FMM.quantity*F.carbs_per_unit) AS carb_sum "
                ."FROM MEAL AS M,FOOD_MEAL_MAP AS FMM,FOOD AS F "
                ."WHERE M.user_id={$id} AND M.id=FMM.meal_id AND FMM.food_id=F.id "
                ."AND M.date::date > (CURRENT_DATE - INTERVAL '".$days." days')::date "
                ."GROUP BY M.id) as slct ON MEAL.id=slct.id ";

        $result = pg_query($query) or die('Intake query failed: ' . pg_last_error());
        $row = pg_fetch_row($result);

        echo "Calories: {$row[0]}<br/>";
        echo "Protein: {$row[1]}g<br/>";
        echo "Carbs: {$row[2]}g";
        
        return $row[0];
    }
    
    function generate_activity_summary($days) {
        global $id;
        
        $query = "SELECT sum(ACTIVITY.duration),sum(ACTIVITY.duration * ACTIVITY_TYPE.consumption_per_minute) "
                . "FROM ACTIVITY, ACTIVITY_TYPE WHERE ACTIVITY.user_id={$id} AND ACTIVITY.type_id=ACTIVITY_TYPE.id "
                . "AND date::date > (CURRENT_DATE - INTERVAL '".$days." days')::date";

        $result = pg_query($query) or die('Weekly activity query failed: ' . pg_last_error());
        $row = pg_fetch_row($result);
        $minutes = intval($row[0]);
        $hours = intval($minutes / 60);
        $minutes -= $hours * 60;

        echo "Duration: {$hours}h {$minutes}min<br/>";
        echo "Calories burned: {$row[1]}";

        return $row[1];
    }
?>
