<?php
/*  cids_emailer.php
 * add new input sheet to the database
 * email completed form data to individuals specified in a config file
 * 
 */
 
//Configure
$email_on = TRUE;  //production value: true

//Get all the form data
$email = htmlspecialchars($_POST['fm_email']);

//create database entry

/**
 * The below uses PHPmailer.  More examples can be found in /vendor/phpmailer/phpmailer/examples
 * 
 */
if($email_on){
	require '../vendor/phpmailer/phpmailer/PHPMailerAutoload.php';

	//Create a new PHPMailer instance
	$mail = new PHPMailer;
	//Set who the message is to be sent from
	$mail->setFrom('forms@otc-solutions.com', 'OTC Forms');
	//Set an alternative reply-to address
	$mail->addReplyTo('technical@otc-soluctions.com', 'OTC-Solutions Technical');
	//Set who the message is to be sent to
	$mail->addAddress('david.hanks@otc-solutions.com', 'David Hanks');
	//Set the subject line
	$mail->Subject = "CIDS - ".$_POST['fm_rig']." @ ".$_POST['fm_well_name'];;
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	//$mail->msgHTML(file_get_contents('cids_email.html'), dirname(__FILE__));
	//generate the email content.
	$email_message = '<html><body>';
	$email_message .= "<h1>CIDS from {$_POST['fm_comp']}</h1><p>".$_POST['fm_first']." ".$_POST['fm_last']." submitted a Client Input Data Sheet on ".date('l, F jS, Y h:i:s A')."</p>";
	$email_message .= "<p>".json_encode($_POST)."</p>";
	$email_message .= "</body></html>";
	$mail->msgHTML($email_message);
	//Replace the plain text body with one created manually
	$mail->AltBody = 'This is a plain-text message body';
	//Attach an image file
	//$mail->addAttachment('images/phpmailer_mini.png');
	
	//send the message, check for errors
	if (!$mail->send()) {
	    echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
	    echo "Message sent!";
	}
}
else{ echo "MAILER DISABLED";}
?>