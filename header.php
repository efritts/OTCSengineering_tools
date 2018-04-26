<?php 
/* header.php
 * this file contains the <head> tag and the main navigation menu.
 * 
 * TODO:
 * Change where "class=active_page" is located.
 * include js based on page
 *
 * read post variable
 * Set $title_name
 * Set $active_page
 */

//change the title and highlighted menu item based on page selection 
include 'include/config.php';
$active_config= "style=\"border-bottom:3px solid #fff\"";
$loadscript = "";
$header_scripts ="";
$body_end_scripts = "";
switch ($_GET["page"]) {
	case "calcs":
		$title_name = "Basic_Calculator";
		$home_page_active = "";
		$basiccalcs_page_active = $active_config;
 		$shear_page_active = "";
 		$BOPindex_page_active ="";
 		$pipe_page_active = "";
 		$project_page_active = "";
		$forms_page_active = "";
		switch ($_GET["sub"]){
			case "ssc":
				$title_name = "Simple Shear Calculator";
				$loadscript .= "load_form_fields();";  //This will preload the BOP selection by default.	
				//TODO if a save is present then need to run load_save_shear()
				if(isset($_GET["save"]) && is_numeric($_GET["save"])){
					$loadscript .=" load_saved_shear();";
				}
				$header_scripts .= "<script src='include/shear.js''></script>";
				break;
			case "mtsc":
				$title_name = "Multi-Tube Shear Calculator";
				$loadscript .= "load_form_fields();";  //This will preload the BOP selection by default.	
				//TODO if a save is present then need to run load_save_shear()
				if(isset($_GET["save"]) && is_numeric($_GET["save"])){
					$loadscript .=" load_saved_shear();";
				}
				// DELETE - moved to end of body so globals could be declared - $header_scripts .= "<script src='include/shear2.js''></script>";
				$body_end_scripts .= "<script src='include/shear2.js''></script>";
				break;
			case "atest":
				$title_name = "Accumulator Sizing Verification";
				$header_scripts .= "<script src='include/accum.js''></script>";
				break;
		}
		break;
	case "pipe":
		$title_name = "Pipe Index";
		$home_page_active = "";
 		$shear_page_active = "";
 		$BOPindex_page_active ="";
 		$pipe_page_active = $active_config;
 		$project_page_active = "";
		$forms_page_active = "";
		break;
	case "bop":
		$title_name = "BOP index";
		$home_page_active = "";
 		$shear_page_active = "";
 		$BOPindex_page_active = $active_config;
 		$pipe_page_active = "";
 		$project_page_active = "";
		$forms_page_active = "";
		break;
	case "project":
		$title_name = "Project Builder";
		$home_page_active = "";
 		$shear_page_active = "";
 		$BOPindex_page_active ="";
 		$pipe_page_active = "";
 		$project_page_active = $active_config;
		$forms_page_active = "";
		break;
	case "updates":
		$title_name = "Update Log";
		$home_page_active = $active_config;
 		$shear_page_active = "";
 		$BOPindex_page_active ="";
 		$pipe_page_active = "";
 		$project_page_active = "";
		$forms_page_active = "";
		break;
	case "forms":
		$title_name = "Form - ";
		$home_page_active = "";
		$basiccalcs_page_active = "";
 		$shear_page_active = "";
 		$BOPindex_page_active ="";
 		$pipe_page_active = "";
 		$project_page_active = "";
		$forms_page_active = $active_config;
		switch ($_GET["sub"]){
			case "cids":
			$title_name .= "Client Input Data Sheet";
			//$loadscript .= "load_form_fields();"; 
			break;
		}
		break;
    //this will be the homepage case
	default:
		$title_name = "Home";
		$home_page_active = $active_config;
 		$shear_page_active = "";
 		$BOPindex_page_active ="";
 		$pipe_page_active = "";
 		$project_page_active = "";
		break;
}
//TODO: MOVE TO jQuery section of jfunctions.js
$onloadscript = "onload=\"".$loadscript."\"";  
?>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>OTC Solutions tool set - <?php echo $title_name; ?></title>
	<meta name="description" content="Tools available for OTCS engineers">
	<meta name="author" content="David Hanks">
	
	<!--HEADER SCRIPTS-->
	<script src="https://www.gstatic.com/firebasejs/4.13.0/firebase.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="include/jfunctions.js"></script>

	<?php echo $header_scripts ?>
	<!--END HEADER SCRIPTS-->

	<!--STYLE SHEETS-->
	<link rel="stylesheet" href="https://www.w3schools.com/lib/w3.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="CSS/style.css">
	<!--END STYLE SHEETS -->
</head>


<body <?php echo $onloadscript;?>>

<div class="wrapper">
<!--NAVIGATION BAR-->
<?php 
/* 
 * Navigation bar reference
 * https://www.w3schools.com/w3css/w3css_navigation.asp
 *
 * 
 */?>	
<div class="w3-bar w3-black">
  <a href="./" <?php echo $home_page_active; ?> class="w3-bar-item w3-button w3-mobile"><i class="fa fa-home w3-small"></i></a>
  <div class="w3-dropdown-hover">
  	<a href="?page=calcs" <?php echo $basiccalcs_page_active; ?> class="w3-button">Calculators</a>
  	<div class="w3-dropdown-content w3-bar-block w3-card-4">
      <a href="?page=calcs"<?php echo $basiccalcs_page_active; ?> class="w3-bar-item w3-button">About</a>
      <!-- Old <a href="?page=calcs&sub=ssc" class="w3-bar-item w3-button">Shear Calculator</a> -->
      <a href="?page=calcs&sub=mtsc" class="w3-bar-item w3-button">Shear Calculator</a>
      <a href="?page=calcs&sub=atest" class="w3-bar-item w3-button">Accumulator Test</a>
    </div>
  </div>
  <div class="w3-dropdown-hover">
  	<a href="?page=bop" <?php echo $BOPindex_page_active; ?> class="w3-button">BOP Index</a>
  	<div class="w3-dropdown-content w3-bar-block w3-card-4">
      <a href="?page=bop&sub=Browse" class="w3-bar-item w3-button">Browse all</a>
      <a href="?page=bop&sub=Detail" class="w3-bar-item w3-button">Detailed view</a>
      <a href="?page=bop&sub=Add" class="w3-bar-item w3-button">Add new</a>
    </div>
  </div>
  <div class="w3-dropdown-hover">
  	<a href="?page=forms" <?php echo $forms_page_active; ?> class="w3-button">Forms</a>
  	<div class="w3-dropdown-content w3-bar-block w3-card-4">
      <a href="?page=forms&sub=cids" class="w3-bar-item w3-button">CIDS</a>
    </div>
  </div>
  <a href="#" class="w3-bar-item w3-button w3-mobile w3-right" id="nav_login">Login</a>
  <a href="#" class="w3-bar-item w3-button w3-mobile w3-right w3-hide" id="nav_logout">Logout</a>
  
</div>
