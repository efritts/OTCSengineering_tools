<?php

$servername = "localhost";
$username= "bop_user";
$password="G0n3W357";
$dbname="compliance";

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
alert(OEM[1]['OEM']);
</script>

<?php
echo "sent index 1 on book.";
?>