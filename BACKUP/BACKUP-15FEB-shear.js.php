<?php
/*shear.js.php
 *
 * This file contains database calls using php which would not be possible in a js file. 
 *
 * @author David Hanks Feb 5, 2016
 */
 
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

function BOP_fields() {
    var OEM = <?php echo json_encode($oem, JSON_PRETTY_PRINT); /*Ex. OEM[0]['OEM']="Cameron"*/  ?>;  
    var theForm = document.forms["sheardata"];
    var BOPchoice = theForm.elements["BOP_select"];
    var divobj = document.getElementById("BOP_values");

    //construct the OEM select input box
    var count; //counter to cycle through all OEMs
    var OEMselect = "<select>";  
        
    for (count = 0; count < OEM.length; count++){
        OEMselect += "<option value=\""+OEM[count]['id']+"\">"+OEM[count]['OEM']+"</option>";
    }
    OEMselect +="</select>";
    
    if (BOPchoice[1].checked) {
        divobj.innerHTML = "<table style=\"width:auto\"><tr><td>Closing Area</td><td><input type=\"text\" name=\"closingArea\" id=\"bop_closingarea\" onkeyup=\"display_results()\" value=\"293.7\"/></td><td>in<sup>2</sup></td></tr><tr><td>Closing Ratio</td><td><input type=\"text\" name=\"bop_closingratio\" id=\"bop_closingratio\" onkeyup=\"display_results()\" value=\"19.6\"/></td><td></td></tr><tr><td>Tailrod Area</td><td><input type=\"text\" name=\"TailrodArea\" id=\"bop_trarea\" onkeyup=\"display_results()\"/></td><td>in<sup>2</sup></td></tr></table>";
    } 
    else {
        divobj.innerHTML = "Manufacturer " + OEMselect + " <br /> Model";
    }
    
}

<?php
/* select box should look like this
<select>
    
    <option value="{OEM[0]['id']}">{OEM[0]['OEM']}</option>
</select>
*/
?>
