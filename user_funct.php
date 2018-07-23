<?php

require_once('db_connect.php');
/*
function manageUserSession(){
	if(isset($_COOKIE['spotter_session'])) { //We have a session ID stored as a cookie.
        if()
    }
}
*/
function authenticateUser($email, $password){
	//Connect to SQL database.
	$dbConn = connectToDatabase();

	$sql = "SELECT email, password FROM spotter.User WHERE email = :email";
	$stmt = $dbConn -> prepare($sql);

	if(!$stmt -> execute(array(':email' => $email))){
	    return False;
	} else {
	    $selected = $stmt -> fetchAll() [0]; //Grab the first selected record.
	    if(strtolower($selected['email']) == strtolower($email) && 
	        password_verify($password,$selected['password'])){
	        return True;
	    } else {
	        return False;
	    }
	}
	
}

?>