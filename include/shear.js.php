<?php
/*shear.js.php
 *
 * This file contains database calls using php which would not be possible in a js file.
 *
 * @author David Hanks Feb 5, 2016
 * 
 */
 
//Setup Connection with MySQL database
$conn = connect_db();
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
    $stmt_bop = $conn->prepare("SELECT BOP.id AS BOP_id, OEM.id as OEM_id, name AS OEM, BOP_model, BOP_closingarea, BOP_closingratio, BOP_taildiameter FROM BOP, OEM WHERE BOP.BOP_OEM_id=OEM.id ORDER BY OEM;");   //Gets all . 
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
 * if anything can't be calculated, replace "null" with "unknown" 
 */

for ($row = 0; $row < count($bop); $row++){
    
    if (!IsNullOrEmptyString($bop[$row]["BOP_taildiameter"])&& is_numeric($bop[$row]["BOP_taildiameter"])){
            $d=$bop[$row]["BOP_taildiameter"];
            $area= round(pi()*pow($d,2)/4.0,2);     
            $bop[$row]["BOP_tailrodarea"]=$area;
    }
    else{
        $bop[$row]["BOP_tailrodarea"]=0;  
    }
    
    //calculate closing area from piston
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
?>
<script>
function BOP_fields() {
    /* Generates dynamic BOP information relevant for shear calculation
    * Activated when: 
    * the user selects "Select"/"Specify" option, 
    * the user selects a manufacturer, 
    * the user selects a model.
    *
    */
    
    var OEM = <?php echo json_encode($oem, JSON_PRETTY_PRINT); /*Ex. OEM[0]['OEM']="Cameron"*/  ?>;  
    var BOP = <?php echo json_encode($bop, JSON_PRETTY_PRINT); /*Ex. BOP[0]['BOP_model']="EVO 18-3/4 15k SBT"*/ ?>;
    var divobj = document.getElementById("BOP_values");
    var theForm = document.contains(document.forms["cids"]) ? document.forms["cids"] : document.forms["sheardata"]
    var BOPchoice = theForm.elements["BOP_select"];
    
    var alertdiag ="";
    var OEM_choice;
    var BOP_choice;
    //change the arrangment of the table if the selection is for the CIDS or the Shear Calculator
    var l3col_class = document.contains(document.forms["cids"]) ? "m4" : "l3 s4";  //l3col_class ->  left of 3 column class
    var m3col_class = document.contains(document.forms["cids"]) ? "m6 s10" : "l3 s4"; // middle of 3 column
    var r3col_class = document.contains(document.forms["cids"]) ? "m2 s2" : "s1 w3-margin-left";  //right of 3 column
    var l2col_class = document.contains(document.forms["cids"]) ? "m4" : "l3 s4";
    var r2col_class = document.contains(document.forms["cids"]) ? "m8" : "l3 s4";
    if(document.contains(document.getElementById('OEM_select'))){  //If an OEM has been selected    
            var e = document.getElementById("OEM_select");
            OEM_choice = e.options[e.selectedIndex].value;
            
            if(document.contains(document.getElementById('BOP_select')) && OEM_choice!=="SELECT"){  //If a BOP has been selected.  Nested inside the OEM selection b/c a BOP can only be selected if an OEM is selected first.
                var f = document.getElementById("BOP_select");									
                BOP_choice = f.options[f.selectedIndex].value;
            }
    }
    if (BOPchoice[0].checked){  //The user has chosen to "Select" a BOP from the database.
        
        //construct the OEM select input box
        //if the input box is being used on the client input data sheet, there is no need to call display_results() or pipe_fields()
        var OEMselect_scripts = document.contains(document.forms["cids"]) ? "BOP_fields()" : "pipe_fields(); BOP_fields(); display_results();";
        var OEMselect = "<select name=\"OEM_select\" id=\"OEM_select\" onchange=\""+OEMselect_scripts+"\" class=\"w3-select\"><option value=\"SELECT\">SELECT</option>";  //The OEM dropdown will call this function and generate the model dropdown
                  
        var BOPselect = "";  
        var BOPproperties = ""; //placeholder for closing ratio, closing area, tail rod area;

        for (var count = 0; count < OEM.length; count++){
            if (OEM[count]['id']===OEM_choice){  // adds the selected value to the option if it was previously selected
                var OEM_selected = "selected";
            }
            else{
                var OEM_selected = "";
           }
           OEMselect += "<option value=\""+OEM[count]['id']+"\" "+OEM_selected+">"+OEM[count]['OEM']+"</option>";
        }       
        OEMselect +="</select>";
        
        //construct the model select input box based on OEM selection only if an Manufacture was selected.
        if(check_value_isNumber(OEM_choice,0,false)){//document.contains(document.getElementById('OEM_select'))){ //IF an OEM is selected and is not "SELECT"           
            var first_BOP = true; 
            var BOPselect_scripts = document.contains(document.forms["cids"]) ? "BOP_fields()" : "BOP_fields(); display_results();"; 
            

            BOPselect += "<div class=\"w3-col "+l2col_class+"\">Model:</div><div class=\"w3-col "+r2col_class+"\"><select name=\"BOP_select\" id=\"BOP_select\" class=\"w3-select\" onchange=\""+BOPselect_scripts+"\">";
            //document.getElementById("test").innerHTML = l3col_class;
            for (var BOP_row = 0; BOP_row < BOP.length; BOP_row++){
                if (BOP[BOP_row]['OEM_id']===OEM_choice){
                    if((BOP[BOP_row]['BOP_id']===BOP_choice) || (BOP[BOP_row]['BOP_id']!==BOP_choice && first_BOP)){  // adds selected value to the option if it was previously selected.
                        var BOP_selected = "selected";
                        
                        //create data for (the selected one) or (if none selected and it's the first row)
                        BOPproperties = "<div class=\"w3-row\"><div class=\"w3-col "+l3col_class+"\">Closing Area</div><div class=\"w3-col "+m3col_class+"\"><input type=\"text\" class=\"w3-input w3-padding-0\" name=\"closingArea\" id=\"bop_closingarea\" onkeyup=\"display_results()\" value=\""+BOP[BOP_row]['BOP_closingarea']+"\"/></div><div class=\"w3-col "+r3col_class+"\">in<sup>2</sup></div></div><div class=\"w3-row\"><div class=\"w3-col "+l3col_class+"\">Closing Ratio</div><div class=\"w3-col "+m3col_class+"\"><input type=\"text\" name=\"bop_closingratio\" id=\"bop_closingratio\" onkeyup=\"display_results()\" class=\"w3-input w3-padding-0\" value=\""+BOP[BOP_row]['BOP_closingratio']+"\"/></div></div><div class=\"w3-row\"><div class=\"w3-col "+l3col_class+"\">Tailrod Area</div><div class=\"w3-col "+m3col_class+"\"><input type=\"text\" name=\"TailrodArea\" id=\"bop_trarea\" onkeyup=\"display_results()\" value=\""+BOP[BOP_row]['BOP_tailrodarea']+"\" class=\"w3-input w3-padding-0\"/></div><div class=\"w3-col "+r3col_class+"\">in<sup>2</sup></div></div>";
                        first_BOP = false;  //no longer the first BOP listed.
                    }
                    else{
                        var BOP_selected = "";
                    }
                    BOPselect += "<option value=\""+BOP[BOP_row]['BOP_id']+"\" "+BOP_selected+">"+BOP[BOP_row]['BOP_model']+"</option>";  //UpdATE NEEDED maintain selection.
                }
            }
            BOPselect +="</select></div>";
        }   
    }
    
    //Show the User Specified dialog or the drop-down selections based on "Select"/"Specify" radio menu
    if (BOPchoice[1].checked) {
        divobj.innerHTML = "<div class=\"w3-row\"><div class=\"w3-col " + l3col_class + "\">Closing Area</div><div class=\"w3-col " + m3col_class + "\"><input type=\"text\" name=\"closingArea\" id=\"bop_closingarea\" onkeyup=\"display_results()\" class=\"w3-input w3-padding-0\"/></div><div class=\"w3-col " + r3col_class + "\">in<sup>2</sup></div></div><div class=\"w3-row\"><div class=\"w3-col "+l3col_class+"\">Closing Ratio</div><div class=\"w3-col " + m3col_class + "\"><input type=\"text\" name=\"bop_closingratio\" id=\"bop_closingratio\" onkeyup=\"display_results()\" class=\"w3-input w3-padding-0\"/></div></div><div class=\"w3-row\"><div class=\"w3-col "+l3col_class+"\">Tailrod Area</div><div class=\"w3-col "+m3col_class+"\"><input type=\"text\" name=\"TailrodArea\" id=\"bop_trarea\" onkeyup=\"display_results()\" class=\"w3-input w3-padding-0\"/></div><div class=\"w3-col "+r3col_class+"\">in<sup>2</sup></div></div>";
    } 
    else{
        divobj.innerHTML = "<div class=\"w3-row\"><div class=\"w3-col " + l2col_class + "\">Manufacturer</div><div class=\"w3-col " + r2col_class + "\">" + OEMselect + "</div></div><div class=\"w3-row\">"+ BOPselect +"</div>"+ BOPproperties;      
    } 
}
</script>
