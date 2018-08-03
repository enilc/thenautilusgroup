<html>

<body>
	<ul>
<?php
	require_once('user_funct.php');

	for($i = 0; $i < 10; $i++){
		echo '<li>' . generateRandomString(14) . '</li>';
	}
?>
</ul>
</body>
</html>


