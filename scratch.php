<?php
	
	//Shim to use for interaction. Credit: https://stackoverflow.com/questions/15485354/angular-http-post-to-php-and-undefined
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
        $_POST = json_decode(file_get_contents('php://input'), true);


	print_r(array('a' => '1', 'b' => '2'));

	file_put_contents('dump.txt',print_r($_POST,$return = TRUE));

?>