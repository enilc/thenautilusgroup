<?php

if(isset($_GET['sid'])){
	session_id($_GET['sid']);
	session_start();
	session_regenerate_id();
	session_destroy();
	header('Location: login.php');
} else {
	echo "<h1> Unable to log you out. Please use the link in the user section"; 
}


?>