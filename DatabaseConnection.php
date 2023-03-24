<?php

class DatabaseConnection {

    public $connectionInfo;
    public $connection;
    public $serverName;

    function __construct(array $connectionInfo, $serverName)
    {
        $connectionInfo=array();
        
        foreach($connectionInfo as $connectData)
        {
            $this->connectionInfo[] = $connectData;
        }
        
        $this->$serverName=$serverName;

        $this->$connection = sqlsrv_connect( $this->$serverName, $this->$connectionInfo);

        if( $this->$connection ) {
             echo "Connection established.<br />";
        }
        else
        {
             echo "Connection could not be established.<br />";
             die(print_r(sqlsrv_errors(), true));
        }
    }
}

$serverName = "REIN\\SQLEXPRESS"; //serverName\instanceName
$connectionInfo = array( "Database"=>"BibleForAllServerAppv1.Server.Data", "UID"=>"sa", "PWD"=>"password", "CharacterSet" =>"UTF-8");

$dbconnection = new DatabaseConnection($connectionInfo, $serverName);