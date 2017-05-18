<?php
//form functions
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
// define variables and set to empty values
$Accum_qty = $Vaccum_gas = $Accum_MaxPress = $Accum_gastype = $Precharge = $Precharge_lowTemp = $Precharge_highTemp = $AirTemp_max = $Pmud = $mawhp = $Psw = $Pcf = $Psys = "";
$Accum_qtyErr = $Vaccum_gasErr = $Accum_MaxPressErr = $Accum_gastypeErr = $PrechargeErr = $Precharge_lowTempErr = $Precharge_highTempErr = $AirTemp_maxErr = $PmudErr = $mawhpErr = $PswErr = $PcfErr = $PsysErr = "";

//determine if the field is empty.  Generate an error or record the value
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["Accum_qty"])){
        $Accum_qtyErr = "Required";
    }
    else {
        $Accum_qty = test_input($_POST["Accum_qty"]);
    }
    if(empty($_POST["Vaccum_gas"])){$Vaccum_gasErr="Required";}
    else{$Vaccum_gas = test_input($_POST["Vaccum_gas"]);}
   
    if(empty($_POST["Accum_MaxPress"])){$Accum_MaxPressErr="Required";}
    else{$Accum_MaxPress = test_input($_POST["Accum_MaxPress"]);}
  
    if(empty($_POST["Accum_gastype"])){$Accum_gastypeErr="Required";}
    else{$Accum_gastype = test_input($_POST["Accum_gastype"]);}
  
    if(empty($_POST["Precharge"])){$PrechargeErr="Required";}
    else{$Precharge = test_input($_POST["Precharge"]);}
  
    if(empty($_POST["Precharge_lowTemp"])){$Precharge_lowTempErr="Required";}
    else{$Precharge_lowTemp = test_input($_POST["Precharge_lowTemp"]);}
  
    if(empty($_POST["Precharge_highTemp"])){$Precharge_highTempErr="Required";}
    else{$Precharge_highTemp = test_input($_POST["Precharge_highTemp"]);}
  
    if(empty($_POST["AirTemp_max"])){$AirTemp_maxErr="Required";}
    else{$AirTemp_max = test_input($_POST["AirTemp_max"]);}
  
    if(empty($_POST["Pmud"])){$PmudErr="Required";}
    else{$Pmud = test_input($_POST["Pmud"]);}
  
    if(empty($_POST["mawhp"])){$mawhpErr="Required";}
    else{$mawhp = test_input($_POST["mawhp"]);}
  
    if(empty($_POST["Psw"])){$PswErr="Required";}
    else{$Psw = test_input($_POST["Psw"]);}
  
    if(empty($_POST["Pcf"])){$PcfErr="Required";}
    else{$Pcf = test_input($_POST["Pcf"]);}
  
    if(empty($_POST["Psys"])){$PsysErr="Required";}
    else{$Psys = test_input($_POST["Psys"]);}
    
    //Determine how many functions exist
    $Total_FunctionRows = test_input($_POST["TotF_rows"]);
    $Function = array();
    
    //Loop though functions
    //Set PHP var to empty
    for($f=1; $f<=$Total_FunctionRows; $f++){
        $Function[$f]['name']="";
        $Function[$f]['gallons']="";
        $Function[$f]['MOP']="";
        $Function[$f]["error"]="";
    }
    
    for($f=1; $f<=$Total_FunctionRows; $f++){
        if($f=1 && (empty($_POST[$f."-Fname"])||empty($_POST[$f."-Fvol"])||empty($_POST[$f."-FMOP"]))){
            //One function is required
            $Function[$f]["error"]="*";
        }
        
        $Function[$f]['name']=test_input($_POST[$f."-Fname"]);  //UPDATE.  Test not empty
        $Function[$f]['gallons']=test_input($_POST[$f."-Fvol"]);  //UPDATE.  TEST numeric
        $Function[$f]['MOP']=test_input($_POST[$f."-FMOP"]);  //UPDATE.  TEST numeric
    }
    
    //If no errors
        //Calculate stuff
        //Display results  
}



?>
		
			<div class="w3-row">
				<div class="w3-col w3-margin">
					<h1>Accumulator sizing verification.</h1>
					<p>This program takes the given data and determines if the accumulator capacity will meet the functional volume requirement.</p>
				</div>
			</div>
			<div class="w3-row">
			<div class="w3-col l8 m8">
							
				<div id="form" class="w3-container">
				<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?page=calcs&sub=atest"; ?>" id="accumdata">	
					<h3>Accumulator</h3>
					<table style="width:auto">
						<tr><td>quantity</td><td><input type="text" name="Accum_qty" style="text-align: right;"/></td><td></td><td><span class="error"><?php echo $Accum_qtyErr; ?></span></td></tr>
						<tr><td>gas volume</td><td><input type="text" name="Vaccum_gas"/></td><td>gal</td><td><span class="error"><?php echo $Vaccum_gasErr; ?></span></td></tr>
						<tr><td>pressure rating</td><td><input type="text" name="Accum_MaxPress"/></td><td>psig</td><td><span class="error"><?php echo $Accum_MaxPressErr; ?></span></td></tr>
						<tr><td>gas type</td><td><input type="text" name="Accum_gastype" value="Nitrogen"/></td><td></td><td><span class="error"><?php echo $Accum_gastypeErr; ?></span></td></tr>
						<tr><td>target precharge</td><td><input type="text" name="Precharge"/></td><td>psig</td><td><span class="error"><?php echo $PrechargeErr; ?></span></td></tr>
						<tr><td>Precharge temperature (low)</td><td><input type="text" name="Precharge_lowTemp"/></td><td>F</td><td><span class="error"><?php echo $Precharge_lowTempErr; ?></span></td></tr>
						<tr><td>Precharge temperature (high)</td><td><input type="text" name="Precharge_highTemp"/></td><td>F</td><td><span class="error"><?php echo $Precharge_highTempErr; ?></span></td></tr>
					</table>
						
					<h3>Well Conditions</h3>
					<table style="width:auto">
						<tr><td>Max Ambient Air temperature</td><td><input type="text" name="AirTemp_max" value="120"/></td><td>F</td><td><span class="error"><?php echo $AirTemp_maxErr; ?></span></td></tr>
						<tr><td>MUD pressure @ BOP</td><td><input type="text" name="Pmud"/></td><td>psig</td><td><span class="error"><?php echo $PmudErr; ?></span></td></tr>
						<tr><td>MAWHP</td><td><input type="text" name="mawhp"/></td><td>psig</td><td><span class="error"><?php echo $mawhpErr; ?></span></td></tr>
						<tr><td>Seawater head @ BOP</td><td><input type="text" name="Psw"/></td><td>psig</td><td><span class="error"><?php echo $PswErr; ?></span></td></tr>
						<tr><td>Control fluid head @ BOP</td><td><input type="text" name="Pcf"/></td><td>psig</td><td><span class="error"><?php echo $PcfErr; ?></span></td></tr>
						<tr><td>System Pressure <span title="Pressure at which the HPU operates"><span class="fa fa-info-circle w3-small"></span></span></td><td><input type="text" name="Psys"/></td><td>psig</td><td><span class="error"><?php echo $PsysErr; ?></span></td></tr>
					</table>							
					
					<h3>Functions</h3>
					<table style="width:auto" id="functions">
						<tr><th>Function Name</th><th>gallons</th><th>MOP @ depth (psia)</th></tr>
						<tr><td><input type="text" id="1-Fname"/></td><td><input type="text" id="1-Fvol"></td><td><input type="text" id="1-FMOP"></td></tr>
					</table>
					<input type="hidden" value="1" id="TotF_rows"/>
					<br />
					<!-- REMOVE <button onclick="add_function()" type="button"><span class="fa fa-plus w3-small"></span></button> -->
					<span onclick="add_function()" class="fa fa-plus w3-small"></span>
					<span onclick="rm_function()" class="fa fa-minus w3-small"></span>
					<hr />
					
					<!-- REMOVE <button onclick="accum_check()" type="button">Check</button>-->
					<input type="submit" name="submit" value="PHP Submit">
					<div id="demo"></div>
				
				</form>
				<hr />
				<div id="test"></div>
			</div>
			</div>	
			</div>
			
			