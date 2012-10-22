<?php

    require 'header-menu.php';
    require 'common_functions.php';
    require 'meals_functions.php';
    
    $start_index = check_start();
    update_page_indices("MEAL", MEALS_PER_PAGE);
    
?>

        <table border ="0">
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Calories</th>
                <th>Protein</th>
                <th>Carbs</th>
                <th>Comment</th>
            </tr>

            <?php generate_meals_table_rows(); ?>
            
        </table>
        <a href="add_meal.php" />Add a meal</a>

        <span class="indexlist">
            <?php generate_page_navi("meals.php", MEALS_PER_PAGE); ?>
        </span>
        
    </body>
</html>
