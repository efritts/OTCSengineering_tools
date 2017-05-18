<?php
/*BOPdetail.php
 *
 * This file connects to the mySQL database and constructs a summary table of 1 BOP in the database. 
 *
 * 
 * @author David Hanks Feb 5, 2016
 * 
 */
 
//Setup Connection with MySQL database
$servername = "localhost";
$username= "bop_user";
$password="G0n3W357";
$dbname="compliance";
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$bop_id=$_GET["id"];  //UPDATED NEEDED.  assign a default value when no Get['id'] is unavailable.

//$bop_rows="";
$bop_reference="";

try {
    //Get 1 BOP given by $bop_id
    $stmt_bopid = $conn->prepare("SELECT BOP_note, BOP_minclosing, BOP_openingvol, BOP_closingvol, BOP.id AS BOP_id, OEM.id as OEM_id, name AS OEM, BOP_model, BOP_rating, BOP_borediameter, BOP_closingarea, BOP_closingratio, BOP_taildiameter FROM BOP, OEM WHERE BOP.BOP_OEM_id=OEM.id AND BOP.id='".$bop_id."' ORDER BY OEM LIMIT 1;"); 
    $stmt_bopid->execute();
	
	//UPDATE NEEDED add units in the while loop rather than the table in case there is no value.
    while( $row = $stmt_bopid->fetch(PDO::FETCH_ASSOC)){
    	//$bop_rows.="<tr><td>".$row['OEM']."</td><td><a title=\"Click for details\" href=\"?page=bop&sub=Detail&id=".$row['BOP_id']."\">".$row['BOP_model']."</a></td><td>".$row['BOP_rating']."</td><td>".$row['BOP_borediameter']."</td><td>".$row['BOP_closingarea']."</td><td>".$row['BOP_closingratio']."</td></td>";
		$bop_model=$row["BOP_model"];
		$bop_OEM=$row["OEM"];
		$bop_bore=$row['BOP_borediameter'];
		$bop_rating=$row['BOP_rating'];
		$bop_closingarea=$row['BOP_closingarea'];
		$bop_closingratio=$row['BOP_closingratio'];		
		$bop_closingvol=$row['BOP_closingvol'];
		$bop_openingvol=$row['BOP_openingvol'];
		$bop_minclosing=$row['BOP_minclosing'];
		$bop_notes=$row['BOP_note'];
    }
	
	//get references for BOPs
	$stmt_bopref = $conn->prepare("SELECT reference_id, reference_link, reference_description FROM `BOP-reference`, BOP, reference WHERE BOP.id=`BOP-reference`.BOP_id AND reference.id=`BOP-reference`.reference_id AND BOP.id='".$bop_id."'"); 
    $stmt_bopref->execute();
	$refnum=1;
    while( $row = $stmt_bopref->fetch(PDO::FETCH_ASSOC)){
    	$bop_reference.="<tr><td><a href=\"".$row['reference_link']."\">Reference ".$refnum."</a></td><td>".$row['reference_description']."</td></tr>";
    	$refnum +=1;
	}
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;

//Setup a row for references only if they exist
if(!empty($bop_reference)){	$BOP_RefTable = "<tr><td colspan=\"4\"><b>References</b></td></tr><tr><td colspan=\"4\"><table>".$bop_reference."</table></td></tr>";}
else {	$BOP_RefTable ="";}

//Setup a row for Notes only i fthey exist
if(!empty($bop_notes)){$BOP_NotesTable = "<tr><td colspan=\"4\"><b>Notes:</b><br /><textarea cols=\"50\" rows=\"5\">".$bop_notes."</textarea></td></tr>";}
else { $BOP_NotesTable ="";}

//echo "<table>".$bop_rows."</table>";
?>


	<table class="w3-table w3-striped">
		<tbody>
		<tr><td><b>Model</b></td><td colspan="3"><?php echo $bop_model; ?></td></tr>
		<tr><td><b>OEM</b></td><td colspan="3"><?php echo $bop_OEM; ?></td></tr>
		<tr><td><b>Bore size</b></td><td><?php echo $bop_bore; ?></td><td><b>Pressure rating</b></td><td><?php echo $bop_rating; ?> psi</td></tr>
		<tr><td><b>Closing Area</b></td><td><?php echo $bop_closingarea; ?> in<sup>2</sup></td><td><b>Opening Area</b></td><td>250 in<sup>2</sup></td></tr>
		<tr><td><b>Closing Ratio</b></td><td colspan="3"><?php echo $bop_closingratio; ?></td></tr>
		<tr><td><b>Closing volume</b></td><td><?php echo $bop_closingvol; ?> gal</td><td><b>Opening Volume</b></td><td><?php echo $bop_openingvol; ?> gal</td></tr>
		<tr><td><b>Min. Closing Pressure</b></td><td colspan="3"><?php echo $bop_minclosing; ?> psi</td></tr>
		<?php echo $BOP_RefTable; ?>
		<?php echo $BOP_NotesTable; ?>
		</tbody>	
	</table>