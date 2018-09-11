<?php

require_once('db_connect.php');

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

//Credit: https://www.xeweb.net/2011/02/11/generate-a-random-string-a-z-0-9-in-php/
function generateRandomString($length = 10) {
	return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}
?>