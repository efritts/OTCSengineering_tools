<?php

/*pipe weight.php
 * 
 * Gets the OD & ID of pipe, then determines the pipe weight for use in 
 * Cameron EB702 D.
 * 
 * INPUT is appended to the address as a $_GET variable
 * ?od=6.625&wall=.330&minYS=135
 * 
 * First determine if the pipe is listed in API 5DP.
 * if so get the nomial weight from the pipe
 * 
 * If the pipe is not in API 5DP, calculate the pipe weight given the OD & ID.
 * 
 * 
 * TODO: Add weights for casing & tubing from API SPEC 5CT
 */

 include "config.php";
 include "functions.php";
 
 $OD = (!IsNullOrEmptyString($_GET["od"])?$_GET["od"]:"");
 $Wall = (!IsNullOrEmptyString($_GET["wall"])?$_GET["wall"]:"");
 $YS = (!IsNullOrEmptyString($_GET["minYS"])?$_GET["minYS"]:"");
 $type = (!IsNullOrEmptyString($_GET["type"])?$_GET["type"]:"pipe");  //pipe, casing, or tubing
 $endType = (!IsNullOrEmptyString($_GET["endType"])?$_GET["endType"]:"NU");
 $ppf = "";
 
if(!IsNullOrEmptyString($OD) && !IsNullOrEmptyString($Wall))
{
	 //Setup Connection with MySQL database
	$conn = connect_db();
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	//Check pipe table for result in API 5DP & 5CT
	if($type == "tubing"){
            $query= "SELECT * FROM tubulars WHERE od=".$OD." && type='".$type."' && wall=".$Wall." && endType='".$endType."';";
        }else{ //casing or pipe
            $query= "SELECT * FROM tubulars WHERE od=".$OD." && type='".$type."' && wall=".$Wall.";";//for tubulars
	}
	try {
            $stmt_pipe = $conn->prepare($query);   
            $stmt_pipe->execute();
                $count = $stmt_pipe->rowCount();
                //if rows exists, assign $ppf
                if($count>0){
                        while( $row = $stmt_pipe->fetch(PDO::FETCH_ASSOC)){
                                $ppf_statement="Found in API 5";  
                                $ppf=$row['weight'];
                        }
                }
                else{//Else calculate the ppf
                    $ppf_statement="Not found. Calculated.";
                    $ppf=round((pow($OD,2) - pow($OD-2*$Wall, 2))*2.92,2);
                }
        }
        catch(PDOException $pw) {
            echo "Error: " . $pw->getMessage();
                die();
        }
        $conn = null;
        
}
else{// Nothing to return
 echo "";
 //$ppf="not enough info";  //comment out after testing.
}

//
//echo $ppf_statement."<br />tubing type is {$type}<br />Query is {$query}<br />Pipe weight is ".$ppf; //comment out after testing
echo $ppf;
?>
