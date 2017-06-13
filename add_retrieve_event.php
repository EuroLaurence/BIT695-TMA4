<?php 
   
    
//If data has been submitted to the page, validate data
if(isset($_POST['title'])){
    $event_title = $_POST['title'];
    $event_date = $_POST['date'];
    $event_location = $_POST['location'];
        


    //If data is successfully validated, store data
    if (!fieldsEmpty() && validateInput()) {
        include 'connect.php';
            
        if ($stmt = $connection->prepare("INSERT INTO event_schedule (event_title, event_date, event_location) VALUES (?, ?, ?)")) {
            $stmt->bind_param("sss", $event_title, $event_date, $event_location);
               

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
    if(empty($_POST['title']) || empty($_POST['date']) || empty($_POST['location'])) {
        return true;
    } else {
        return false;
    }
}

    //Validate input and display error message if any data is invalid
    function validateInput() {
        $validationSuccessful = true;
        $errorMessage = "";


        //Validate title
        if (!preg_match("/^.{1,250}$/", $_POST['title'])) {
            $validationSuccessful = false;
            $errorMessage .= "Title is invalid.". $_POST['title'];
        }

        //Validate date
        if (!preg_match("/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/", $_POST['date'])) {
            $validationSuccessful = false;
            $errorMessage .= "Date is invalid." . $_POST['date'];
        }

        //Validate location
        if (!preg_match("/^.{1,250}$/", $_POST['location'])) {
            $validationSuccessful = false;
            $errorMessage .= "Max_players is invalid." . $_POST['location'];
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
<h1 class="form_heading">Add new event</h1>

<form class="registration_form" action="add_retrieve_event.php"
  method="post" name="submitnewsalesrecord">


    <ul>
        <li>
            <label for="title">Event title:</label>
            <input type="text" name="title" id="title" required pattern=".{1,250}" oninvalid="this.setCustomValidity('Event title must contain data and be less than 250 characters max.')" onchange="this.setCustomValidity('')"/>
        </li>
        
        <li>
            <label for="date">Event date/time YYYY-MM-DD HH:MI:SS</label>
            <input type="text" name="date" id="date" required pattern="(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})" oninvalid="this.setCustomValidity('Date and time must match described format.')" onchange="this.setCustomValidity('')"/>
        </li>
        
        <li>
            <label for="location">Event location:</label>
            <input type="text" name="location" id="location" required pattern=".{1,250}" oninvalid="this.setCustomValidity('Event location must contain data and be less than 250 characters max.')" onchange="this.setCustomValidity('')"/>
        </li>
        
        <li>
            <button class="submit" type="submit" name="submit">Register</button>
        </li>
    </ul>
    
</form>
    
<?php
    
    // Load event table onto the page.
    include 'connect.php';
    
    
    $sql = "SELECT * FROM event_schedule";

    $result = $connection->query($sql);
       
    if($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th></th><th></th><th>Event ID</th><th>Event Title</th><th>Event Date</th><th>Event Location</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>";
            echo "<a href='update_scheduled_event.php?id=".$row['event_id']."'>Update</a>";
            echo "</td><td>";
            echo "<a href='delete_event.php?id=".$row['event_id']."'>Delete</a>";
            echo "</td><td>";
            echo $row['event_id'];
            echo "</td><td>";
            echo $row['event_title'];
            echo "</td><td>";
            echo $row['event_date'];
            echo "</td><td>";
            echo $row['event_location'];
            echo "</td></tr>";
        }
        echo "</table>";
    }
    $connection->close();
?>
</body>
</html>