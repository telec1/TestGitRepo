<html>
	<head>
		<link rel="stylesheet" href="/groupH/assets/css/styleHomeWindow.css" type="text/css" />
        <link rel="stylesheet" href="/groupH/assets/css/new.css" type="text/css" />
	</head>
	<body id="home"><div class="mainDiv">
<?php
 session_start();
 

// Test if the user is logged in.
// If no : back to the login page!
if(!isset($_SESSION['staffID'])){
  header('location: index.php');
  exit;
 }


include('php/pdo.inc.php');

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    echo "<nav><a class='logoutbutton' href='logout.php' >Logout</a><br></nav>\n";

    echo "<h1>Homepage of ".$_SESSION['first_name']." ".$_SESSION['name']."</h1>\n";

    /*** echo a message saying we have connected ***/
    echo '<h3>List of patients</h3>';
    $sql = "select * from patient";

    $result = $dbh->query($sql);
	
	echo "<div><table class='hover' align='center'>";
	
    while($line = $result->fetch()){
      echo "<td><a class='s2' href='patient.php?id=".$line['patientID']."'>";
	  echo  $line['first_name']. " " .$line['name']."</td>"; 
	  echo "</a></tr>";
        
      
    }
	echo "</table></div>";
    $dbh = null;
}
catch(PDOException $e)
{

    /*** echo the sql statement and error message ***/
    echo $e->getMessage();
}


?>

	</div></body>
</html> 