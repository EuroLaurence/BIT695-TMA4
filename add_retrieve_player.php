<?php 
   
    
//If data has been submitted to the page, validate data
if(isset($_POST['firstname'])){
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
        


    //If data is successfully validated, store data
    if (!fieldsEmpty() && validateInput()) {
        include 'connect.php';
            
        if ($stmt = $connection->prepare("INSERT INTO players (first_name, last_name, email, phone) VALUES (?, ?, ?, ?)")) {
            $stmt->bind_param("ssss", $firstname, $lastname, $email, $phone);
               

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
    if(empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email']) || empty($_POST['phone'])) {
        return true;
    } else {
        return false;
    }
}

//Validate input and display error message if any data is invalid
function validateInput() {
    $validationSuccessful = true;
    $errorMessage = "";
    
    
    //Validate first name
    if (validateName($_POST['firstname']) == false) {
        $validationSuccessful = false;
        $errorMessage .= "The first name: '$firstname' is invalid.";
    }
    
    //Validate family name
    if (validateName($_POST['lastname']) == false) {
        $validationSuccessful = false;
        $errorMessage .= "The family name: '$familyname' is invalid.";
    }
    
    //Validate email address
    if (validateEmail($_POST['email']) == false) {
        $validationSuccessful = false;
        $errorMessage .= "The email address: '$email' is invalid.";
    }
    
    //Validate phone number
    if (validatePhone($_POST['phone'] == false)) {
        $validationSuccessful = false;
        $errorMessage .= "The phone number: '$phone' is invalid.";
    }
    
    if($validationSuccessful == true) {
        return true;
    } else {
        echo $errorMessage;
        return false;
    }
}

//Validate first name
function validateName($name) {
    return preg_match("/^[A-z]+$/", $name);
}

//Validate email address
function validateEmail($email) {
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }   
}

//Validate phone number
function validatePhone($phone) {
    return preg_match("/^\+(?:[0-9] ?){6,14}[0-9]$/", $phone);
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
<h1 class="form_heading">Register new member</h1>

<form class="registration_form" action="add_retrieve_player.php"
  method="post" name="submitnewsalesrecord">


    <ul>
        <li>
            <label for="firstname">First name:</label>
            <input type="text" name="firstname" id="firstname" required pattern="^[A-z]+$" oninvalid="this.setCustomValidity('First name must not be empty and must only contain alphabetical characters.')" onchange="this.setCustomValidity('')"/>
        </li>
        
        <li>
            <label for="familyname">Last name:</label>
            <input type="text" name="lastname" id="lastname" required pattern="^[A-z]+$" oninvalid="this.setCustomValidity('Family name must not be empty and must only contain alphabetical characters.')" onchange="this.setCustomValidity('')"/>
        </li>
        
        <li>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required oninvalid="this.setCustomValidity('Email must not be empty and must contain the @ symbol.')" onchange="this.setCustomValidity('')"/>
        </li>
        
        <li>
            <label for="phone">Phone:</label>
            <input type="text" name="phone" id="phone" required pattern="^\+(?:[0-9] ?){6,14}[0-9]$" oninvalid="this.setCustomValidity('Phone input must contain: 7-15 digits, the international prefix and only spaces must separate groups of numbers. Example: +99 99 9999 9999')" onchange="this.setCustomValidity('')"/>
        </li>
        
        <li>
            <button class="submit" type="submit" name="submit">Register</button>
        </li>
    </ul>
    
</form>
    
<?php
    //Load player table onto the page.
    
    include 'connect.php';
    
    
    $sql = "SELECT * FROM players";

    $result = $connection->query($sql);
       
    if($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th></th><th></th><th>memberid</th><th>firstname</th><th>lastname</th><th>email</th><th>phone</th></tr>";
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
    $connection->close();
?>
</body>
</html>