<?php

//get info from $post
$str_json = file_get_contents('php://input');
$cids= json_decode($str_json, true);

//setup the email 
$to = 'david.hanks@otc-solutions.com';

$subject = 'Client Input Data Sheet Submitted';

$headers = "From: cids.mailer@otc-solutions.com\r\n";
//$headers .= "Reply-To: ". strip_tags($_POST['req-email']) . "\r\n";
//$headers .= "CC: susan@example.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$message = "<html><body><h1>Client Input Sheet</h1><p>".$cids['name']." submitted an input sheet for the {$cids['rigName']}.  blah blah blah</p>";
$message .= "<p>$str_json</p>";
$message .="</body></html>";
//Send the mail
if(mail($to, $subject, $message, $headers)){
	echo"<script>console.log('mail sent successfully');</script>";
}else{
	echo"<script>console.log('mail send failed.');</script>";
}


?>