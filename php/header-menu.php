<?php

    //This file must always be included from another file
    if(count(get_included_files()) <= 1) {
            //header('Location: http://kestahlb.users.cs.helsinki.fi/tks/index.php');
            echo "Sheitan";
    }
    
    if(!isset($id)) {
        session_start() or die('FAILED TO START SESSION: ' . error_get_last());

        if(isset($_SESSION['user_id'])) {
            $name = $_SESSION['user_name'];
            $id = intval($_SESSION['user_id']);

        } else {
            header('Location: http://kestahlb.users.cs.helsinki.fi/tks/index.php');
        }

        $dbconn = pg_connect("dbname=kestahlb user=kestahlb")
            or die('Could not connect: ' . pg_last_error());
        
    }    
?>

<html>
    <head>
        <title>Nutrition management</title>
        <link rel="stylesheet" type="text/css" href="basestyle.css" />
    </head>
    <body>

        <div class="_menu">
            <a href="mainpage.php">Home</a> | 
            Reports | 
            <a href="meals.php">Meals</a> | 
            <a href="activities.php">Activities</a> | 
            <a href="index.php?logout=1">Logout</a>
        </div>
