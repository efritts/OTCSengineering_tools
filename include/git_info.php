<?php
//error_reporting(E_ALL);
//Get the options sent by $_GET
$option = filter_input(INPUT_GET,"option",FILTER_SANITIZE_STRING);

$commitIDshort = `git log --pretty=format:"%h" -1`;
$commitIDlong = `git log --pretty=format:"%H" -1`;
$commitCommitter = `git log --pretty=format:"%cn" -1`;
$commitDate = `git log --pretty=format:"%cd" --date=short -1`;
$commitSummary = `git log --pretty=format:"Revised by %cn on %cd - %h" --date=short -1`;  //Revised by $commitCommitter on $commitDate - $commitIDshort
$statement = "";

switch($option){
	case 'idshort': 
		$statement = $commitIDshort;
		break;
	case 'idlong': 
		$statement = $commitIDlong;
		break;
	case 'committer': 
		$statement = $commitCommitter;
		break;
	case 'date': 
		$statement = $commitDate;
		break;
	case 'summary': 
		$statement = $commitSummary;
		break;
	default:
		$statement = $commitSummary;
}
echo $statement;



