<?php

session_start();
$dbc = mysqli_connect("localhost","root","","forum");

if(!isset($_SESSION['loggedIn'])){
	$_SESSION['loggedIn'] = false;
}

if(isset($_POST['logout'])){
	session_unset();
	session_destroy();
	$_SESSION['loggedIn'] = false;
}

if( isset($_POST['nick']) && isset($_POST['mail']) && isset($_POST['password'])){
	// användare vill registrera sig
	
	
	// Hämta data
	$username = htmlspecialchars($_POST['nick']);
	$email = htmlspecialchars($_POST['mail']);
	$password = htmlspecialchars($_POST['password']);
	
	// Formulera fråga
	$query = "INSERT INTO users (user_nickname,user_mail,user_password) VALUES ('$username','$email','$password')";

	// Kolla om frågan fungerar
	if(mysqli_query($dbc,$query)){
		echo "Du är nu registrerad! Logga in nedan:<br>";
	}
	else{
		echo $query;
		echo "Något gick fel... Försök igen!";
	}
	
}
else if( isset($_POST['nick']) && isset($_POST['password'])){
	// användare vill logga in
	
	
	// Hämta data
	$username = htmlspecialchars($_POST['nick']);
	$password = htmlspecialchars($_POST['password']);
	
	// Formulera fråga
	$query = "SELECT * FROM users WHERE user_nickname = '$username' AND user_password = '$password'";
	
	// Ställ frågan
	$result = mysqli_query($dbc,$query);
	
	// Finns en rad med resultat så har användaren skrivit rätt information
	if($row = mysqli_fetch_array($result)){
		//echo "Du är nu inloggad!";
		$_SESSION['loggedIn'] = true;
		$_SESSION['user_name'] = $row['user_nickname'];
		$_SESSION['user_id'] = $row['user_id'];
	}
	else{
		echo "Fel uppgifter, försök igen...<br>";
		$_SESSION['loggedIn'] = false;
	}

}

?>

<!DOCTYPE html>
<html>

<head>
	<title> Min sida </title>
	<link rel="stylesheet" href="css.css">
</head>


<body>

<?php 

if(!$_SESSION['loggedIn']){ // Om man inte är inloggad, visa formulär

?>
	
	Registreringsformulär:
	<form action = "login.php" method = "POST">
		
		Användarnamn:<input type = "text" name = "nick" > </input><br>
		Mail:<input type = "email" name = "mail" > </input><br>
		Lösenord:<input type = "password" name = "password" > </input><br>

		<input type="submit" value = "Registrera" />

	</form>
	
	<br><br><br>
	
	Loginformulär:
	<form action = "login.php" method = "POST">
		
		Användarnamn:<input type = "text" name = "nick" > </input><br>
		Lösenord:<input type = "password" name = "password" > </input><br>

		<input type="submit" value = "Logga in" />

	</form>
	
<?php
}
else{
	header("Location: forum.php");
}
?>
	

</body>

</html>