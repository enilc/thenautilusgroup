<?php

require_once('db_connect.php');

//Function used to authenticate a user against the User table entry
//used by login and session system. No need to load a full Mediator
//object for basic authentication.
function authenticateUser($email, $password){
	//Connect to SQL database.
	$dbConn = connectToDatabase();

	$sql = "SELECT email, password FROM spotter.User WHERE email = :email";
	$stmt = $dbConn -> prepare($sql);

	if(!$stmt -> execute(array(':email' => $email))){
	    return False;
	} else if($stmt -> rowCount() > 0) { //The user is in the system
	    $selected = $stmt -> fetchAll() [0]; //Grab the first selected record.
	    if(strtolower($selected['email']) == strtolower($email) && 
	        password_verify($password,$selected['password'])){ //Make sure the password is correct
	        return True;
	    } else {
	        return False;
	    }
	} else { //Account does not exist.
		return False;
	}
	
}

//Credit: https://www.xeweb.net/2011/02/11/generate-a-random-string-a-z-0-9-in-php/
//Random string is used for sessions and other photo name hashing functions.
function generateRandomString($length = 10) {
	return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}
?>