<?php

    //This file must always be included from another file
    if(count(get_included_files()) <= 1) {
            header('Location: http://kestahlb.users.cs.helsinki.fi/tks/index.php');
    
    }
?>

<div class="_menu"><a href="mainpage.php">Home</a> | Reports | Meals | Activities | <a href="index.php?logout=1">Logout</a></div>
