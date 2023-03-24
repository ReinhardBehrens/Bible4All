<?php

class DatabaseConnection {

            public $connectionInfo;
            public $conn;
            public $serverName;
            
            function __construct($connectionInfo, $connection, $serverName)
            {
                $this->$connectionInfo=$connectionInfo;
                $this->$conn = $connection;
                if($connection) {
                     //echo "Connection established.<br />";
                }else{  
                     echo "Connection could not be established.<br />";
                     die(print_r(sqlsrv_errors(), true));
                }
                
                $this->$serverName=$serverName;
            }
            
            
            public get_connection() 
            {
                return $this->$conn;
            }
            
//            $serverName = "REIN\\SQLEXPRESS"; //serverName\instanceName
//            $connectionInfo = array( "Database"=>"BibleForAllServerAppv1.Server.Data", "UID"=>"sa", "PWD"=>"password", "CharacterSet" =>"UTF-8");
//            $conn = sqlsrv_connect( $serverName, $connectionInfo);

            
          
}
