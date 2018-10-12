<?php
function connectToDatabase(){
    if(!empty($_SERVER['RDS_HOSTNAME'])){
        $dbhost = $_SERVER['RDS_HOSTNAME'];
        $dbport = $_SERVER['RDS_PORT'];
        $username = $_SERVER['RDS_USERNAME']; 
        $password = $_SERVER['RDS_PASSWORD'];  
    } else {   
        $dbhost = 'aaarl7tfabntmd.cvbl6vddyfbo.us-west-1.rds.amazonaws.com';   
        $dbport = '3306';  
        $username = 'nemo';    
        $password = '655B495637EBF597D58ED8A9E57209E3E81B08F3CF92F9B95ADEDFB7035D05C8';
    }
    $dbname = 'spotter';//$_SERVER['RDS_DB_NAME'];
    $charset = 'utf8';

	
    $dsn = "mysql:host={$dbhost};port={$dbport};dbname={$dbname};charset={$charset}";

	
    return new PDO($dsn, $username, $password);

}


?>