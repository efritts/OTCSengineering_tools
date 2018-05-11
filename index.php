<?php
/* index.php
 * 
 *
 * TODO: Check user credentials
 */
?>
<!DOCTYPE html>
<html>
<?php include 'header.php'; ?>

<div class="w3-row">
<?php 

//change the page content based on page selection.  Page clicked on is given by $_GET["page"] 
switch ($getPage) {
    case "calcs":
        switch ($getSub) {
            case "ssc":
                include 'include/functions.php';
                include 'include/shear.js.php';
                include 'shear_calculator.php';
                //if $_GET["save"] is set and it's numeric, then run some javascript to fill out the form.
                if(isset($_GET["save"]) && is_numeric($_GET["save"])){
                        include 'ShearCalculator/load_ssc_save.php'; 				
                }
                break;
            case "mtsc":
                include 'include/functions.php';
                include 'include/shear2.js.php';
                include 'shear_calculator2.html';
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
        switch ($getSub) {
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
        <div class="w3-row content pvt">
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
        switch ($getSub) {
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
            <h3>Offshore Technical Compliance - Solutions</h3>
            <p>This site contains engineering tools to help evaluate a well.</p>
        </div> 
        <div class="w3-col m4 l4"><!--<p>Column 2 info.</p>--></div>
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
                        <button class="w3-button w3-white w3-medium">Create account</button>
                        <button class="w3-button w3-white w3-medium">Login</button>
                </footer>
        </div>
</div>
<footer class="w3-container w3-black w3-right-align w3-tiny footer"><span id="siteVersionInfo"></span></footer>	<!-- inline style corrects shear 
<!--<footer class="w3-container w3-black w3-right-align w3-tiny footer"><a href="?page=updates">last modified</a> December 19, 2017 by David Hanks</footer>-->	<!-- inline style corrects shear pressure overlap, but hides footer below initial area.  style="margin-top:10px;" -->
<?php
/*TODO: 
 *  add the latest git commit with shell_exec()
 * https://www.lullabot.com/articles/tip-show-the-last-git-commit-in-the-site-footer
 */
?>
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
    <?php echo $body_end_scripts; ?>
<!--END SCRIPTS -->
</body>
</html>
