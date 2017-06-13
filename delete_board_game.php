<?php 
    $game_id = intval($_GET['id']);
    include 'connect.php';
    
    //If data has been submitted to the page, delete board game information
    if(isset($_POST['id'])){
        
        $sql = "DELETE FROM board_games WHERE game_id=".$_POST['id'];
        
        if ($connection->query($sql)) {
            $updatemessage = "Successfully deleted";
            
        } else {
            $updatemessage = $connection->error;
        }
    }
    
    // Retrieve information of selected board game to be loaded into the view
    $sql = "SELECT * FROM board_games WHERE game_id=" . $game_id . " LIMIT 1";

    $result = $connection->query($sql);
    if (!empty($result)) {
        $board_game = $result->fetch_assoc();
        
        
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

<h1 class="form_heading">Delete board game</h1>

    

<form class="registration_form" action="delete_board_game.php"
  method="post" name="submitnewsalesrecord">


    <ul>
        <li>
            <label for="game">Board game:</label>
            <input type="text" name="game" id="game" disabled readonly required pattern="^[A-z]+$" oninvalid="this.setCustomValidity('First name must not be empty and must only contain alphabetical characters.')" onchange="this.setCustomValidity('')" value="<?php if(isset($board_game['game'])) {echo $board_game['game'];} ?>"/>
        </li>
        
        <li>
            <label for="min_players">Minimum players:</label>
            <input type="text" name="min_players" id="min_players" disabled readonly required pattern="^[A-z]+$" oninvalid="this.setCustomValidity('Family name must not be empty and must only contain alphabetical characters.')" onchange="this.setCustomValidity('')" value="<?php if(isset($board_game['min_players'])) {echo $board_game['min_players'];} ?>"/>
        </li>
        
        <li>
            <label for="max_players">Maximum players:</label>
            <input type="text" name="max_players" id="max_players" disabled readonly required oninvalid="this.setCustomValidity('Email must not be empty and must contain the @ symbol.')" onchange="this.setCustomValidity('')" value="<?php if(isset($board_game['max_players'])) {echo $board_game['max_players'];} ?>"/>
        </li>
        
        <li>
            <input type="hidden" name="id" value="<?php if(isset($board_game['game_id'])) {echo $board_game['game_id'];} ?>"/>
            <button class="submit" type="submit" name="submit">Delete</button>
        </li>
    </ul>
    
</form>

    
<?php 
    //Display board game table on page.
    
    include 'connect.php';
    
    $sql = "SELECT * FROM board_games";

    $result = $connection->query($sql);
       
    if($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th></th><th></th><th>Game</th><th>Min players</th><th>Max players</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>";
            echo "<a href='update_board_game.php?id=".$row['game_id']."'>Update</a>";
            echo "</td><td>";
            echo "<a href='delete_board_game.php?id=".$row['game_id']."'>Delete</a>";
            echo "</td><td>";
            echo $row['id'];
            echo "</td><td>";
            echo $row['game'];
            echo "</td><td>";
            echo $row['min_players'];
            echo "</td><td>";
            echo $row['max_players'];
            echo "</td></tr>";
        }
        echo "</table>";
    }
   
    
?>
</body>
</html>