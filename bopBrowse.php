<?php
/*bopBrowse.php
 *
 * This file connects to the mySQL database and constructs a summary table of all BOPs in the database. 
 *
 * 
 * @author David Hanks Feb 5, 2016
 * 
 */
 
$conn = connect_db();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$bop_rows="";

try {
    //Get all the BOPs
    $stmt_bop = $conn->prepare("SELECT BOP.id AS BOP_id, OEM.id as OEM_id, name AS OEM, BOP_model, BOP_rating, BOP_borediameter, BOP_closingarea, BOP_closingratio, BOP_taildiameter FROM BOP, OEM WHERE BOP.BOP_OEM_id=OEM.id ORDER BY OEM;");   //Gets all . 
    $stmt_bop->execute();
    while( $row = $stmt_bop->fetch(PDO::FETCH_ASSOC)){
    	$bop_rows.="<tr><td>".$row['OEM']."</td><td><a title=\"Click for details\" href=\"?page=bop&sub=Detail&id=".$row['BOP_id']."\">".$row['BOP_model']."</a></td><td>".$row['BOP_rating']."</td><td>".$row['BOP_borediameter']."</td><td>".$row['BOP_closingarea']."</td><td>".$row['BOP_closingratio']."</td></td>";
    }
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
?>
<table>
	<tr><td>OEM</td><td>Description</td><td>Pressure rating (psi)</td><td>Well bore diameter (in)</td><td>Closing area (in<sup>2</sup>)</td><td>Closing Ratio</td></tr>
	
<?php echo $bop_rows; ?>
</table>