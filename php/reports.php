<?php

    require 'header-menu.php';
    require 'common_functions.php';
    require 'reports_functions.php';
    
    $start_index = check_start();
    //update_page_indices("MEAL", MEALS_PER_PAGE);
    
?>

        <table border ="0">
            <tr>
                <th>Week number</th>
                <th>Intake</th>
                <th>Consumption</th>
                <th>Total</th>
            </tr>

            <?php generate_reports_table_rows(); ?>
            
        </table>
        <span class="indexlist">
            <?php //generate_page_navi("reports.php", MEALS_PER_PAGE); ?>
        </span>
    </body>
</html>
