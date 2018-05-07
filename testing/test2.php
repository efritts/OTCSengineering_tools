<?php

//$servername = "localhost";
//$username = "bop_user";
//$password = "G0n3W357";
//$dbname = "compliance";

//mysql://b2d3fec699a396:4fda2c9a@us-cdbr-iron-east-05.cleardb.net/heroku_fd05977f8785f28?reconnect=true
$servername = "us-cdbr-iron-east-05.cleardb.net";
$username = "b2d3fec699a396";
$password = "4fda2c9a";
$dbname = "heroku_fd05977f8785f28";

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$oem = array();  //multi-dimensional array to pass to javascript.

try {
    $stmt = $conn->prepare("SELECT DISTINCT OEM.id, name AS OEM FROM OEM, BOP WHERE OEM.ID=BOP.BOP_OEM_ID ORDER BY OEM"); 
    $stmt->execute();
    while( $row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $oem[]=$row;
    }
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<script type="text/javascript">
var OEM = <?php echo json_encode($oem, JSON_PRETTY_PRINT) ?>;
//alert(OEM[1]['OEM']);
</script>

<?php
//echo "<br />".json_encode(getenv(), JSON_PRETTY_PRINT);
//echo "{CLEARDB_DATABASE_URL}= ".getenv("CLEARDB_DATABASE_URL");
echo "Test Save";
//mysql://b2d3fec699a396:4fda2c9a@us-cdbr-iron-east-05.cleardb.net/heroku_fd05977f8785f28?reconnect=true
?>