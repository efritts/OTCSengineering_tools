<?php
/*  cids_emailer.php
 * add new input sheet to the database
 * email completed form data to individuals specified in a config file
 * 
 */
 
 //Configure
 $email_on = true;  //production value: true

//Get all the form data
$email = htmlspecialchars($_POST['fm_email']);

if(!empty($email)){
	echo $email;
}
else{
	echo "the form was empty";
}

//create database entry


//generate the email.
$to_email = "david.hanks@otc-solutions.com";
$subject = "CIDS - ".$_POST['fm_rig']." @ ".$_POST['fm_well_name'];
$headers = "From: david.hanks@otc-solutions.com \r\n";
$headers .= "Reply-To: technical@otc-solutions.com \r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$email_message = '<html><body>';
$email_message .= "<h1>CIDS from {$_POST['fm_comp']}</h1><p>".$_POST['fm_first']." ".$_POST['fm_last']." submitted a Client Input Data Sheet on ".date('l, F jS, Y h:i:s A')."</p>";
$email_message .= "</body></html>";


if($email_on){
	//mail everyone
	if(mail($to_email,$subject,$email_message, $headers)){
		echo "SENT";
	}
	else{
		echo "MAIL FAIL";
	}
}


?>