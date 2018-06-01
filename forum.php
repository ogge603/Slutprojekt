<?php
include('templates/before.php');
?>

<?php 

// Besöker sidan för första gången
if(!isset($_SESSION['loggedIn'])){
	$_SESSION['loggedIn'] = false;
}


if(isset($_POST['like']) && isset($_POST['post_id'])) {
    $dbc = mysqli_connect("localhost","root","","forum");
    $post = $_POST['post_id'];
    $usr = $_SESSION['user_id'];
    $sql = "INSERT INTO likes VALUES ($usr,$post)";
    $result = mysqli_query($dbc,$sql);
}


// Skapa en kopppling till databasen
$dbc = mysqli_connect("localhost","root","","forum");

// Sätt rätt teckenkodning
mysqli_query($dbc,"SET NAMES utf-8");

if(isset($_GET['action'])){
	$action = $_GET['action'];
	if($action == 'new_forum'){
		if(isset($_POST['forum_name'])){
			$name = $_POST['forum_name'];
			$query = "INSERT INTO forums (forum_name) VALUES ('$name');";
			mysqli_query($dbc,$query);
			header("Location: forum.php");
		}
	}
	
}
else if(isset($_POST['thread_name']) && isset($_POST['thread_desc']) && isset($_POST['forum_id'])){
	// Användaren vill skapa en ny tråd 

	
	// Identifiera data
	$name = $_POST['thread_name'];
	$desc = $_POST['thread_desc'];
	$user_id = $_SESSION['user_id'];
	$forum_id = $_POST['forum_id'];

	// Formulera fråga
	$query = "INSERT INTO threads 
	(thread_name,thread_desc,thread_user_id,thread_forum_id)
	VALUES ('$name','$desc',$user_id,$forum_id)";

	// Kolla om frågan fungerar
	if(!mysqli_query($dbc,$query)){
		die("Något gick fel..."); // Stoppa inläsningen av sidan och skriv ut "Något gick fel..."
	}

}
// Användare vill skapa en post
else if(isset($_POST['post_content']) && isset($_POST['thread_id'])){

	// Identifiera data
	$content = $_POST['post_content'];
	$user_id = $_SESSION['user_id'];
	$thread_id = $_POST['thread_id'];
    if($content != ""){
        // Formulera fråga
        $query = "INSERT INTO posts 
        (post_content,post_user_id,post_thread_id)
        VALUES ('$content',$user_id,$thread_id)";

        // Kolla om frågan fungerar
        if(!mysqli_query($dbc,$query)){
            die("Något gick fel..."); // Stoppa inläsningen av sidan och skriv ut "Något gick fel..."
        }
        
    }

}

// Användare är inne och kollar på en tråd
if(isset($_GET['thread_id'])){
	?>


	
	<!-- Tillbaka-knapp -->
	<a href="forum.php"><button>Tillbaka</button></a><br>
<?php 
    if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true){
?>
	Skapa ny post:
	<form action = "forum.php?thread_id=<?php echo $_GET['thread_id'];?>" method = "POST">
		Text:<input type = "text" name = "post_content" /><br>
		<input type="hidden" name="thread_id" value = "<?php echo $_GET['thread_id']; ?>" /> 
		<input type="submit" value = "Skriv inlägg" />
	</form>

	<?php
                                                                     }
	// Hämta tråd_id från adressfältet
	$thread_id = $_GET['thread_id'];

	// Hämta alla poster med valt id
	$query = "SELECT * FROM posts JOIN users ON user_id = post_user_id WHERE post_thread_id = $thread_id";
	$result = mysqli_query($dbc,$query);

	// Visa alla poster med rätt tråd_id
	while($row = mysqli_fetch_array($result)){
        $post_id = $row['post_id'];
        $usr = $_SESSION['user_id'];
		?>
		<div class="forum"> 
			<p class="threadDesc"><?php echo $row['user_nickname'];?> wrote:</p>
            
			<p class="threadName"><?php echo $row['post_content'];?> </p>
            <?php
             $query2 = "SELECT * FROM likes WHERE post = $post_id AND user = $usr";
            $result2 = mysqli_query($dbc,$query2);
                if(mysqli_num_rows($result2) == 0) {
            ?>

            <form action="<?php echo $_SERVER['PHP_SELF']?>?thread_id=<?php echo $thread_id; ?>" method="POST">
            
                <input type = "hidden" value = "<?php echo $post_id; ?>" name='post_id'/>
                <input type = "submit" value="like" name='like'/>
            
                            <?php
                }
                $query2 = "SELECT COUNT(post) AS likes FROM likes WHERE post = $post_id";
                $result2 = mysqli_query($dbc,$query2);
                echo mysqli_fetch_array($result2)['likes'];
            ?>

            </form>
            
            <br><br>
		</div>
		<?php
	}	
}

// Användaren är inne och kollar på ett forum
else if(isset($_GET['forum_id'])){
	?>

	<!-- Tillbaka-knapp -->
	<a href="forum.php"> <button>Tillbaka</button></a><br>
<?php 
    if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true){
?>
	Skapa ny tråd:
	<form action = "forum.php?forum_id=<?php echo $_GET['forum_id'];?>" method = "POST">
		Ämne:<input type = "text" name = "thread_name" /> <br>
		Beskrivning:<input type = "text" name = "thread_desc" /> <br>
		<input type="hidden" name="forum_id" value = "<?php echo $_GET['forum_id']; ?>" /> 
		<input type="submit" value = "Skapa tråd" />
	</form>

	<?php
    }
    
    
	// Hämta forum_id från adressfältet
	$forum_id = $_GET['forum_id'];

	// Hämta alla trådar med valt id
	$query = "SELECT * FROM threads JOIN users ON user_id = thread_user_id WHERE thread_forum_id = $forum_id";
	$result = mysqli_query($dbc,$query);

	// Visa alla trådar med rätt forum_id
	while($row = mysqli_fetch_array($result)){
		?>
		<a href="forum.php?thread_id=<?php echo $row['thread_id'];?>"><div class="forum"> 
			<p class="threadName"><?php echo $row['thread_name'];?> </p>
			<p class="threadDesc"><?php echo $row['thread_desc'];?> </p>
			<p class="threadUser">Creator:<?php echo $row['user_nickname'];?> </p>
		</div></a>
		<?php
	}
}
// Användaren har inte valt forum eller tråd
else{

if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true){
	?>
	
	Skapa nytt Forum:
	<form action = "forum.php?action=new_forum" method = "POST">
		Namn på Forum:<input type = "text" name = "forum_name" /><br>
		<input type="submit" value = "Skapa Forum" />
	</form>
	<br>
	
	<?php
}
    

	// Hämta alla forum
	$query = "SELECT * FROM forums";
	$result = mysqli_query($dbc,$query);

	// Visa alla forum
	while($row = mysqli_fetch_array($result)){
		?>		
		<a style="text-decoration:none" href="forum.php?forum_id=<?php echo $row['forum_id'];?>">
			<div class="forum">
				<p class="forumName"><?php echo $row['forum_name'];?> </p>
			</div>
		</a>
		<?php
	}
}




?>

<?php
include('templates/after.php');
?>
