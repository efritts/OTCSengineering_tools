<?php

//Add to functions.php
// Function for basic field validation (present and neither empty nor only white space
//Retuns TRUE if null or empty or not set
function IsNullOrEmptyString($question){
    return (!isset($question) || trim($question)==='');
}

//Setup Connection with MySQL database
$servername = "localhost";
$username= "bop_user";
$password="G0n3W357";
$dbname="compliance";
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$oem = array();  //multi-dimensional array to pass to javascript.

try {
	//Get the available OEMs
    $stmt = $conn->prepare("SELECT DISTINCT OEM.id, name AS OEM FROM OEM, BOP WHERE OEM.ID=BOP.BOP_OEM_ID ORDER BY OEM");   //Gets all OEM that have a BOP in the database. 
    $stmt->execute();
    while( $row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $oem[]=$row;
    }
	
	//Get all the BOPs
	$stmt_bop = $conn->prepare("SELECT BOP.id AS BOP_id, OEM.id as OEM_id, name AS OEM, BOP_model, BOP_closingarea, BOP_closingratio, BOP_taildiameter, BOP_pistondiameter FROM BOP, OEM WHERE BOP.BOP_OEM_id=OEM.id ORDER BY OEM;");   //Gets all . 
    $stmt_bop->execute();
    while( $row = $stmt_bop->fetch(PDO::FETCH_ASSOC)){
        $bop[]=$row;
    }
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

/*Manipulate $bop[].
 * Add closing area by using the piston diameter - rod diameter
 * 
 * Calculate tailrod area if the diameter is known.
 * 
 * if anything can't be calculated, replace "null" with "unavailable" 
 */
for ($row = 0; $row < count($bop); $row++){
	if (!IsNullOrEmptyString($bop[$row]["BOP_taildiameter"])&& is_numeric($bop[$row]["BOP_taildiameter"])){
			$d=$bop[$row]["BOP_taildiameter"];
			$area= round(pi()*pow($d,2)/4.0,2);		
			$bop[$row]["BOP_tailrodarea"]=$area;
	}
	else{
		$bop[$row]["BOP_tailrodarea"]="unavailable";
	}

	//else tailrod area is calculated and inserted into the array
	if ($bop[$row]["BOP_closingarea"]==null){
		if (!IsNullOrEmptyString($bop[$row]["BOP_pistondiameter"])){
			$d=$bop[$row]["BOP_pistondiameter"];
			$area= round(pi()*pow($d,2)/4.0,2);
			$bop[$row]["BOP_closingarea"]=$area;
		}
		else{
			$bop[$row]["BOP_closingarea"]="unavailable";
		}
		
	}
}  

var_dump($bop);

$dia = (float) $bop[4]["BOP_pistondiameter"];
$area = round(pi()*pow($dia,2)/4,2);
echo "<br /><hr />diameter=".$dia."in; Area = ".$area;
 
?>
