<?php 
   
    
//If data has been submitted to the page, validate data
if(isset($_POST['game'])){
    $event_title = $_POST['event'];
    $game = $_POST['game'];
    $player_id = $_POST['player'];
    $score = $_POST['score'];
        


    //If data is successfully validated, store data
    if (!fieldsEmpty() && validateInput()) {
        include 'connect.php';
            
        if ($stmt = $connection->prepare("INSERT INTO scoring (event_title, game, player_id, score) VALUES (?, ?, ?, ?)")) {
            $stmt->bind_param("ssii", $event_title, $game, $player_id, $score);
               

            $stmt->execute();
            $stmt->close();
                
        } else {
            echo "Prepare failed: ( " . $connection->errno . ") " . $connection->error;
        }
        
    $connection->close();
}
}

//Check whether or not any fields are empty
function fieldsEmpty() {
    if(empty($_POST['event']) || empty($_POST['game']) || empty($_POST['player']) || empty($_POST['score'])) {
        return true;
    } else {
        return false;
    }
}

    //Validate input and display error message if any data is invalid
    function validateInput() {
        $validationSuccessful = true;
        $errorMessage = "";
        
        //Validate score
        if (!preg_match("/^\d{1,250}$/", $_POST['score'])) {
            $validationSuccessful = false;
            $errorMessage .= "Score is invalid." . $_POST['score'];
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
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
<head>
<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">
<title>Add new boardgame</title>
</head>

<body>
<h1 class="form_heading">Assign score to player for a game from an event</h1>

<!-- Use Angular 1.x to dynamically load event, game and player information into the selection boxes -->
<div ng-app="dropDownApp" ng-controller="gamesCtrl"> 
<form class="registration_form" action="add_retrieve_score.php"
  method="post" name="submitnewsalesrecord">


    <ul>
        <li>
            <label for="event">Event:</label>
            <select name="event" id="event" required>
                <option ng-repeat="event in events" value="{{event.event_title}}">{{event.event_title}}</option>
            </select>
        </li>
        
        <li>
            <label for="game">Game:</label>
            <select name="game" id="game" required>
                <option ng-repeat="game in games" value="{{game.game}}">{{game.game}}</option>
            </select>
        </li>
        
        <li>
           <label for="player">Player:</label>
            <select name="player" id="player" required>
                <option ng-repeat="player in players" value="{{player.player_id}}">{{player.first_name}} {{player.last_name}} - {{player.email}}</option>
            </select>
        </li>
        
        <li>
            <label for="title">Score:</label>
            <input type="text" name="score" id="score" required pattern="\d{1,250}" oninvalid="this.setCustomValidity('Score must contain data and be less than 250 characters max.')" onchange="this.setCustomValidity('')"/>
        </li>
        
        <li>
            <button class="submit" type="submit" name="submit">Assign</button>
        </li>
    </ul>
    
</form>
</div>    

<script>
// Dynamically load the event, board game and player information
// from the server and into the front-end model.
var app = angular.module('dropDownApp', []);
app.controller('gamesCtrl', function($scope, $http) {
    $http.get("get_events.php")
    .then(function (response) {$scope.events = response.data;})
    $http.get("get_board_games.php")
    .then(function (response) {$scope.games = response.data;})
    $http.get("get_players.php")
    .then(function (response) {$scope.players = response.data;});
});
</script>
    
<?php
    // Load scoring table onto the page.
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
    $connection->close();
?>
</body>
</html>