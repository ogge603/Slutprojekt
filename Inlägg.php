<?php
include("Hemsida.php"); 
echo "HEJ";

// Skapa en kopppling till databasen
$dbc = mysqli_connect("localhost","root","","forumen");

// Sätt rätt teckenkodning
mysqli_query($dbc,"SET NAMES utf-8");

// Användaren vill skapa en ny tråd
if(isset($_POST['thread_name']) && isset($_POST['thread_desc']) && isset($_POST['forum_id'])){

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

    // Formulera fråga
    $query = "INSERT INTO posts 
    (post_content,post_user_id,post_thread_id)
    VALUES ('$content',$user_id,$thread_id)";

    // Kolla om frågan fungerar
    if(!mysqli_query($dbc,$query)){
        die("Något gick fel..."); // Stoppa inläsningen av sidan och skriv ut "Något gick fel..."
    }

}

// Användare är inne och kollar på en tråd
if(isset($_GET['thread_id'])){
    ?>

    <!-- Tillbaka-knapp -->
    <a href="inlägg.php"><button>Tillbaka</button></a><br>

    Skapa ny post:
    <form action = "inlägg.php?thread_id=<?php echo $_GET['thread_id'];?>" method = "POST">
        Text:<input type = "text" name = "post_content" /><br>
        <input type="hidden" name="thread_id" value = "<?php echo $_GET['thread_id']; ?>" /> 
        <input type="submit" value = "Skriv inlägg" />
    </form>

    <?php

    // Hämta tråd_id från adressfältet
    $thread_id = $_GET['thread_id'];

    // Hämta alla poster med valt id
    $query = "SELECT * FROM posts JOIN users ON user_id = post_user_id WHERE post_thread_id = $thread_id";
    $result = mysqli_query($dbc,$query);

    // Visa alla poster med rätt tråd_id
    while($row = mysqli_fetch_array($result)){
        ?>
        <div class="forum"> 
            <p class="threadDesc"><?php echo $row['user_nickname'];?> wrote:</p>
            <p class="threadName"><?php echo $row['post_content'];?> </p>
        </div>
        <?php
    }	
}

// Användaren är inne och kollar på ett forum
else if(isset($_GET['forum_id'])){
    ?>

    <!-- Tillbaka-knapp -->
    <a href="inlägg.php"> <button>Tillbaka</button></a><br>

    Skapa ny tråd:
    <form action = "inlägg.php?forum_id=<?php echo $_GET['forum_id'];?>" method = "POST">
        Ämne:<input type = "text" name = "thread_name" /> <br>
        Beskrivning:<input type = "text" name = "thread_desc" /> <br>
        <input type="hidden" name="forum_id" value = "<?php echo $_GET['forum_id']; ?>" /> 
        <input type="submit" value = "Skapa tråd" />
    </form>

    <?php

    // Hämta forum_id från adressfältet
    $forum_id = $_GET['forum_id'];

    // Hämta alla trådar med valt id
    $query = "SELECT * FROM threads JOIN users ON user_id = thread_user_id WHERE thread_forum_id = $forum_id";
    $result = mysqli_query($dbc,$query);

    // Visa alla trådar med rätt forum_id
    while($row = mysqli_fetch_array($result)){
        ?>
        <a href="inlägg.php?thread_id=<?php echo $row['thread_id'];?>"><div class="forum"> 
            <p class="threadName"><?php echo $row['thread_name'];?> </p>
            <p class="threadDesc"><?php echo $row['thread_desc'];?> </p>
            <p class="threadUser">Creator:<?php echo $row['user_nickname'];?> </p>
        </div></a>
        <?php
    }
}
// Användaren har inte valt forum eller tråd
else{
    ?>
    <!-- Logga ut knapp(formulär) -->
    <form action="inlägg.php" method ="POST">
        <input type="submit" name="logout" value="Logga ut" />
    </form>
    <?php

    // Hämta alla forum
    $query = "SELECT * FROM forums";
    $result = mysqli_query($dbc,$query);

    // Visa alla forum
    while($row = mysqli_fetch_array($result)){
        ?>		
        <a href="inlägg.php?forum_id=<?php echo $row['forum_id'];?>"><div class="forum"> 
            <p class="forumName"><?php echo $row['forum_name'];?> </p>
        </div></a>
        <?php
    }
}


?>



            </div>

<div id="Comments"> 

</div>


    </div>




<div class="rubrik">



</div>

<footer>Sponsors</footer>



</body>

</html>
