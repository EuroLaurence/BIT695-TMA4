<?php 
    $id = intval($_GET['id']);
    include 'connect.php';
    
    //If data has been submitted to the page, delete relevent member information
    if(isset($_POST['id'])){
        
        $sql = "DELETE FROM event_schedule WHERE event_id=".$_POST['id'];
        
        if ($connection->query($sql)) {
            $updatemessage = "Successfully deleted";
            
        } else {
            $updatemessage = $connection->error;
        }
    }

    // Retrieve information of selected event to be loaded into the view
    $sql = "SELECT * FROM event_schedule WHERE event_id=".$id;

    $result = $connection->query($sql);
    if (!empty($result)) {
        $event = $result->fetch_assoc();
        
        
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

<h1 class="form_heading">Delete event</h1>

    

<form class="registration_form" action="delete_event.php"
  method="post" name="submitnewsalesrecord">


    <ul>
        <li>
            <label for="title">Event title:</label>
            <input type="text" name="title" id="title" disabled readonly required value="<?php if(isset($event['event_title'])) {echo $event['event_title'];} ?>"/>
        </li>
        
        <li>
            <label for="date">Event date:</label>
            <input type="text" name="date" id="date" disabled readonly required value="<?php if(isset($event['event_date'])) {echo $event['event_date'];} ?>"/>
        </li>
        
        <li>
            <label for="location">Event location:</label>
            <input type="text" name="location" id="location" disabled readonly required value="<?php if(isset($event['event_location'])) {echo $event['event_location'];} ?>"/>
        </li>
        
        <li>
            <input type="hidden" name="id" value="<?php if(isset($event['event_id'])) {echo $event['event_id'];} ?>"/>
            <button class="submit" type="submit" name="submit">Delete</button>
        </li>
    </ul>
    
</form>

    
<?php 
   //Display event table on the page.
    
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
   
    
?>
</body>
</html>