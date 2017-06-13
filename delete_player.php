<?php 
    $playerid = intval($_GET['playerid']);
    include 'connect.php';
    
    //If data has been submitted to the page, delete relevent player information
    if(isset($_POST['playerid'])){
        
        $sql = "DELETE FROM players WHERE player_id=".$_POST['playerid'];
        
        if ($connection->query($sql)) {
            $updatemessage = "Successfully deleted";
            
        } else {
            $updatemessage = $connection->error;
        }
    }

    // Retrieve information of selected player to be loaded into the view
    $sql = "SELECT * FROM players WHERE player_id = " . $playerid . " LIMIT 1";

    $result = $connection->query($sql);
    if (!empty($result)) {
        $player = $result->fetch_assoc();
        
        
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

<h1 class="form_heading">Delete player</h1>

    

<form class="registration_form" action="delete_player.php"
  method="post" name="submitnewsalesrecord">


    <ul>
        <li>
            <label for="firstname">First name:</label>
            <input type="text" name="firstname" id="firstname" disabled readonly required pattern="^[A-z]+$" oninvalid="this.setCustomValidity('First name must not be empty and must only contain alphabetical characters.')" onchange="this.setCustomValidity('')" value="<?php if(isset($player['first_name'])) {echo $player['first_name'];} ?>"/>
        </li>
        
        <li>
            <label for="familyname">Family name:</label>
            <input type="text" name="lastname" id="lastname" disabled readonly required pattern="^[A-z]+$" oninvalid="this.setCustomValidity('Family name must not be empty and must only contain alphabetical characters.')" onchange="this.setCustomValidity('')" value="<?php if(isset($player['last_name'])) {echo $player['last_name'];} ?>"/>
        </li>
        
        <li>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" disabled readonly required oninvalid="this.setCustomValidity('Email must not be empty and must contain the @ symbol.')" onchange="this.setCustomValidity('')" value="<?php if(isset($player['email'])) {echo $player['email'];} ?>"/>
        </li>
        
        <li>
            <label for="phone">Phone:</label>
            <input type="text" name="phone" id="phone" disabled readonly required pattern="^\+(?:[0-9] ?){6,14}[0-9]$" oninvalid="this.setCustomValidity('Phone input must contain: 7-15 digits, the international prefix and only spaces must separate groups of numbers. Example: +99 99 9999 9999')" onchange="this.setCustomValidity('')" value="<?php if(isset($player['phone'])) {echo $player['phone'];} ?>"/>
        </li>
        
        <li>
            <input type="hidden" name="playerid" value="<?php if(isset($player['player_id'])) {echo $player['player_id'];} ?>"/>
            <button class="submit" type="submit" name="submit">Delete</button>
        </li>
    </ul>
    
</form>

    
<?php 
   //Display player table on page.
    
    include 'connect.php';
    
    $sql = "SELECT * FROM players";

    $result = $connection->query($sql);
       
    if($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th></th><th></th><th>memberid</th><th>firstname</th><th>familyname</th><th>email</th><th>phone</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>";
            echo "<a href='update_player.php?playerid=".$row['player_id']."'>Update</a>";
            echo "</td><td>";
            echo "<a href='delete_player.php?playerid=".$row['player_id']."'>Delete</a>";
            echo "</td><td>";
            echo $row['player_id'];
            echo "</td><td>";
            echo $row['first_name'];
            echo "</td><td>";
            echo $row['last_name'];
            echo "</td><td>";
            echo $row['email'];
            echo "</td><td>";
            echo $row['phone'];
            echo "</td></tr>";
        }
        echo "</table>";
    }
   
    
?>
</body>
</html>