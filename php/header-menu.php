<?php

    require_once 'db_connect.php';

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
            exit;
        }

        $dbconn = db_connect();
        
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
            <a href="reports.php">Reports</a> | 
            <a href="meals.php">Meals</a> | 
            <a href="activities.php">Activities</a> | 
            <a href="index.php?logout=1">Logout</a>
        </div>
