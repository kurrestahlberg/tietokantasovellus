<?php

    require 'header-menu.php';
    require 'common_functions.php';
    require 'activities_functions.php';
    
    $start_index = check_start();
    update_page_indices("ACTIVITY", ACTIVITIES_PER_PAGE);
    
?>

        <table border ="0">
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Duration</th>
                <th>Consumption</th>
                <th>Comment</th>                    
            </tr>
            
            <?php generate_activities_table_rows() ?>
            
        </table>
        <a href="add_activity.php">Add new activity</a>
        <span class="indexlist">
            <?php generate_page_navi("activities.php", ACTIVITIES_PER_PAGE); ?>
        </span>
        
    </body>
</html>
