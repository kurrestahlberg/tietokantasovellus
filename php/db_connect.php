<?php

    function db_connect() {
        $dbconn = pg_connect("dbname=kestahlb user=kestahlb")
            or die('Could not connect: ' . pg_last_error());
        return $dbconn;
    }

?>
