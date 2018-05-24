<?php

session_start();

// Besöker sidan för första gången
if(!isset($_SESSION['loggedIn'])){
	$_SESSION['loggedIn'] = false;
}

// Om man redan är inloggad
if($_SESSION['loggedIn']){
	header("Location: forum.php");
}
// Om man inte är inloggad
else{
	header("Location: login.php");	
}

?>