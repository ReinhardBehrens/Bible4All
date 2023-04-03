<?php
            include './DatabaseLayer.php';
            $serverName = "REIN\\SQLEXPRESS"; //serverName\instanceName
            $dbconnection = new DatabaseLayer("BibleForAllServerAppv1.Server.Data", "sa", "password", "UTF-8", $serverName);
            $conn = $dbconnection->GetConnection();

            if( $conn ) {
                 //echo "Connection established.<br />";
            }else{
                 echo "Connection could not be established to the database.<br />";
                 die(print_r(sqlsrv_errors(), true));
            }