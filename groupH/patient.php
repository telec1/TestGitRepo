<html>
	<head>
		<link rel="stylesheet" href="/groupH/assets/css/styleHomeWindow.css" type="text/css" />
		<link rel="stylesheet" href="/groupH/assets/css/new.css" type="text/css" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		
		
		
		<script src="/groupH/assets/js/main.js"></script>
		
	</head>
	<body><div class="mainDiv">
	
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
		$patientID = (int)($_GET['id']);
		if($patientID >0){
				echo "<nav><a class='logoutbutton' href='logout.php' >Logout</a>\n";
				echo "<a class='homeButton' href='home.php' >Home</a></nav>\n";

				echo "<div id='tabnav'><a href='#' class='active' data-tab='patient'>Patient</a> <a href='#' data-tab='signs'>Signs</a> <a href='#' data-tab='Medicines'>Medicines</a> </div>";
				echo "<div class='tabs'><div class='tab open' data-tab='patient'>";
				$sql0 = "SELECT name, first_name, Picture
					FROM patient
					WHERE patient.patientID = :patientID";
				$statement0 = $dbh->prepare($sql0);
				$statement0->bindParam(':patientID', $patientID, PDO::PARAM_INT);
				$result0 = $statement0->execute();
				
				while($line = $statement0->fetch()){
				
					echo "<h1> Patient : ".$line['first_name']."  ".$line['name']. " </h1>\n";
					echo "<div class='image'><img src='/groupH/assets/img/".$line['Picture']."'alt='Patient picture'></div>\n";
				}
				echo "</div><div class='tab' data-tab='signs'>";
				$sql = "SELECT distinct vital_sign.signID, sign.sign_name
				FROM vital_sign, sign WHERE vital_sign.signID = sign.signID AND vital_sign.patientID = :patientID";
				
				$statement = $dbh->prepare($sql);
				$statement->bindParam(':patientID', $patientID, PDO::PARAM_INT);
				$signs = $statement->execute();
				echo "<div class='allVitalSigns'>";
					while($signLine = $statement->fetch()){
						$sql = "SELECT value, time, note FROM vital_sign WHERE vital_sign.patientID = :patientID AND vital_sign.signid = :signID";
						$innerStatement = $dbh->prepare($sql);
						$innerStatement->bindParam(':patientID', $patientID, PDO::PARAM_INT);
						$innerStatement->bindParam(':signID', $signLine['signID'], PDO::PARAM_INT);
						$result = $innerStatement->execute();
						echo "<div class='vitalSign'><h2>".$signLine["sign_name"]."</h2>";
							echo "<table class='hover'><tr><th>Vital Sign Value</th><th>Time</th><th>Note</th></tr>";
							while($line = $innerStatement->fetch()){
								echo "<tr><td>".$line['value']. "</td><td>".$line['time']."</td><td>".$line['note']."</td></tr>";
							}
						echo "</table></div>";
						
					}
				echo "<h2>Insert new vital sign</h2>\n";
				echo "<form action='php/addsign.php' method='POST'>\n";
					echo "<input type='hidden' name='patientID' value='$patientID'>\n";
					echo "<table><tr><td>Vital sign</td><td><select name='signID'>\n";
				$sql = "SELECT * FROM sign";
					$statement = $dbh->prepare($sql);
					$result = $statement->execute();
					while($line = $statement->fetch()){
						echo "<option value='".$line['signID']."'>".$line['sign_name']."</option>\n";
					
					}
				echo "</select></td></tr>\n";
				echo "<tr><td>Value</td><td><input type='text' name='val'></td></tr>\n";
				echo "<tr><td>Note</td><td><textarea name='note'></textarea></td></tr>\n";
				echo "<tr><td><input class=\"logoutbutton\" type='submit' value='Add sign'></td></tr></table></form>";
				echo "</div>";
				echo "</div><div class='tab' data-tab='Medicines'>";
				
				
				echo "<h2>Medicines</h2>";
					
				$sql = "SELECT o.time,o.quantity,o.note, m.medicament_name, m.unit, nurse.name as nurseName, doctor.name as doctorName FROM medicine o
					join medicament m on o.medicamentID = m.medicamentID
					join staff nurse on nurse.staffID=o.staffID_nurse
					join staff doctor on doctor.staffID = o.staffID_physician where o.patientID = :patientID";
			
				$statement = $dbh->prepare($sql);
				$statement->bindParam(':patientID', $patientID, PDO::PARAM_INT);
				$signs = $statement->execute();
				echo "<table class='hover'><tr><th>Medicament</th><th>Quantity</th><th>Unit</th><th>Time</th><th>Note</th><th>Doctor</th><th>Nurse</th></tr>";
				while($line = $statement->fetch()){
					echo "<tr><td>".$line['medicament_name']. "</td><td>".$line['quantity']."</td><td>".$line['unit']. "</td><td>".$line['time']. "</td><td>".$line['note']. "</td><td>".$line['doctorName']. "</td><td>".$line['nurseName']. "</td></tr>";
				}
			echo "</table>";
			
			echo "<h2>Insert Medicament Order</h2>\n";
			echo "<form action='php/addMedicament.php' method='POST'>\n";
				echo "<input type='hidden' name='patientID' value='$patientID'>\n";
				echo "<table><tr><td>Medicament</td><td><select name='medicamentID'>\n";
				// Medicament-Select
			$sql = "SELECT * FROM medicament";
				$statement = $dbh->prepare($sql);
				$result = $statement->execute();
				while($line = $statement->fetch()){
					echo "<option value='".$line['medicamentID']."'>".$line['medicament_name']."(".$line['unit'].")</option>\n";
				}
			echo "</select></td></tr>\n";
			echo "<tr><td>Quantity</td><td><input type='text' name='quantity'></td></tr>\n";
			echo "<tr><td>Note</td><td><textarea name='note'></textarea></td></tr>\n";
			
			// Physician-Select
			echo "<tr><td>Physician</td><td><select name='physicianID'>\n";
			$sql = "SELECT * FROM staff where fonctionid=2";
			$statement = $dbh->prepare($sql);
			$result = $statement->execute();
			while($line = $statement->fetch()){
				echo "<option value='".$line['staffID']."'>".$line['first_name']." ".$line['name']."</option>\n";
			}
		echo "</select></td></tr>\n";
		
		// Nurse-Select
		echo "<tr><td>Nurse</td><td><select name='nurseID'>\n";
		$sql = "SELECT * FROM staff where fonctionid=1";
		$statement = $dbh->prepare($sql);
		$result = $statement->execute();
		while($line = $statement->fetch()){
			echo "<option value='".$line['staffID']."'>".$line['first_name']." ".$line['name']."</option>\n";
		}
		echo "</select></td></tr>\n";
		
		echo "<tr><td><input type='submit' class=\"logoutbutton\" value='Add Medicament Order'></td></tr></table></form>";
				echo "</div></div>";

				
				
				
				
				
				
}
else{
echo "<h1>The patient does not exist</h1>";
}
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