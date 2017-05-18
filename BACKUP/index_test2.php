<?php

$servername = 'localhost';
$username= "bop_admin";
$password="GoneWest";
$dbname="compliance";

/*
//Create SQLi Conenction

$conn = new mysqli($servername, $user, $pass, 'compliance');

//Check
if ($conn->connect_error) {
	die("connection failed: ".$conn->connect_error);
}

echo "SQLi Connected succesfully!";
*/

/*
//TEST  PDO connection
try {
	$conn = new PDO("mysql:host=$servername;dbname=compliance", $username, $password);
	//  set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	echo "Connected successfully using PDO";
}
catch(PDOException $e){
	echo "Connection failed: ".$e->getMessage();
}

*/

//connect with PDO module and return result

echo "<table style='border: solid 1px black;'>";
echo "<tr><th>OEM</th><th>Model</th><th>Pressure Rating (psi)</th><th>Closing Area (in<sup>2</sup>)</th><th>Closing ratio</th><th>closing volume</th></tr>";

class TableRows extends RecursiveIteratorIterator { 
    function __construct($it) { 
        parent::__construct($it, self::LEAVES_ONLY); 
    }

    function current() {
        return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
    }

    function beginChildren() { 
        echo "<tr>"; 
    } 

    function endChildren() { 
        echo "</tr>" . "\n";
    } 
}
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT name AS OEM, BOP_model, BOP_rating, bop_closingarea, BOP_closingratio, BOP_closingvol FROM BOP,OEM WHERE BOP.BOP_OEM_id=OEM.id ORDER BY OEM"); 
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) { 
        echo $v;
    }
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
echo "</table>";
?>
<br />
<b>id - OEM</b>
<?php
try {
	//SELECT OEM'S FOR WHICH WE HAVE A BOP
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$OEM_avail = $conn->query('SELECT DISTINCT OEM.id, name AS OEM FROM OEM, BOP where OEM.ID=BOP.BOP_OEM_ID ORDER BY OEM');
	$OEM_avail->setFetchMod(PDO::FETCH_ASSOC);
	while($row = $OEM_avail->fetch()) {
    	echo $row['id']." - ".$row['OEM']."\n";
	}
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>