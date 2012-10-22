<?php

    require('header-menu.php');
    require('mainpage_functions.php');

?>

        <h1>
            Welcome to Nutrition management <?php echo $name; ?>
        </h1>
    
        <div class="_100">
            <div id="latest_info" class="_25">
                <!--
                <div class="_100">
                    <ul>
                        <lh>Latest news:</lh>
                        <li>(12.12.2012 12:12) Blah blah</li>
                        <li>(12.12.2012 12:12) Blah blah</li>
                    </ul>
                </div>
                -->
                <div class="_100">
                    <ul>
                        <lh>Your latest meals:</lh>
                        <?php generate_latest_meals(5); ?>
                        
                    </ul>
                    <a href="add_meal.php" />Add a meal</a>
                </div>

                <div class="_100">
                    <ul>
                        <lh>Your latest activities:</lh>
                        <?php generate_latest_activities(5); ?>
                    </ul>
                    <a href="add_activity.php">Add new activity</a>
                </div>
            </div>
            <div id="reports" class="_75">
                <div class="_50">
                    <div class="_100">
                        <p class="report_title">Intake within last 7 days:</p>
                        <?php $weekly_intake = generate_intake_summary(7); ?>                         
                    </div>
                    <div class="_100">
                        <p class="report_title">Activity within last 7 days:</p>
                        <?php $weekly_burn = generate_activity_summary(7); ?>
                    </div>
                    <div class="_100">
                        <p class="report_title">Total: 
<?php
                        $weekly_diff = $weekly_intake - $weekly_burn - 7*1750;
                        if($weekly_diff > 0) echo "<span class='_red'>+{$weekly_diff}cal</span>";
                        else echo "<span>{$weekly_diff}cal</span>";
?>
                        </p>
                    </div>
                </div>
                <div class="_50">
                    <div class="_100">
                        <p class="report_title">Intake within last 30 days:</p>
                        <?php $monthly_intake = generate_intake_summary(30); ?>                         
                    </div>
                    <div class="_100">
                        <p class="report_title">Activity within last 30 days:</p>
                        <?php $monthly_burn = generate_activity_summary(30); ?>
                    </div>
                    <div class="_100">
                        <p class="report_title">Total: 
<?php
                        $monthly_diff = $monthly_intake - $monthly_burn - 30*1750;
                        if($monthly_diff > 0) echo "<span class='_red'>+{$monthly_diff}cal</span>";
                        else echo "<span>{$monthly_diff}cal</span>";
?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    
    </body>
</html>
