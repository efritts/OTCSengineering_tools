$(function() {
	//Store original login-modal
	var loginmodal_original = $('#login-modal').clone();

	updateFooter();
	//Send verification email when button is pressed.
	$(document).on('click',"#login-modal button:contains('Send verification email')", function(){
		var user = firebase.auth().currentUser;
		user.sendEmailVerification().then(function() {
		  console.log('Verification sent to '+user.email);
		  $('#verifyemail-error').text('A message has been sent, please check your email.');
		  $('#login-modal footer').html('<button class="w3-button w3-white w3-medium">OK</button>');
		}, function(error) {
		  console.log('Problem sending email to '+user.email+': '+error);
		});	
	});
	
	$(document).on('click',"#login-modal button:contains('OK')", function(){
		//logout
		console.log('user clicked Logout button');
		firebase.auth().signOut();
		//hide modal
		$('#login-modal').css("display","none");
	});
	//When login is clicked, unhide modal
	$("#nav_login").click(function(){
		$("#login-modal").replaceWith(loginmodal_original.clone());
		$('#login-modal').css("display","block");	
	});
	
	//When enter is hit for the login-modal, login.
	$(document).on('keypress', "#login-modal input", function (e) {
	 var key = e.which;
	 if(key === 13){  // the enter key code
	    $("#login-modal button:contains('Login')").trigger("click");
	    return false;  
	  }
	});   
	
	//When login modal close is clicked close the modal
	$(document).on('click','#login-modal .w3-closebtn',function(){
		$('#login-modal').css("display","none");
	});
	
	//When modal login is clicked, use firebase.auth().signInWithEmailAndPassword(email, password)
	$(document).on('click',"#login-modal button:contains('Login')", function(){
		var email = $("#login-email").val();
		var pass = $("#login-password").val();
		
		const login_promise = firebase.auth().signInWithEmailAndPassword(email, pass);
		login_promise.catch(function(e){
			console.log(e.message);
			$('#login-error').text(e.code + ": " + e.message);
		});

	});
	
	//When modal create account is clicked, the modal is changed to include additional password and a sign-up button
	$(document).on('click', "#login-modal button:contains('Create account')" , function(){
		//change the header
		$("#login-modal h2").text('Create Account');
		
		//change the form
		var fm_signup = '<div><input type="text" name="login-first" id="login-first" class="w3-input" placeholder="First Name"></div><div><input type="text" name="login-last" id="login-last" class="w3-input" placeholder="Last Name"></div><div><input type="text" name="login-email" id="login-email" class="w3-input" placeholder="Email"></div><div><input type="password" name="login-password" id="login-password" class="w3-input" placeholder="password"></div><div><input type="password" name="login-password2" id="login-password2" class="w3-input" placeholder="password"><div id="login-error" class="w3-margin w3-text-red"></div></div>';
		$("#login-modal-msg").html(fm_signup);
		
		//change the buttons
		var fm_signup_btn ='<button class="w3-button w3-white w3-medium">Sign-up</button>';
		$("#login-modal footer").html(fm_signup_btn);
	});
	
	//When modal sign-up is clicked use firebase.auth().createUserWithEmailAndPassword(email, password)
	$(document).on('click', "#login-modal button:contains('Sign-up')", function(){
		
		var email = $("#login-email").val();
		var pass = $("#login-password").val();
		var pass2 = $("#login-password2").val();
		
		if (pass == pass2){
			const login_promise = firebase.auth().createUserWithEmailAndPassword(email, pass);
		
			login_promise
				.then(function(){
					console.log('Account created for '+email);
					//TODO: on success signup, send email verification with 
					firebase.auth().currrentUser.sendEmailVerification().then(function() {
					  // Email sent.
					  console.log('Verification sent to '+email);
					}, function(error) {
					  console.log('Problem sending email to '+email+': '+error);
					});
					
				})
				.catch(function(e){
				console.log(e.message);
				
				$('#login-error').text(e.code + ": " + e.message);
			});
			
			
		}
		else{
			$('#login-error').text('Passwords don\'t match!');
		}
		
	});	
	
	firebase.auth().onAuthStateChanged(function(firebaseUser) {
		if(firebaseUser){
			var user = firebase.auth().currentUser;
			var name, email, uid, emailVerified;
			name = user.displayName;
			email = user.email;
			emailVerified = user.emailVerified;
			console.log(email+" used login modal");
			//TODO: enable after email verification is active.  
			if(emailVerified){
				$('#login-modal').css("display","none");
				$("#nav_login").addClass("w3-hide");
				$("#nav_logout").text("Logout " + email);
				$("#nav_logout").removeClass("w3-hide");
                                $(".pvt").removeClass("w3-hide");
			}
			else{
				//TODO: Open modal to send verification email.
				//Ask user to verify email address
				$("#login-modal").replaceWith(loginmodal_original.clone());
				$("#login-modal h2").text('Verify Email');
				var fm_verify = '<div><p>'+email+', You gots to validate that email</p><div id="verifyemail-error" class="w3-margin w3-text-red"></div></div>'; 
				$("#login-modal-msg").html(fm_verify);
				$("#login-modal footer").html('<button class="w3-button w3-white w3-medium">Send verification email</button>');
				$('#login-modal').css("display","block");
			}
		}else{
			console.log('Logged out');
			$("#nav_logout").addClass("w3-hide");
			$("#nav_login").removeClass("w3-hide");
                        //Hide protected content
                        //if($(".pvt").addClass("w3-hide")){
                        //    $("#nav_login").click();
                        //}
		}
	});
	
	//Logout user
	$("#nav_logout").click(function(){
		console.log('user clicked Logout button');
		firebase.auth().signOut();	
	});
	
});
async function updateFooter(){ 
	var commitVersion = await commitInfo();
	$("#siteVersionInfo").html(commitVersion);
}
	
function commitInfo(x){
	// requests the git info
	// idshort, idlong, committer, date, summary are acceptable argument for x
	return new Promise(function(resolve, reject){
		x = x || "";

		$.get("include/git_info.php?"+x
		).done(function(git_info){
			console.log('received: '+git_info);
			resolve(git_info);
		}).fail(function(error){
            reject(()=>{console.log("Error in commitInfo: "+error);});
        });
	});
}
function GetElementInsideContainer(containerID, childID) {
    var elm = document.getElementById(childID);
    var parent = elm ? elm.parentNode : {};
    return (parent.id && parent.id === containerID) ? elm : {};
}

function hideshow(x,show,hide){
	/* Given that the value x was selected then id's with show will be shown and id's with hide will be hidden
	 * 
	 *
	 * x is an list of values that could be selected in a dropdown (ex. "Pipe, Tube")
	 * show is an list of ids that should be shown if x is selected
	 * hide is an list of ids that should be hidden if x is selected
	 */
	arry_x = x.split(',');
	arry_show = show.split(',');
	arry_hide = hide.split(',');
	
	//get the value of dropdown
	var dropdown = event.target;
	var dropdown_value = dropdown.options[dropdown.selectedIndex].value;
	
	for (i=0; i < arry_x.length; i++){
		if(dropdown_value == arry_x[i]){
			//change the class of document.getElementById("show") to "w3-show" and remove "w3-hide"
			if(show){//if there is something to show
			for (j=0; j < arry_show.length; j++){
				document.getElementById(arry_show[j]).classList.remove("w3-hide");
        		document.getElementById(arry_show[j]).classList.add("w3-show");	
			}
			}
			
			if(hide){//if hide has been set
			//change the class of document.getElementById("hide") to "w3-hide" and  remove "w3-show"
			for (k=0; k < arry_hide.length; k++){
				document.getElementById(arry_hide[k]).classList.remove("w3-show");
        		document.getElementById(arry_hide[k]).classList.add("w3-hide");
      		}
      		}
		}
		
	} 
}
function extractColumn(arr, column) {
	function reduction(previousValue, currentValue) {
		previousValue.push(currentValue[column]);
		return previousValue;
	}

	return arr.reduce(reduction, []);
}

// set the radio button with the given value as being checked
// do nothing if there are no radio buttons
// if the given value does not exist, all the radio buttons
// are reset to unchecked
function setCheckedValue(radioObj, newValue) {
	if(!radioObj)
		return;
	var radioLength = radioObj.length;
	if(radioLength == undefined) {
		radioObj.checked = (radioObj.value == newValue.toString());
		return;
	}
	for(var i = 0; i < radioLength; i++) {
		radioObj[i].checked = false;
		if(radioObj[i].value == newValue.toString()) {
			radioObj[i].checked = true;
		}
	}
}

function toggle_accordian(id) {
	//this funciton provides will hide a container of class="w3-show"
	//the id listed should always have w3-hide or w3-show classes
    var x = document.getElementById(id);
    x.classList.toggle("w3-show");
    x.classList.toggle("w3-hide");
}

function show_accordian(id) {
	//this funciton provides will show a container of class="w3-hide"
	//the id listed should always have w3-hide or w3-show classes
    var x = document.getElementById(id);
    if (x.classList.contains("w3-hide")) {
        x.classList.remove("w3-hide");
        x.classList.add("w3-show");
    }
}
function hide_accordian(id) {
	//this funciton provides will hide a container of class="w3-show"
	//the id listed should always have w3-hide or w3-show classes
    var x = document.getElementById(id);
    if (x.classList.contains("w3-show")) {
        x.classList.remove("w3-show");
        x.classList.add("w3-hide");
    }
}

function check_form_field(field, defaultvalue) {
	//this function only works for id fields where a number is expected.  
	//also only works for form inputs
	//Returns defaultvalue if a number wasn't provided.
	var input = $('#'+field).val(),
	
	//test that the field is present.
	//if(document.contains(document.getElementById(field))){input = document.getElementById(field).value;} 
	//deafultvalue = (typeof defaultvalue !== 'undefined') ? defaultvalue : 0;
	newvalue = (defaultvalue) ? defaultvalue : 0;
	if (input == null || input == "" || input == "undefined" || isNaN(input))  {
		input = newvalue;
		return input;
	}
	else {
		return parseFloat(input);
	}
}

function check_html_text(field, defaultvalue) {
	//this function only works for id fields where a number is expected.  
	//same as check_form_field but for contents within div tag
	//Returns defaultvalue if a number wasn't provided.
	var input;
	
	//test that the field is present.
	if(document.contains(document.getElementById(field))){input = document.getElementById(field).innerHTML;} 
	deafultvalue = (typeof defaultvalue !== 'undefined') ? defaultvalue : 0;
	if (input == null || input == "" || input == "undefined" || isNaN(input))  {
		input = defaultvalue;
		return input;
	}
	else {
		return parseFloat(input);
	}
}



function check_value_isNumber(input, decimals, defaultvalue) {
	//this function only work for fields where a number is expected.  
	decimals = (typeof decimals !== 'undefined') ? decimals : 0;
	var value = (typeof defaultvalue !== 'undefined') ? defaultvalue : "-";
	
	if (input === null || input === "" || input === "undefined" || isNaN(input))  {
		return value;
	}
	else {
		return parseFloat(input).toFixed(decimals);
	}
}

function checkandalert_field(field,type){
	//Function checks that field meets all the requirements of the type.
	//If the input is valid, it returns the input, otherwise it returns alerts
	
	//field => the id value of the form field
	//type => int, float, string

	var input;
	var FieldInfo = {};
	
	//test that the field is present
	if(document.contains(document.getElementById(field))){input = document.getElementById(field).value;}
	else {
		FieldInfo["value"]=false;
		FieldInfo["alert_txt"]="does not exist.";
		return FieldInfo;
	}
	
	//test if the field is empty
	if (input == null || input == "" || input == "undefined" )  {
		FieldInfo["value"]=false;
		FieldInfo["alert_txt"]="is empty";
		
		return FieldInfo;
	}
	
	if (type=="int"){
		if (isNaN(input)){
			FieldInfo["value"]=false;
			FieldInfo["alert_txt"]="is not an number";
		
			return FieldInfo;
		}
		if(false/*not whole number?*/){
			FieldInfo["value"]=false;
			FieldInfo["alert_txt"]="is not an whole number";
			
			return FieldInfo;
		}
			FieldInfo["value"]=input;			
	}
	else if (type=="float"){
		if (isNaN(input)){
			FieldInfo["value"]=false;
			FieldInfo["alert_txt"]="is not a number";
		
			return FieldInfo;
		}
		FieldInfo["value"]=input;
	}
	else if (type=="string"){
		FieldInfo["value"]=input;
		
	}
	
	//return an array with the value and alerts if any
	
	return FieldInfo;
}

function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}
function accum_check() {
	
	var alert_list ="";
	
	//CHECK AND ASSIGN VARIABLES.  Assign a value if one exists; otherwise add to the alert text list.
	//
	//quantity (total accumulators)
	current_form_input="quantity";
	current_fieldid="Accum_qty";
	current_type="int";
	if(checkandalert_field(current_fieldid,current_type).value){JS_Accum_qty=checkandalert_field(current_fieldid,current_type).value;}
	else {alert_list+="\n"+ current_form_input + " " + checkandalert_field(current_fieldid,current_type).alert_txt;}
	
	//gas volume
	current_form_input="gas volume";
	current_fieldid="Vaccum_gas";
	current_type="float";
	if(checkandalert_field(current_fieldid,current_type).value){JS_Vaccum_gas=checkandalert_field(current_fieldid,current_type).value;}
	else {alert_list+="\n"+ current_form_input + " " + checkandalert_field(current_fieldid,current_type).alert_txt;}
	
	//pressure rating (maximum pressure of the accumulator bottle)
	current_form_input="pressure rating";
	current_fieldid="Accum_MaxPress";
	current_type="int";
	if(checkandalert_field(current_fieldid,current_type).value){JS_Accum_MaxPress=checkandalert_field(current_fieldid,current_type).value;}
	else {alert_list+="\n"+ current_form_input + " " + checkandalert_field(current_fieldid,current_type).alert_txt;}
		
	current_form_input="gas type";
	current_fieldid="Accum_gastype";
	current_type="string";
	if(checkandalert_field(current_fieldid,current_type).value){JS_Accum_gastype=checkandalert_field(current_fieldid,current_type).value;}
	else {alert_list+="\n"+ current_form_input + " " + checkandalert_field(current_fieldid,current_type).alert_txt;}
	
	current_form_input="target precharge";
	current_fieldid="Precharge";
	current_type="int";
	if(checkandalert_field(current_fieldid,current_type).value){JS_Precharge=checkandalert_field(current_fieldid,current_type).value;}
	else {alert_list+="\n"+ current_form_input + " " + checkandalert_field(current_fieldid,current_type).alert_txt;}
	
	current_form_input="Precharge temperature (low)";
	current_fieldid="Precharge_lowTemp";
	current_type="int";
	if(checkandalert_field(current_fieldid,current_type).value){JS_Precharge_lowTemp=checkandalert_field(current_fieldid,current_type).value;}
	else {alert_list+="\n"+ current_form_input + " " + checkandalert_field(current_fieldid,current_type).alert_txt;}
	
	current_form_input="Precharge temperature (high)";
	current_fieldid="Precharge_highTemp";
	current_type="int";
	if(checkandalert_field(current_fieldid,current_type).value){JS_Precharge_highTemp=checkandalert_field(current_fieldid,current_type).value;}
	else {alert_list+="\n"+ current_form_input + " " + checkandalert_field(current_fieldid,current_type).alert_txt;}
	
	current_form_input="Max Ambient Air temperature";
	current_fieldid="AirTemp_max";
	current_type="int";
	if(checkandalert_field(current_fieldid,current_type).value){JS_AirTemp_max=checkandalert_field(current_fieldid,current_type).value;}
	else {alert_list+="\n"+ current_form_input + " " + checkandalert_field(current_fieldid,current_type).alert_txt;}	
	
	current_form_input="MUD pressure @ BOP";
	current_fieldid="Pmud";
	current_type="float";
	if(checkandalert_field(current_fieldid,current_type).value){JS_Pmud=checkandalert_field(current_fieldid,current_type).value;}
	else {alert_list+="\n"+ current_form_input + " " + checkandalert_field(current_fieldid,current_type).alert_txt;}
	
	current_form_input="MAWHP";
	current_fieldid="mawhp";
	current_type="float";
	if(checkandalert_field(current_fieldid,current_type).value){JS_mawhp=checkandalert_field(current_fieldid,current_type).value;}
	else {alert_list+="\n"+ current_form_input + " " + checkandalert_field(current_fieldid,current_type).alert_txt;}
	
	current_form_input="Seawater head @ BOP";
	current_fieldid="Psw";
	current_type="float";
	if(checkandalert_field(current_fieldid,current_type).value){JS_Psw=checkandalert_field(current_fieldid,current_type).value;}
	else {alert_list+="\n"+ current_form_input + " " + checkandalert_field(current_fieldid,current_type).alert_txt;}
	
	current_form_input="Control fluid head @ BOP";
	current_fieldid="Pcf";
	current_type="float";
	if(checkandalert_field(current_fieldid,current_type).value){JS_Pcf=checkandalert_field(current_fieldid,current_type).value;}
	else {alert_list+="\n"+ current_form_input + " " + checkandalert_field(current_fieldid,current_type).alert_txt;}
	
	current_form_input="System Pressure";
	current_fieldid="Psys";
	current_type="int";
	if(checkandalert_field(current_fieldid,current_type).value){JS_Psys=checkandalert_field(current_fieldid,current_type).value;}
	else {alert_list+="\n"+ current_form_input + " " + checkandalert_field(current_fieldid,current_type).alert_txt;}
	
	//UPDATE - Determine total functions, then check and assign to varibles.
	
	//alert missing info
	if (alert_list != null && alert_list != "" && alert_list != "undefined") {
		alert(alert_list);
		return false;
	}
	else{
		//load the php page that will process the input and generate results.
		//window.location="http://www.dignaj.com";
	}
	//Send data to results page
	//Display performance table
	
	//Display usable volume vs. FVR
	
	//Display any errors
	
	//Display Graph
	
	//Save Results Button
	
}
