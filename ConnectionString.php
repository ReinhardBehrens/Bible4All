<?php
            $serverName = "REIN\\SQLEXPRESS"; //serverName\instanceName
            $connectionInfo = array( "Database"=>"BibleForAllServerAppv1.Server.Data", "UID"=>"sa", "PWD"=>"password");
            $conn = sqlsrv_connect( $serverName, $connectionInfo);

            if( $conn ) {
                 //echo "Connection established.<br />";
            }else{
                 echo "Connection could not be established.<br />";
                 die(print_r(sqlsrv_errors(), true));
            }