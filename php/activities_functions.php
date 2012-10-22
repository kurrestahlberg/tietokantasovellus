<?php

    define('ACTIVITIES_PER_PAGE', 15);

    function generate_activities_table_rows() {
        global $start_index, $id;
        
        $query = "SELECT ACTIVITY.date::date,ACTIVITY_TYPE.name,ACTIVITY.duration,ACTIVITY_TYPE.consumption_per_minute,"
                . "ACTIVITY.comment "
                . "FROM ACTIVITY,ACTIVITY_TYPE WHERE ACTIVITY.user_id={$id} AND ACTIVITY.type_id=ACTIVITY_TYPE.id "
                . "ORDER BY ACTIVITY.date DESC OFFSET {$start_index} LIMIT " . ACTIVITIES_PER_PAGE;

        $result = pg_query($query) or die('Activity query failed: ' . pg_last_error());

        while($row = pg_fetch_row($result)) {
           echo "<tr>";
           echo '<td>'.$row[0].'</td><td>'.$row[1].'</td><td>'
                   .$row[2].'</td><td>'
                   .strval(intval($row[2])*intval($row[3])).' cal</td><td>'
                   .$row[4].'</td>';
           echo "</tr>";
        }
    }

?>
