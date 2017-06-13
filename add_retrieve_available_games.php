<?php 
   
    
//If data has been submitted to the page, store data
if(isset($_POST['game'])){
    $game = $_POST['game'];
    $player = $_POST['player'];
        
        include 'connect.php';
            
        if ($stmt = $connection->prepare("INSERT INTO board_game_availability (game, player_id) VALUES (?, ?)")) {
            $stmt->bind_param("si", $game, $player);
               

            $stmt->execute();
            $stmt->close();
                
        } else {
            echo "Prepare failed: ( " . $connection->errno . ") " . $connection->error;
        }
        
    $connection->close();
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
<h1 class="form_heading">Assign board game to player</h1>

<!-- Use Angular 1.x to dynamically load game and player details into selection boxes -->
<div ng-app="dropDownApp" ng-controller="gamesCtrl"> 
<form class="registration_form" action="add_retrieve_available_games.php"
  method="post" name="submitnewsalesrecord">


    <ul>
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
            <button class="submit" type="submit" name="submit">Assign</button>
        </li>
    </ul>
    
</form>
</div>    

<script>
// Retrieve board game and player details from server
// into the front-end model.
var app = angular.module('dropDownApp', []);
app.controller('gamesCtrl', function($scope, $http) {
    $http.get("get_board_games.php")
    .then(function (response) {$scope.games = response.data;})
    $http.get("get_players.php")
    .then(function (response) {$scope.players = response.data;});
});
</script>
    
<?php
    //Load board game availability table onto the page.
    include 'connect.php';
    
    
    $sql = "SELECT board_game_availability.id, board_game_availability.game, players.first_name, players.last_name FROM board_game_availability INNER JOIN players ON board_game_availability.player_id=players.player_id
";

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
    $connection->close();
?>
</body>
</html>