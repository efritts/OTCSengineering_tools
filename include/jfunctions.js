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
	
	//document.getElementById("test").innerHTML = Object.getOwnPropertyNames(this).filter(function (p){return typeof this[p] === 'function';}).toString();
	//document.getElementById("test").innerHTML = dropdown.options[dropdown.selectedIndex].value;
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
	var input;
	
	//test that the field is present.
	if(document.contains(document.getElementById(field))){input = document.getElementById(field).value;} 
	deafultvalue = (typeof defaultvalue !== 'undefined') ? defaultvalue : 0;
	if (input == null || input == "" || input == "undefined" || isNaN(input))  {
		input = defaultvalue;
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
	
	if (input == null || input == "" || input == "undefined" || isNaN(input))  {
		input = value;
		return input;
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
