<?php 
    $game_id = intval($_GET['id']);
    include 'connect.php';
  
    //If data has been submitted to the page, update relevent board game information
    if(isset($_POST['game'])){
        $game = $_POST['game'];
        $min_players = $_POST['min_players'];
        $max_players = $_POST['max_players'];
        
        if (!fieldsEmpty() && validateInput()) {  
        
            if($stmt = $connection->prepare("UPDATE `board_games` SET game=?, min_players=?, max_players=? WHERE game_id=?")) {
                $stmt->bind_param("siii", $game, $min_players, $max_players, $_POST['id']);


                $stmt->execute();
                $stmt->close();


            } else {
                echo "Prepare failed: ( " . $connection->errno . ") " . $connection->error;
            }
        }
    }
            
            // Retrieve relevant board game information to be loaded into the form.
            $sql = "SELECT * FROM board_games WHERE game_id = " . $game_id . " LIMIT 1";

            $result = $connection->query($sql);
            if (!empty($result)) {
                $board_game = $result->fetch_assoc();


            } else {
                echo "Error: " . "<br>" . $connection->error;
            }
                $connection->close();
          


 //Check whether or not any fields are empty
    function fieldsEmpty() {
        if(empty($_POST['game']) || empty($_POST['min_players']) || empty($_POST['max_players'])) {
            return true;
        } else {
            return false;
        }
    }
    
    //Validate input and display error message if any data is invalid
    function validateInput() {
        $validationSuccessful = true;
        $errorMessage = "";


        //Validate game
        if (!preg_match("/^.{1,100}$/", $_POST['game'])) {
            $validationSuccessful = false;
            $errorMessage .= "Game is invalid.". $_POST['game'];
        }

        //Validate min players
        if (!preg_match("/^[0-9]{1,2}$/", $_POST['min_players'])) {
            $validationSuccessful = false;
            $errorMessage .= "Min_players is invalid." . $_POST['min_players'];
        }

        //Validate min players
        if (!preg_match("/^[0-9]{1,2}$/", $_POST['max_players'])) {
            $validationSuccessful = false;
            $errorMessage .= "Max_players is invalid." . $_POST['max_players'];
        }

        if($validationSuccessful == true) {
            return true;
        } else {
            echo $errorMessage;
            return false;
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">
<title>Title of the document</title>
</head>

<body>

<h1 class="form_heading">Update board game information</h1>

    

<form class="registration_form" action="update_board_game.php"
  method="post" name="submitnewsalesrecord">


    <ul>
        <li>
            <label for="game">Board game name:</label>
            <input type="text" name="game" id="game" required pattern=".{1,100}" oninvalid="this.setCustomValidity('Board game name must not be empty or exceed character limit.')" onchange="this.setCustomValidity('')" value="<?php if(isset($board_game['game'])) {echo $board_game['game'];} ?>"/>
        </li>
        
        <li>
            <label for="min_players">Minimum players:</label>
            <input type="text" name="min_players" id="min_players" required pattern="\d{1,2}" oninvalid="this.setCustomValidity('Minimum players must not be empty and must be less than 100.')" onchange="this.setCustomValidity('')" value="<?php if(isset($board_game['min_players'])) {echo $board_game['min_players'];} ?>"/>
        </li>
        
        <li>
            <label for="max_players">Maximum players:</label>
            <input type="text" name="max_players" id="max_players" required pattern="\d{1,2}" oninvalid="this.setCustomValidity('Maximum players must not be empty and must be less than 100.')" onchange="this.setCustomValidity('')" value="<?php if(isset($board_game['max_players'])) {echo $board_game['max_players'];} ?>"/>
        </li>
        
        <li>
            <input type="hidden" name="id" value="<?php if(isset($board_game['game_id'])) {echo $board_game['game_id'];} ?>"/>
            <button class="submit" type="submit" name="submit">Update</button>
        </li>
    </ul>
    
</form>

    
<?php 
   //Load board game table onto the page.
    
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