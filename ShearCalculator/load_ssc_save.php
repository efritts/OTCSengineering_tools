<?php
/* load_ssc_save.php
 * 
 * When $_GET["save"] is set and it's numeric, then run some javascript to fill out the form.
 * 
 * 
 */

 $save_id = $_GET["save"];
 
 try{
 	//connect to the db
 	$compliance_connection = connect_db();
 	$compliance_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	//select the saved row
	$sql = "SELECT * FROM shear_saves WHERE id_shear_saves = $save_id";
	$select_stmt = $compliance_connection->prepare($sql);
	$select_stmt->execute();
	
	//create an array that can be used to update the form.
	while( $row = $select_stmt->fetch(PDO::FETCH_ASSOC)){
        $save[]=$row;
    }
 }
 catch(PDOException $e){
 	echo $e->getMessage()."\n\n ATTEMPTED QUERY: \n".$sql;
 }
?>
<script>
	
	function load_saved_shear(){
		// create an array of the saved entry
		var save = <?php echo json_encode($save, JSON_PRETTY_PRINT); /*Ex. save[0]['pipechoice']="specify"*/  ?>;
		
		//take only the first row
		//var new_save = save.pop();
		//save.toString()
		//document.getElementById("test").innerHTML = save.toString();
		
		//set the pipechoice
		setCheckedValue(document.forms['shear_inputs'].elements['Pipe_select'], save[0]['pipechoice']);
		if(save[0]['pipechoice']=='specify'){
			//set pipe grade
			document.getElementById("pipe_grade").selectedIndex = save[0]['gr_index'];
			
			//set min yield
			document.getElementById("pipe_minYS").value = save[0]['minYS'] ? save[0]['minYS'] : "";  
			//set yield strength
			document.getElementById("pipe_ys").value = save[0]['ys'] ? save[0]['ys'] : ""; 
			//set Ultimate Strength
			document.getElementById("pipe_uts").value = save[0]['uts'] ? save[0]['uts'] : "" ;
			//set % Elongation
			document.getElementById("pipe_elong").value = save[0]['elong'] ? save[0]['elong'] : "" ;
			//set OD
			document.getElementById("pipe_od").value = save[0]['od'] ? save[0]['od'] : "" ;
			//set wall thickness
			document.getElementById("pipe_wall").value = save[0]['wall'] ? save[0]['wall'] : "" ;
			//calculate area.  Area is calculated when display_results() is called.
		}
		else{
			//pipe was selected from available pipe
			//Not yet available as of 4/4/2017
		}
		
		//display the Well parameters
		document.getElementById("mawhp").value = save[0]['MAWHP'] ? save[0]['MAWHP'] : "";
		document.getElementById("h_riser").value = save[0]['h_riser'] ? save[0]['h_riser'] : "";
		document.getElementById("h_sw").value = save[0]['h_sw'] ? save[0]['h_sw'] : "";
		document.getElementById("h_hpu").value = save[0]['h_hpu'] ? save[0]['h_hpu'] : "";
		document.getElementById("h_bop").value = save[0]['h_bop'] ? save[0]['h_bop'] : "";
		document.getElementById("mudweight").value = save[0]['mudweight'] ? save[0]['mudweight'] : "";
		document.getElementById("g_cf").value = save[0]['grad_cf'] ? save[0]['grad_cf'] : "";
		document.getElementById("g_sw").value = save[0]['grad_sw'] ? save[0]['grad_sw'] : "";
		
		//display the BOP parameters
		setCheckedValue(document.forms['shear_inputs'].elements['BOP_select'], save[0]['bop_choice']);
		BOP_fields();
		//if BOP select is "select", then manufacturer and model should be set
		if(save[0]['bop_choice'] =="select"){
			document.getElementById("OEM_select").value = save[0]['OEM'];
			pipe_fields(); BOP_fields();  //add C1 if cameron, show the model field
			document.getElementById("BOP_select").value = save[0]['BOPmodel'];
			BOP_fields();			
		}
		//if bop parameters were specified, then use them, otherwise use the parameters given by BOP_fields().  (this is really only relevant for a time when the three parameters weren't sent to the db))
		if(save[0]['bop_closingarea']){
			document.getElementById("bop_closingarea").value = save[0]['bop_closingarea'];
		}
		if(save[0]['bop_closingratio']){
			document.getElementById("bop_closingratio").value = save[0]['bop_closingratio'];
		}
		if(save[0]['bop_trarea']){
			document.getElementById("bop_trarea").value = save[0]['bop_trarea'];	
		}
		/*
		document.getElementById("bop_closingarea").value = save[0]['bop_closingarea'] ? save[0]['bop_closingarea'] : ""; 
		document.getElementById("bop_closingratio").value = save[0]['bop_closingratio'] ? save[0]['bop_closingratio'] : ""; 
		document.getElementById("bop_trarea").value = save[0]['bop_trarea'] ? save[0]['bop_trarea'] : ""; 
		*/
		//Calculate the result
		display_results();
	}
	
</script>
