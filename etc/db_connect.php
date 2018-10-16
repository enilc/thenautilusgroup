<?php

//Connects to Spottr database and returns a PDO object if successful
function connectToDatabase(){

    //Use the RDS setting for security
    if(!empty($_SERVER['RDS_HOSTNAME'])){
        $dbhost = $_SERVER['RDS_HOSTNAME'];
        $dbport = $_SERVER['RDS_PORT'];
        $username = $_SERVER['RDS_USERNAME']; 
        $password = $_SERVER['RDS_PASSWORD'];  
    //Connection details for Developers to use on local webhosts.
    } else {   
        $dbhost = 'aaarl7tfabntmd.cvbl6vddyfbo.us-west-1.rds.amazonaws.com';   
        $dbport = '3306';  
        $username = 'nemo';    
        $password = '';
    }
    $dbname = 'spotter';//$_SERVER['RDS_DB_NAME'];
    $charset = 'utf8';

	
    $dsn = "mysql:host={$dbhost};port={$dbport};dbname={$dbname};charset={$charset}";

	
    //Return a connection to the Spottr database.
    return new PDO($dsn, $username, $password);

}


?>