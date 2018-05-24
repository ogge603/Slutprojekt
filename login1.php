<?php

session_start();
// Besöker sidan för första gången
if(!isset($_SESSION['loggedIn'])){
	$_SESSION['loggedIn'] = false;
}

// Om man redan är inloggad
if($_SESSION['loggedIn']){
	header("Location: index.php");
}		

// Användare vill registrera sig
if( isset($_POST['username'])  && isset($_POST['password'])){
	
	
	// Skapa en kopppling till databasen
	$dbc = mysqli_connect("localhost","root","","forum");

	// Identifiera data
	$username = $_POST['username'];

	$password = $_POST['password'];
	
    
	// Formulera fråga
    
	$query = "SELECT * FROM users WHERE user_name = '$username' AND password = '$password'"; 
	
    $sql = mysqli_query($dbc, $query);
    
    $row = mysqli_fetch_array($sql);
    
    if($row != null) {
        header("location:hemsida.php");
    } else {
        echo "Fel användarnamn eller lösenord, vänligen försök igen<br>";       
    }

}

?>

<!DOCTYPE html>
<html>

	<head>
		<title> Forum </title>
	</head>

	<body>

		Registreringsformulär:
		<form action = "login1.php" method = "POST">
			Användarnamn:<input type = "text" name = "username" > </input><br>
			Lösenord:<input type = "password" name = "password" > </input><br>
			<input type="submit" value = "Login" />
		
    
 

</form>

		<br><br><br>
		
		
		</form>
       
	</body>

</html>