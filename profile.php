<?php

include('templates/before.php');

?>

<?php

if(!isset($_SESSION['loggedIn'])){
	$_SESSION['loggedIn'] = false;
	header("Location: forum.php");
}

if($_SESSION['loggedIn'] != true){
	$_SESSION['loggedIn'] = false;
	header("Location: forum.php");	
}

$dbc = mysqli_connect("localhost","root","","forum");

if(isset($_POST['update_info'])){

	$user_id  = $_SESSION['user_id'];
	$new_mail = $_POST['new_mail'];
	$new_password = $_POST['new_password'];
	$old_password = $_POST['old_password'];

	$query = "SELECT * FROM users WHERE user_id = $user_id;";
	$result = mysqli_query($dbc,$query);

	$row = mysqli_fetch_array($result);


	if($old_password == $row['user_password']){
		
		if($new_mail == ""){
			$new_mail = $row['user_mail'];
		}

		if($new_password == ""){
			$new_password = $row['user_password'];
		}
		
		$query = "UPDATE users SET user_mail = '$new_mail' , user_password = '$new_password' WHERE user_id = $user_id";

		mysqli_query($dbc,$query);
		
		header("Location: profile.php");
		
	}	
	else{
		echo "Fel lösenord!";
	}
	
}
else if(isset($_SESSION['user_id']) && isset($_SESSION['user_name'])){
		
	// Skapa en kopppling till databasen

	// Sätt rätt teckenkodning
	mysqli_query($dbc,"SET NAMES utf-8");

	$user_id  = $_SESSION['user_id'];

	$query = "SELECT * FROM users WHERE user_id = $user_id;";
	$result = mysqli_query($dbc,$query);

	$row = mysqli_fetch_array($result);
	?>
	<form action="profile.php" method="POST" autocomplete="off">

		Ny Mail: <input type="mail" name="new_mail" placeholder="<?php echo $row['user_mail']; ?>" /><br>
		Nytt Lösenord: <input type="password" name="new_password" placeholder="" /><br><br>
		Nuvarande Lösenord: <input type="password" name="old_password" placeholder="" /><br>
		<input type="submit" name="update_info" value="Uppdatera Information" />
	
	</form>
	
	<?php
	
}
else{
	$_SESSION['loggedIn'] = false;
	header("Location: login.php");	
}

?>

<?php

include('templates/after.php');

?>