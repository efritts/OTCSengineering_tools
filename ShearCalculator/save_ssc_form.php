<?php
/* save_ssc_form.php
 * 
 * Receives a post variable of form fields.
 * Adds those fields to a table in the db
 * returns the id of the new row that was added.
 * 
 * The following $_POST variable may be available to this script
 * 		//$_POST sent to Call_ajax()
		// pipechoice => string "specify"|"select" 
		// gr_index = int index number of the pipe grade
		// minYS => int(6)
		// ys => int(6)
		// uts => int(6)
		// elong => decimal (4,2)
		// od => decimal (4,2)
		// wall => decimal (6,4)
		// MAWHP => int(6)
		// h_riser, h_sw, h_hpu, h_bop = decimal (7,2)
		// mudweight => decimal(4,2)
		// bop_choice => string "specify"|"select"
		// OEM => int(3)
		// BOPmodel => int(4)
 		// closing area => decimal (6,2)
 		// closing ratio => decimal (6,2)
 * 		// tr area => decimal (6,2)
 * 
 */
 include('../include/config.php');
 
 //Setup which fields and data are to be inserted to mySQL table
 $fields = "";
 $data = "";
 if(isset($_POST["pipechoice"])){$fields .= "pipechoice," ; $data .= "'".$_POST["pipechoice"]."',";}
 if(isset($_POST["gr_index"])){$fields .= "gr_index," ; $data .= $_POST["gr_index"].",";}
 if(isset($_POST["minYS"])){$fields .= "minYS," ; $data .= $_POST["minYS"].",";}
 if(isset($_POST["ys"])){$fields .= "ys," ; $data .= $_POST["ys"].",";}
 if(isset($_POST["uts"])){$fields .= "uts," ; $data .= $_POST["uts"].",";}
 if(isset($_POST["elong"])){$fields .= "elong," ; $data .= $_POST["elong"].",";}
 if(isset($_POST["od"])){$fields .= "od," ; $data .= $_POST["od"].",";}
 if(isset($_POST["wall"])){$fields .= "wall," ; $data .= $_POST["wall"].",";}
 if(isset($_POST["MAWHP"])){$fields .= "MAWHP," ; $data .= $_POST["MAWHP"].",";}
 if(isset($_POST["h_riser"])){$fields .= "h_riser," ; $data .= $_POST["h_riser"].",";}
 if(isset($_POST["h_sw"])){$fields .= "h_sw," ; $data .= $_POST["h_sw"].",";}
 if(isset($_POST["h_hpu"])){$fields .= "h_hpu," ; $data .= $_POST["h_hpu"].",";} 
 if(isset($_POST["h_bop"])){$fields .= "h_bop," ; $data .= $_POST["h_bop"].",";}
 if(isset($_POST["mudweight"])){$fields .= "mudweight," ; $data .= $_POST["mudweight"].",";}
 if(isset($_POST["bop_choice"])){$fields .= "bop_choice," ; $data .= "'".$_POST["bop_choice"]."',";}
 if(isset($_POST["OEM"])){$fields .= "OEM," ; $data .= $_POST["OEM"].",";}
 if(isset($_POST["BOPmodel"])){$fields .= "BOPmodel," ; $data .= $_POST["BOPmodel"].",";}
 if(isset($_POST["bop_closingarea"])){$fields .= "bop_closingarea," ; $data .= $_POST["bop_closingarea"].",";}
 if(isset($_POST["bop_closingratio"])){$fields .= "bop_closingratio," ; $data .= $_POST["bop_closingratio"].",";}
 if(isset($_POST["bop_trarea"])){$fields .= "bop_trarea," ; $data .= $_POST["bop_trarea"].",";}
 if(isset($_POST["grad_cf"])){$fields .= "grad_cf," ; $data .= $_POST["grad_cf"].",";}
 if(isset($_POST["grad_sw"])){$fields .= "grad_sw," ; $data .= $_POST["grad_sw"].",";} 
 
 //remove the , from the end of $fields and $data
 $fields = rtrim($fields,",");
 $data = rtrim($data,",");

try{
 	//connect to the db
 	$compliance_connection = connect_db();
 	$compliance_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	//insert a row
	$sql = "INSERT INTO shear_saves ($fields) VALUES ($data)";
	$insert_stmt = $compliance_connection->prepare($sql);
	$insert_stmt->execute();
	
	//get the id of the row inserted
	$last_id = $compliance_connection->lastInsertId();
 	
 	//echo the row id
 	echo $last_id;
	
	$compliance_connection = null;
 }
 catch(PDOException $e){
 	echo $e->getMessage()."\n\n ATTEMPTED QUERY: \n".$sql;
 }
//echo "fields: ".$fields."\n data: ".$data; //for testing
 ?>
