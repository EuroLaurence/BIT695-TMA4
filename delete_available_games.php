<?php 
    $id = intval($_GET['id']);
    include 'connect.php';
    
    //If data has been submitted to the page, delete relevent member information
    if(isset($_POST['id'])){
        
        $sql = "DELETE FROM board_game_availability WHERE id=".$_POST['id'];
        
        if ($connection->query($sql)) {
            $updatemessage = "Successfully deleted";
            
        } else {
            $updatemessage = $connection->error;
        }
    }

    // Retrieve board game and player info to be loaded into the view
    $sql = "SELECT board_game_availability.id, board_game_availability.game, players.first_name, players.last_name, players.email FROM board_game_availability INNER JOIN players ON board_game_availability.player_id=players.player_id
 WHERE board_game_availability.id=" . $id;

    $result = $connection->query($sql);
    if (!empty($result)) {
        $available_game = $result->fetch_assoc();
        
        
    } else {
        echo "Error: " . "<br>" . $connection->error;
    }
    $connection->close();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">
<title>Title of the document</title>
</head>

<body>

<h1 class="form_heading">Delete available game</h1>

    

<form class="registration_form" action="delete_available_games.php"
  method="post" name="submitnewsalesrecord">


    <ul>
        <li>
            <label for="game">Game:</label>
            <input type="text" name="game" id="game" disabled readonly required value="<?php if(isset($available_game['game'])) {echo $available_game['game'];} ?>"/>
        </li>
        
        <li>
            <label for="player">Player:</label>
            <input type="text" name="player" id="player" disabled readonly required value="<?php if(isset($available_game['first_name'])) {echo $available_game['first_name'] . " " . $available_game['last_name'] . " - " . $available_game['email'];} ?>"/>
        </li>
        
        <li>
            <input type="hidden" name="id" value="<?php if(isset($available_game['id'])) {echo $available_game['id'];} ?>"/>
            <button class="submit" type="submit" name="submit">Delete</button>
        </li>
    </ul>
    
</form>

    
<?php 
    //Display board game availability table on the page.
    
    include 'connect.php';
    
    $sql = "SELECT board_game_availability.id, board_game_availability.game, players.first_name, players.last_name FROM board_game_availability INNER JOIN players ON board_game_availability.player_id=players.player_id";

    $result = $connection->query($sql);
       
    if($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th></th><th>Game</th><th>First name</th><th>Last name</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>";
            echo "<a href='delete_available_games.php?id=".$row['id']."'>Delete</a>";
            echo "</td><td>";
            echo $row['game'];
            echo "</td><td>";
            echo $row['first_name'];
            echo "</td><td>";
            echo $row['last_name'];
            echo "</td></tr>";
        }
        echo "</table>";
    }
    
?>
</body>
</html>