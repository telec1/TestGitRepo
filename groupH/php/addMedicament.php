<html>
	<head>
		<link rel="stylesheet" href="styleHomeWindow.css" type="text/css" />
	</head>
	<body><div class="mainDiv">
<?php
  // For the testing of this database, the username and password are the same
  // They are the names of our staff members.

if(!isset($_POST['medicamentID']) OR !isset($_POST['quantity']) OR !isset($_POST['physicianID']) OR !isset($_POST['nurseID']) OR !isset($_POST['patientID'])){
  include('index.php');
  exit();
 }

session_start();

$medicamentID = (int)$_POST['medicamentID'];
$quantity = (double)$_POST['quantity'];
$physicianID = (int)$_POST['physicianID'];
$nurseID= (int)$_POST['nurseID'];
$patientID = (int)$_POST['patientID'];
$note = $_POST['note'];


include('pdo.inc.php');

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    /*** echo a message saying we have connected ***/
    // echo 'Connected to database<br />';


    /*** set the error reporting attribute ***/
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /*** prepare the SQL statement ***/
    $stmt = $dbh->prepare("INSERT INTO `medicine`(`medicineID`, `time`, `quantity`, `medicamentID`, `patientID`, `staffID_nurse`, `staffID_physician`, `note`) VALUES 
	(null,now(),:quantity,:medicamentID,:patientID,:nurseID,:physicianID,:note)");

    /*** bind the paramaters ***/
	$stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT); 
	$stmt->bindParam(':medicamentID', $medicamentID, PDO::PARAM_INT);
    $stmt->bindParam(':patientID', $patientID, PDO::PARAM_INT);
    $stmt->bindParam(':nurseID', $nurseID, PDO::PARAM_INT);
	$stmt->bindParam(':physicianID', $physicianID, PDO::PARAM_INT);
    $stmt->bindParam(':note', $note, PDO::PARAM_STR, 5);

    /*** execute the prepared statement ***/
    $stmt->execute();

    // redirect to the page home.php
    header('location: ../patient.php?id='.$patientID);

    

    /*** close the database connection ***/
    $dbh = null;

    }
catch(PDOException $e)
    {
    echo $e->getMessage();
    }



?>
	</div></body>
</html> 