<?php
/* index.php
 * 
 *
 * ADD CONTENT
 * Check user credentials
 */
?>
<!DOCTYPE html>
<html>
<?php include 'header.php'; ?>

<div class="w3-row">
<?php 

//change the page content based on page selection.  Page clicked on is given by $_GET["page"] 
switch ($_GET["page"]) {
	case "calcs":
		//REMOVE: if (empty($_GET["sub"])){ include ("calculators_home.html");}
		switch ($_GET["sub"]) {
			case "ssc":
		        include 'include/functions.php';
		        include 'include/shear.js.php';
				include 'shear_calculator.php';
				//if $_GET["save"] is set and it's numeric, then run some javascript to fill out the form.
				if(isset($_GET["save"]) && is_numeric($_GET["save"])){
					include 'ShearCalculator/load_ssc_save.php'; 				
 				}
				break;
			case "atest":
				//include 'include/functions.php';
				include 'form_accum_test.php';
				break;
            default:
                include 'calculators_home.html';
		}
		break;
	case "bop":
		switch ($_GET["sub"]) {
			case "Add":
				echo "<div class=\"w3-container content\"><h1>Add BOP</h1>";
				include 'form_bop.php';
				echo "</div>";
				break;
            case "Detail":
				echo "<div class='w3-container'>";
				include 'bopBrowse.php';
				echo "</div>";
                echo "<div class=\"w3-container\"><h1>BOP detail</h1>";
				include 'BOPdetail.php';
				echo "</div>";
				break;
			default:
            case "Browse":
                echo "<div class=\"w3-container content\"><h1>BOP's available</h1>";
                include 'bopBrowse.php';
                echo "</div>";
                break;
		}
		break;
	case "pipe":?>
		<div class="w3-row content">
			<div class="w3-col l8 w3-red">Column 75% red</div>
			<div class="w3-col l4 w3-grey">Column2 - 25% grey</div>
		</div>
		<?php
		break;
	case "updates":
		?>
		<div class="w3-container">
		<?php include "include/changelog.html"; ?>
		</div>
		<?php
		break;
	case "forms":
			switch ($_GET["sub"]) {
				case "cids":
					include 'include/functions.php';
					include 'include/shear.js.php';
					include "cids_form.html";
					break;	
				default:	
					include ("forms_home.html");
					break;
			}
		break;
	default:
		//this will be the homepage case
		?>
		<div class="w3-container">
		<div class="w3-col m8 l8">
		<h3>Default page</h3>
		<p><?php echo $clicked_page; ?></p>
		<p>On this page we'll have a menu of all the bop's we have on record.</p>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
		</div> 
		<div class="w3-col m4 l4"><p>Column 2 info.</p></div>
		</div>
		<?php
		break;
}
?>
</div>
</div>
			<div id="login-modal" class="w3-modal">
				<div class="w3-modal-content w3-animate-zoom">
					<header class="w3-container w3-black">
						<span class="w3-closebtn">&times;</span>	
						<h2>Login</h2>
					</header>
					<div id="login-modal-msg" class="w3-padding">
						<div><input type="text" name="login-email" id="login-email" class="w3-input" placeholder="Email"></div>
						<div><input type="password" name="login-password" id="login-password" class="w3-input" placeholder="password"></div>
						<div id="login-error" class="w3-margin w3-text-red"></div>
					</div>
					<footer class="w3-black w3-panel w3-padding w3-right-align">
						<button class="w3-button w3-white w3-small">Create account</button>
						<button class="w3-button w3-white w3-medium">Login</button>
					</footer>
				</div>
			</div>
<footer class="w3-container w3-black w3-right-align w3-tiny footer"><a href="?page=updates">last modified</a> June 19, 2017 by David Hanks</footer>	<!-- inline style corrects shear pressure overlap, but hides footer below initial area.  style="margin-top:10px;" -->
<!--SCRIPTS-->
	<script>
      // Initialize Firebase
      var config = {
        apiKey: "AIzaSyC1kzPDgWGUcqDItqOIvmA_CPRQwM7YWYw",
        authDomain: "otcsolutions-d2f8c.firebaseapp.com",
        databaseURL: "https://otcsolutions-d2f8c.firebaseio.com",
        projectId: "otcsolutions-d2f8c",
        storageBucket: "otcsolutions-d2f8c.appspot.com",
        messagingSenderId: "808288712006"
      };
      firebase.initializeApp(config);
    </script>
<!--END SCRIPTS -->
</body>
</html>
