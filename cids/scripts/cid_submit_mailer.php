<?php
/*
 * TODO: get appropriate fields for casing, pipe, tube, wireline
 * casing, pipe: grade, od, wall, nominal weight
 * tube: grade, OD, wall
 * wireline: size, breaking strength.
 * 
 * TODO: Create a table for tubular type: casing, pipe, tube, wireline.
 */
//get info from $post
$str_json = file_get_contents('php://input');

//$str_json = <<<SUM
//{"name":"David","email":"david@hanks.com","email2":"david@hanks.com","rigName":"ba","bopLocation":"Subsea","wellName":"Sum Well","wellLocation":"Over thar","MAWHP":"11300","depth":"1023","mudWeight":"12.3","BOP_Stack":[{"BOP_type":"Annular","BOP_OEM":"GE","BOP_description":"18-3/4"},{"BOP_type":"Ram","BOP_OEM":"Cameron","BOP_description":"Type U"},{"BOP_type":"Gate","BOP_OEM":"WOM","BOP_description":"15M BX"}],"tubulars":[{"lineType":"pipe","strengthYield":"135","outsideDiameter":"5"}]}
//SUM;
$cids= json_decode($str_json, true);

//setup the email 
$to = 'david.hanks@otc-solutions.com';

$subject = 'Client Input Data Sheet Submitted';

$headers = "From: cids.mailer@otc-solutions.com\r\n";
//$headers .= "Reply-To: ". strip_tags($_POST['req-email']) . "\r\n";
//$headers .= "CC: susan@example.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$message_BOPs ='';
foreach ($cids['BOP_Stack'] as $component){
	$message_BOPs .= <<<MSG
	<tr>
	    <td style="width:55%">{$component['BOP_type']}</td>
	    <td style="width:15%">{$component['BOP_OEM']}</td>
	    <td style="width:30%">{$component['BOP_description']}</td>
	</tr>
MSG;
}

$message = <<<FORM
<!DOCTYPE html>
<html lang='eng'>
<head>
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {background-color: #f2f2f2}            
        .bg-margin-color{
            background-color: #b3ccff;
        }
    </style>
</head>
	<body style="padding: 0px; margin: 0px;">
	    <table class="bg-margin-color" >
	    	<tr>
			<td style="width: 10%;" id="left-margin"></td>
			<td style="background-color: #fff">
				<h1>Client Input Data Sheet</h1><p>{$cids['name']} submitted an input sheet for the {$cids['rigName']}.</p>
				<h2>Customer</h2>
				<table>
	                <tr>
	                    <td style="width:70%">Name</td>
	                    <td style="width:30%">{$cids['name']}</td>
	                </tr>
	                <tr>
	                    <td>Company</td>
	                    <td>{$cids['company']}</td>
	                </tr>
	                <tr>
	                    <td>email</td>
	                    <td>{$cids['email']}</td>
	                </tr>
	                <tr>
	                    <td>phone</td>
	                    <td>{$cids['phone']}</td>
	                </tr>
	            </table>
	            <h2>Requested Services</h2>
	            <table>
	                <tr>
	                    <td style="width:70%">Verification of Compliance</td>
	                    <td style="width:30%"><i class="fa fa-check-square-o" aria-hidden="true"></i></td>
	                </tr>
	                <tr>
	                    <td>Shear Verificaiton</td>
	                    <td><i class="fa fa-check-square-o" aria-hidden="true"></i></td>
	                </tr>
	                <tr>
	                    <td>Accumulator Calculations</td>
	                    <td>NO</td>
	                </tr>
	                <tr>
	                    <td>Accumulator Review</td>
	                    <td><i class="fa fa-check-square-o" aria-hidden="true"></i></td>
	                </tr>
	            </table>
	            
	            <h2>Rig</h2>
	            <table>
	                <tr>
	                    <td style="width:70%">Rig Name</td>
	                    <td>{$cids["rigName"]}</td>
	                </tr>
	                <tr>
	                    <td>Rig Owner</td>
	                    <td>{$cids['rigOwner']}</td>
	                </tr>
	                <tr>
	                    <td>Rig Contact - Name</td>
	                    <td>Big John</td>
	                </tr>
	                <tr>
	                    <td>Rig Contact - Email</td>
	                    <td>bigbigjohn@bigrig.com</td>
	                </tr>
	                <tr>
	                    <td>Rig Contact - Number</td>
	                    <td>234-567-8901</td>
	                </tr>
	                <tr>
	                    <td>BOP location</td>
	                    <td>{$cids['bopLocation']}</td>
	                </tr>
	                <tr>
	                    <td>Riser Elevation</td>
	                    <td>{$cids['heightRiser']}</td>
	                </tr>
	                <tr>
	                    <td>HPU Elevation</td>
	                    <td>{$cids['heightHPU']}</td>
	                </tr>
	            </table>
	            <h2>BOP stackup</h2>
	            <table>
	            	$message_BOPs
	                <!--<tr>
	                    <td style="width:55%">Annular</td>
	                    <td style="width:15%">GE</td>
	                    <td style="width:30%">18-3/4" GX 18-10M</td>
	                </tr>-->
	            </table>
	            <h2>Well</h2>
	            <table>
	                <tr>
	                    <td style="width:70%">Well Name</td>
	                    <td style="width:30%">{$cids['wellName']}</td>
	                </tr>
	                <tr>
	                    <td>Well Location</td>
	                    <td>{$cids['wellLocation']}</td>
	                </tr>
	
	                <tr>
	                    <td>Maximum Anticipated Wellbore Temperature</td>
	                    <td>210 deg F</td>
	                </tr>
	                <tr>
	                    <td>Water depth</td>
	                    <td>{$cids['depth']}</td>
	                </tr>
	                <tr>
	                    <td>BOP Elevation above sea floor</td>
	                    <td>40 ft</td>
	                </tr>
	                <tr>
	                    <td>Maximum Anticipated Wellhead Pressure</td>
	                    <td>{$cids['MAWHP']}</td>
	                </tr>
	                <tr>
	                    <td>Maximum Drilling Fluid Weight</td>
	                    <td>{$cids['mudWeight']}</td>
	                </tr>                    
	            </table>
	            <h2>Tubulars</h2>
	            <table >
	                <thead><tr>
	                    <th style="width: 40%">Grade</th>
	                    <th style="width: 15%">OD</th>
	                    <th style="width: 15%">wall</th>
	                    <th style="width: 30%">Nominal Weight</th>
	                </tr></thead>
	                <tr>
	                    <td>S-135</td>
	                    <td>5</td>
	                    <td>.362</td>
	                    <td>19.5</td>
	                </tr>
	                <tr>
	                    <td>S-135</td>
	                    <td>3.5</td>
	                    <td>.2</td>
	                    <td>13.3</td>
	                </tr>
	            </table>
	            <h2>Comments</h2>
	            <p>{$cids['comments']}</p>
            <td style="width: 10%" id="right-margin"></td>  
		</tr>
		</table>
		<p>$str_json</p>
</body>
</html>
FORM;

//Send the mail
if(mail($to, $subject, $message, $headers)){
	echo"<script>console.log('mail sent successfully');</script>";
}else{
	echo"<script>console.log('mail send failed.');</script>";
}

?>