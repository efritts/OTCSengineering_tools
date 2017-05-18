<?php
/* C3.php
 * 
 * Using a table in mySQL data base this file looks up C3 for a Cameron BOP.
 * 
 * Inputs:
 * 
 *  The following should be included in the call for this page 
 *  Ex C3.php?bop_id=2&pipe_grade=E75&pipe_od=6.625
 * bop_id -> the id number of a bop table in the database
 * pipe_grade -> E75, L80, X95, G105, P110, Q125, S135, Z140, V150
 * pipe_od -> outside diameter of the pipe.
 * 
 * The following terms are evaluated based on the inputs given.
 * $bop_group:
 * 	GROUP A - U BOP, SB, LB, SBT, LBT/UII SB, LB/EVO SB, TL SB/Ramlock
 *	GROUP B - TL/EVO SS 
 * 
 * $ram_type:
 * 	if bop_group="A" is selected the ram type is required to determine C3
 * 	TYPE A - SBR
 * 	TYPE B - DS, ISR, DSI, DVS, CDVS, CDVS II
 * 
 * $pipe_od:
 * 	if bop_group="B" then tubular size is required to determien C3
 * 	-9.625 TUBULAR UP TO 9.625 OD
 * 	+9.625 TUBULAR GREATER THAN 9.625 OD
 * 
 * $pipe_grade
 * 
 * OUTPUT:
 * the file echos the value of C3 based on a database table.
 * 
 * EX: ".28"
 * 
 */
 
 include "functions.php";
 

 $bop_id = (!IsNullOrEmptyString($_GET["bop_id"])?$_GET["bop_id"]:"");
 $pipe_grade = (!IsNullOrEmptyString($_GET["pipe_grade"])?$_GET["pipe_grade"]:"");
 $pipe_od = (!IsNullOrEmptyString($_GET["pipe_od"])?$_GET["pipe_od"]:"");
//UPDATE NEEDED.  Check that pipe_grade is an existing pipe grade and in the correct format.


if(!IsNullOrEmptyString($bop_id) && !IsNullOrEmptyString($pipe_grade) && !IsNullOrEmptyString($pipe_od)){
	 
	//Setup Connection with MySQL database
	$conn_C3 = connect_db();
	$conn_C3->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	//Using the $bop_id, determine $bop_group & $ram_type
	try {
	    $stmt_bopinfo = $conn_C3->prepare("SELECT id, BOP_model, BOP_Operatortype, BOP_ramtype FROM compliance.BOP WHERE id=".$bop_id); 
	    $stmt_bopinfo->execute();
		$count = $stmt_bopinfo->rowCount();
		//if rows exists, assign $bop_type & ram_type
		if($count>0){
			while( $row = $stmt_bopinfo->fetch(PDO::FETCH_ASSOC)){    
				$bop_type=$row['BOP_Operatortype'];
				$ram_type=$row['BOP_ramtype'];
				//echo $bop_type." ".$ram_type."<br />"; //testing
			}
		}
		
		//Determine BOP Group A or B
		if($bop_type=="TL/EVO SS"){$bop_group="B";}
		else{$bop_group="A";}
		
		//if Group A
		if($bop_group=="A"){
			//Determine BOP Ram Group A or B
			if($ram_type=="SBR"){$ram_group="A";}
			else{$ram_group="B";}
			$stmt_c3Query = $conn_C3->prepare("SELECT idCameronC3, C3 FROM compliance.CameronC3 WHERE BOP_group='A' AND Ram_type='$ram_group' AND pipe_grade='$pipe_grade'");
			//echo "bop_group=$bop_group ram_group=$ram_group<br />";//testing
		}
		else{
		//if BOP group B
			if($pipe_od>9.625)
			{
				$stmt_c3Query = $conn_C3->prepare("SELECT idCameronC3, C3 FROM compliance.CameronC3 WHERE BOP_group='B' AND Tubular_size='+9.625' AND pipe_grade='".$pipe_grade."'");
			}
			else {
				$stmt_c3Query = $conn_C3->prepare("SELECT idCameronC3, C3 FROM compliance.CameronC3 WHERE BOP_group='B' AND Tubular_size='-9.625' AND pipe_grade='$pipe_grade'");
			}
			//echo "bop_group=$bop_group pipe_od=$pipe_od<br />";//testing
		}
		
		//Search for C3
		$stmt_c3Query->execute();
		$count_c3 = $stmt_c3Query->rowCount();
		if($count_c3>0){
			while ($row_c3 = $stmt_c3Query->fetch(PDO::FETCH_ASSOC)){
				$c3=$row_c3['C3'];
			}
		}
		else{
			$error="No rows found <br />";
		}	
	}
	catch(PDOException $e) {
	    echo "Error: " . $e->getMessage();
	}
	 
/*
 * Info when testing
	 echo "bop_type = $bop_type <br />";
	 echo "bop_group = $bop_group <br />";
	 echo "ram_type = $ram_type <br />";
	 echo "ram_group = $ram_group <br />";
	 echo "pipe_od = $pipe_od<br />";
	 echo "pipe_grade = $pipe_grade <br />";
	 echo "<b>C3 = $c3";
	 echo $error;
 * 
 */
 echo $c3;
 $conn_C3 = null;
}
/* This was used for troubleshooting.  No longer needed.
else{
	echo "Something went rong. re-heely rong. <br />";
	//Not enough info available
}
 */

