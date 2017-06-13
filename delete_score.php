<?php 
    $id = intval($_GET['id']);
    include 'connect.php';
    
    //If data has been submitted to the page, delete relevant scoring information
    if(isset($_POST['id'])){
        
        $sql = "DELETE FROM scoring WHERE id=".$_POST['id'];
        
        if ($connection->query($sql)) {
            $updatemessage = "Successfully deleted";
            
        } else {
            $updatemessage = $connection->error;
        }
    }

    // Retrieve information of selected score to be loaded into the view
    $sql = "SELECT scoring.id, players.first_name, players.last_name, players.email, scoring.game, event_schedule.event_title, scoring.score FROM scoring INNER JOIN players ON scoring.player_id = players.player_id INNER JOIN event_schedule ON scoring.event_title = event_schedule.event_title WHERE scoring.id=".$id;

    $result = $connection->query($sql);
    if (!empty($result)) {
        $score = $result->fetch_assoc();
        
        
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

<h1 class="form_heading">Delete score</h1>

    

<form class="registration_form" action="delete_score.php"
  method="post" name="submitnewsalesrecord">


    <ul>
        <li>
            <label for="title">Event title:</label>
            <input type="text" name="title" id="title" disabled readonly required value="<?php if(isset($score['event_title'])) {echo $score['event_title'];} ?>"/>
        </li>
        
        <li>
            <label for="game">Game:</label>
            <input type="text" name="game" id="game" disabled readonly required value="<?php if(isset($score['game'])) {echo $score['game'];} ?>"/>
        </li>
        
        <li>
            <label for="player">Player:</label>
            <input type="text" name="player" id="player" disabled readonly required value="<?php if(isset($score['first_name'])) {echo $score['first_name'] . " " . $score['last_name'] . " - " . $score['email'];} ?>"/>
        </li>
        
        <li>
            <label for="game">Score:</label>
            <input type="text" name="game" id="game" disabled readonly required value="<?php if(isset($score['score'])) {echo $score['score'];} ?>"/>
        </li>
        
        <li>
            <input type="hidden" name="id" value="<?php if(isset($score['id'])) {echo $score['id'];} ?>"/>
            <button class="submit" type="submit" name="submit">Delete</button>
        </li>
    </ul>
    
</form>

    
<?php 
    //Display scoring table on the page.
    
    include 'connect.php';
    
   $sql = "SELECT scoring.id, players.first_name, players.last_name, scoring.game, event_schedule.event_title, scoring.score FROM scoring INNER JOIN players ON scoring.player_id = players.player_id INNER JOIN event_schedule ON scoring.event_title = event_schedule.event_title";

    $result = $connection->query($sql);
       
    if($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th></th><th>First name</th><th>Last name</th><th>Game</th><th>Event title</th><th>Score</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>";
            echo "<a href='delete_score.php?id=".$row['id']."'>Delete</a>";
            echo "</td><td>";
            echo $row['first_name'];
            echo "</td><td>";
            echo $row['last_name'];
            echo "</td><td>";
            echo $row['game'];
            echo "</td><td>";
            echo $row['event_title'];
            echo "</td><td>";
            echo $row['score'];
            echo "</td></tr>";
        }
        echo "</table>";
    }
   
    
?>
</body>
</html>