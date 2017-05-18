/**shear.js
 * 
 * This file contains no php statements and is left as a js file to reduce load time of the cached file for the user.  
 * Functions within are only utilized during shear calculations, so this file does not need to be loaded on other pages. 
 * 
 * author David Hanks Feb 5, 2016
 *
 */

function load_form_fields(){
	//functions will display fields for selected options by default.  This is generally called on page load.
	BOP_fields();
	pipe_fields();
}

function check_form_field(field, defaultvalue) {
	//this function only works for id fields where a number is expected.  
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

function display_results(){
	document.getElementById("pipe_area").innerHTML = check_value_isNumber(calculateArea(),2,"");
	
	//if the pipe weight field is available, show the result.
	if(document.contains(document.getElementById("pipe_ppf"))){
		var od = check_form_field('pipe_od',""); 
		var wall = check_form_field('pipe_wall',"");
		var form_ys = check_form_field('pipe_minYS',false);
		
		// get a value for the grade 
		var grade_element = document.getElementById("pipe_grade");
        var grade_ys = grade_element.options[grade_element.selectedIndex].value;
        
		// if the minYS is not available use grade_ys
		if(form_ys){
			var ys=form_ys;
		}
		else if(grade_ys){
			var ys=grade_ys;
		}
		else{var ys=false;}
		 
		 if(od && wall && ys){
		 	var url = "include/pipe_weight.php?od="+od+"&wall="+wall+"&minYS="+ys;
		 	Call_ajax(url,test_ppf);
		 	//document.getElementById("pipe_ppf").innerHTML = ppf;
		 	//show_ppf(od,wall,ys);
		 }
		//check that each variable is a number
		//show_ppf(check_form_field('pipe_od',""),check_form_field('pipe_wall',""),check_form_field('pipe_minYS',""));
	}
	
	//If a shear method can be determined write it to the Force Approximations
	var TableForceApprox = "";
	var Cameron = Calculate_shear().Cameron_force; //check_value_isNumber(Calculate_shear().Cameron_force,0,false); TESTING
	var West = check_value_isNumber(Calculate_shear().West_force,0,false);
	var DistEnergy = check_value_isNumber(Calculate_shear().DE_force,0,false);
	
	if(Cameron){
		TableForceApprox += "<tr><td>F-Cameron</td><td>"+Cameron+"</td><td>kips</td></tr>";
	} 
	if(West){
		TableForceApprox += "<tr><td>F-West</td><td>"+West+"</td><td>kips</td></tr>";
	}
	if(DistEnergy){
		TableForceApprox += "<tr><td>F-DE</td><td>"+DistEnergy+"</td><td>kips</td></tr>";
	}
	
	//Fill SUMMARY BOX
	
	//Well Pressure
	document.getElementById('P_mud').innerHTML = check_value_isNumber(Calc_all().Press_well);
	//Dominate Well Pressure
	document.getElementById('WellP_type').innerHTML = Calc_all().Press_type;
	//Seawater head pressure
	document.getElementById("P_head_sw").innerHTML = check_value_isNumber(Calc_all().Press_head_sw);
	//Control fluid head pressure
	document.getElementById("P_head_cf").innerHTML = check_value_isNumber(Calc_all().Press_head_cf);
	//Force approximations
	document.getElementById("approx_forces").innerHTML = TableForceApprox;
	//Closing Pressure adjustment
	document.getElementById("P_adj").innerHTML = check_value_isNumber(Calc_all().Press_adj,2);
	//SHEAR PRESSURE
	document.getElementById("final_pressure").innerHTML = check_value_isNumber(calc_adj_shear());	
	//TEST
	//document.getElementById("test").innerHTML = check_value_isNumber(Calc_all().Press_adj,2) + "=adjusted pressure<br />" + Calc_all().ForceO_sw +"=sw Opening Force<br />" + Calc_all().ForceC_cf + "=cf Closing Force<br />" + Calc_all().ForceC_tr+"=tr Closing Force<br />" + Calc_all().closingarea + "=ClosingArea <br />" + Calc_all().openingarea + "=Opening Area";
	

}
function calculateArea() {
	var od = check_form_field('pipe_od');
	var wall = check_form_field('pipe_wall');
	//if (outside == null || outside == "" || isNaN(outside)) {outside=0;}
	//if (wall ==null || wall == "" || isNaN(wall)) {wall = 0;}
	if (wall==0){inside=0;} else{ var inside = od - (2 * wall);}
	
	var area = Math.PI * (Math.pow(od,2) - Math.pow(inside,2)) / 4;
	return area;
}

function display_area(){
	//this function may not be necessary.  I moved this line of code to display_results()
	document.getElementById("pipe_area").innerHTML = check_value_isNumber(calculateArea(),2,"");
}

function pipe_fields(){
	// change pipe selction method based on radio buttons
	var divobj = document.getElementById("pipe_values");
    var theForm = document.forms["sheardata"];
    var Pipe_choice = theForm.elements["Pipe_select"];
    var BOP_OEM = "";
    
    //UPDATE NEEDED.  Check for previous form values.  Use values in form or insert blank. 
    //UPDATE NEEDED.  Maintain pipe grade.
    //UPDATE NEEDED.  Selecting pipe grade changes min YS.  New function needed
	var od = check_form_field('pipe_od',"");
	var wall = check_form_field('pipe_wall',"");
	var ys = check_form_field('pipe_ys',"");
    var elong = check_form_field('pipe_elong',"");
    var minYS = check_form_field('pipe_minYS',"");
    var uts = check_form_field('pipe_uts',"");
    
	//list all rows for Specifying pipe. 
	var pipe_form_od = "<tr><td>Outside Diameter</td><td><input type=\"text\" name=\"pipe_od\" id=\"pipe_od\" onkeyup=\"display_results()\" value=\""+od+"\"></td><td>in</td></tr>";
	var pipe_form_wt = "<tr><td>Wall Thickness</td><td><input type=\"text\" name=\"pipe_wall\" id=\"pipe_wall\" onkeyup=\"display_results()\" value=\""+wall+"\"></td><td>in</td></tr>";
	var pipe_form_area = "<tr><td>Area</td><td id=\"pipe_area\"></td><td style=\"text-align:left\">in<sup>2</sup></td></tr>";
	var pipe_form_yield = "<tr><td>Yield Strength</td><td><input type=\"text\" id=\"pipe_ys\" onkeyup=\"display_results()\" value=\""+ys+"\"/></td><td>psi</td></tr>";
	var pipe_form_uts = "<tr><td>Ultimate Strength</td><td><input type=\"text\" id=\"pipe_uts\" onkeyup=\"display_results()\" value=\""+uts+"\"/></td><td>psi</td></tr>";
	var pipe_form_el = "<tr><td>% Elongation</td><td><input type=\"text\" id=\"pipe_elong\" onkeyup=\"display_results()\" value=\""+elong+"\"/></td><td>%</td></tr>";
	var pipe_form_ppf = "";
	var pipe_grade ="<tr><td>Pipe Grade</td><td><select id=\"pipe_grade\" onchange=\"display_results()\"><option value=\"75000\">E75</option><option value=\"80000\">L80</option><option value=\"95000\">X95</option><option value=\"105000\">G105</option><option value=\"110000\">P110</option><option value=\"125000\">Q125</option><option value=\"135000\">S135</option><option value=\"140000\">Z140</option><option value=\"150000\">V150</option></select></td></tr>";
	var pipe_form_minYS = "<tr><td>min. Yield Strength</td><td><input type=\"text\" id=\"pipe_minYS\" onkeyup=\"display_results()\" value=\""+minYS+"\"/></td><td>psi</td></tr>";
	
	//Check if Cameron is selected as OEM.  If so, include pipe weight
	if(document.contains(document.getElementById('OEM_select'))){  //If an OEM has been selected
		BOP_OEM_option = document.getElementById("OEM_select").options;
		OEM_index = document.getElementById("OEM_select").selectedIndex;
		BOP_OEM = BOP_OEM_option[OEM_index].text;
		if (BOP_OEM == "Cameron"){
			pipe_form_ppf = "<tr><td>Pipe weight</td><td><div id=\"pipe_ppf\"></div></td><td>ppf</td></tr>";
			//pipe_form_uts = "";
		}
	}
		
	if (Pipe_choice[1].checked) { 
		//Specify.  
        divobj.innerHTML = "<table style=\"width:auto\">"+ pipe_grade + pipe_form_minYS + pipe_form_yield + pipe_form_uts + pipe_form_el + pipe_form_od + pipe_form_wt + pipe_form_area + pipe_form_ppf  + "</table>";
    } 
    else{ //Select
        divobj.innerHTML = "<table style=\"width:auto\"><tr><td>Test</td><td colspan='2'>blank</td></tr>";      
    } 
}

function Calculate_shear() {
	/*Function Calculates the shear force in lbs for a given pipe size.  
	* It attempts to use 3 different methods: Distortion Energy, West, Cameron
	* The following values are returned.  calculate_shear().Cameron_force, .West_force, .DE_force
	* A successful evaluation will return a numerical values.  Unsuccesful evaluation will return false.
	* 
	*/ 
	var method = "";
	var ForceValues = {};
	var pipe_elong = check_form_field('pipe_elong',false);
	var min_YS = check_form_field('pipe_minYS',false);
	var UTS = check_form_field('pipe_uts',false);
	var ys = check_form_field('pipe_ys',false);
	var area = calculateArea().toFixed(2); 
	
	//get grade
	if(document.contains(document.getElementById('pipe_grade'))){  //If a pipe grade has been selected
			var grade_option = document.getElementById("pipe_grade").options;
			var grade_index = document.getElementById("pipe_grade").selectedIndex;
			var pipe_grade = grade_option[grade_index].text;
	}
	else{ var pipe_grade = false;}

	//Get BOP Manufacturer
	if(document.contains(document.getElementById('OEM_select'))){  //If an OEM has been selected
		var BOP_OEM_option = document.getElementById("OEM_select").options;
		var OEM_index = document.getElementById("OEM_select").selectedIndex;
		var BOP_OEM = BOP_OEM_option[OEM_index].text;
	}
	else{ var BOP_OEM = "";}
	//Is "Wireline BOP" check?
	
	//----CAMERON-----
	//Generate a force using Cameron's EB 702D.  The following must be available.
	// C3, Cameron BOP, ppf
	if (BOP_OEM == "Cameron"){
		
		//check that C3 can be calculated.
		//get pipeOD
		var od = check_form_field('pipe_od',"");
		
		//get bop_id
		if(document.contains(document.getElementById('BOP_select'))){  //If a bop has been selected
			var BOP_option = document.getElementById("BOP_select").options;
			var BOP_index = document.getElementById("BOP_select").selectedIndex;
			var BOP_id = BOP_option[BOP_index].value;
		}
		else{ var BOP_id = "";}
		
		var C3 = get_c3(od,pipe_grade,BOP_id); 
		
		//check that ppf is available.
		var ppf = check_form_field('pipe_ppf',"");
		//UPDATED NEEDED:  at this point ppf appears to be a string.  Test with this else{ ForceValues["Cameron_force"] = typeof ppf;}
		if(check_value_isNumber(ppf, 2, false) && check_value_isNumber(C3, 3, false) && check_value_isNumber(min_YS,0,false)){
			
			var CameronForce = C3 * ppf * min_YS;
			ForceValues["Cameron_force"] = CameronForce;
		}
		else{ ForceValues["Cameron_force"] = false;}  
	}
	else{ ForceValues["Cameron_force"] = false;}
	
	//-------WEST------
	//if %elongation && (UTS or YS  or pipe grade) then compute .West_force
	//Selection Yield (use nominal if available, else use pipe grade)  Selection_yield is used to select from the forumlas.
	//Evaluatioin Yield priority = UTS, YS, (nominal YS)/.85.  Evaluation yield is used to calculate the formula
	if(pipe_elong && (pipe_grade || min_YS )){
		
		//Determine Evaluation Yield.
		if (UTS){eval_yield=UTS/1000;}
		else if(ys){eval_yield=ys/1000;}
		else if(min_YS){eval_yield=(min_YS/.85)/1000;}
		else{ //pipe_grade -> E75, L80, X95, G105, P110, Q125, S135, Z140, V150
			if(pipe_grade = "E75"){eval_yield=75/.85;}
			else if(pipe_grade = "L80") {eval_yield=80/.85;}
			else if(pipe_grade = "G105") {eval_yield=105/.85;}
			else if(pipe_grade = "P110") {eval_yield=110/.85;}
			else if(pipe_grade = "Q125") {eval_yield=125/.85;}
			else if(pipe_grade = "S135") {eval_yield=135/.85;}
			else if(pipe_grade = "Z140") {eval_yield=140/.85;}
			else if(pipe_grade = "V150") {eval_yield=150/.85;}
			}  
				
		//Determine Select Yield
		if(min_YS){sel_Yield=min_YS/1000;}
		else{ //pipe_grade -> E75, L80, X95, G105, P110, Q125, S135, Z140, V150
			if(pipe_grade == "E75" || pipe_grade == "L80" || pipe_grade == "X95"){sel_Yield = 100;}
			else if (pipe_grade == "G105" || pipe_grade == "P110" || pipe_grade == "Q125"){sel_Yield = 130;}
			else if (pipe_grade == "S135" || pipe_grade == "Z140" || pipe_grade == "V150"){sel_Yield = 160;}
			}
						
		//The following values are given by a WEST engineering report generated for MMS
		//if ys is >=75ksi <105 use C=-234 A=-0.318 B=25.357 R2=.359 Stdev=62.03
		//if ys is >=105ksi <135 use C=181.33 A=.396 B=2.035 R2=.121 Stdev=62.89
		//if ys >=135 <165ksi use C=-35.11 A=.630 B=4.489 R2=.3 Stdev=76.69
		//else C=35.28 A=.427 B=6.629 R2=.231 Stdev=75.15
		if (sel_Yield >= 75 && sel_Yield < 105){
			var C = -234;
			var A = -.318;
			var B = 25.357;
			var R2 = .359;
			var Stdev = 62.03;
		}
		else if (sel_Yield >= 105 & sel_Yield < 135){
			var C = 181.33;
			var A = .396;
			var B = 2.035;
			var R2 = .121;
			var Stdev = 62.89;
		}
		else if (sel_Yield >= 135 & sel_Yield < 200){
			var C = -35.11;
			var A = .630;
			var B = 4.489;
			var R2 = .3;
			var Stdev = 76.69;
		}
		else {
			var C = 35.28;
			var A = .427;
			var B = 6.629;
			var R2 = .231;
			var Stdev = 75.15;
		}
		
		var WestForce = C + A * .577 * eval_yield * calculateArea() + B * pipe_elong + (2 * Stdev);
		ForceValues["West_force"]=WestForce;
		ForceValues["Recommended_force"]=WestForce;  //UPDATE NEEDED - ONLY WHEN RECOMMENDED.
	}
	else {ForceValues["West_force"]=false;}
	
	
	//----Simple Distortion Energy---
	if(false){  
		//yield how do we determine when to use distortion energy?  BOP type? tubing size?
		//Else If "Wireline BOP" is true
		//Set var Calculation Method to SimpleDistortion Energy
		
		//ForceValues["DE_force"]=DE_forceValue;
	}
	
	//UPDATE
	//Determine the recommended shear force
	//ForceValues["recommended_force"]=WestForce??;
	 	
	return ForceValues;
}

function calc_adj_shear(){
	var bop_closingarea = check_form_field('bop_closingarea');
	var Press_final = (Calculate_shear().Recommended_force * 1000) / bop_closingarea + Calc_all().Press_adj;
	

	if (!isNaN(Press_final) && isFinite(Press_final)){
		return Press_final;
	}
	else {return false;}
}	

function calc_grad(x) {
	
	// x value is given in pounds per gallon
	// y is returned in psi per ft of depth
	y=x*12/231;
	return y;
}

function show_grad(x) {
	 y=calc_grad(x); 
	document.getElementById("gr_mud").innerHTML = y.toFixed(2);
}

/* this has been moved to shear.js.php because it needs to access database info with php
function BOP_fields() {
	var theForm = document.forms["sheardata"];
	var BOPchoice = theForm.elements["BOP_select"];
	var divobj = document.getElementById("BOP_values");
	
	//Determing if BOP values will be selected or specified.  Show the correct form.
	if (BOPchoice[1].checked) {
	divobj.innerHTML = "<table><tr><td>Closing Area</td><td><input type=\"text\" name=\"closingArea\" id=\"bop_closingarea\" onkeyup=\"display_results()\" value=\"293.7\"/></td><td>in<sup>2</sup></td></tr><tr><td>Closing Ratio</td><td><input type=\"text\" name=\"bop_closingratio\" id=\"bop_closingratio\" onkeyup=\"display_results()\" value=\"19.6\"/></td><td></td></tr><tr><td>Tailrod Area</td><td><input type=\"text\" name=\"TailrodArea\" id=\"bop_trarea\" onkeyup=\"display_results()\"/></td><td>in<sup>2</sup></td></tr></table>";
	} else {
		divobj.innerHTML = "Manufacture <br /> Model <br /> Operator size";
	}
}

*/
function Calc_all() {
	
	//assign variables from user input.
	/*MISSING  need to add validation of form fields.  Is number.  Not empty*/
	var mawhp = document.getElementById('mawhp').value;
	var h_riser = document.getElementById('h_riser').value;
	var h_sw = document.getElementById('h_sw').value;
	var h_hpu = document.getElementById('h_hpu').value;
	var h_bop = document.getElementById('h_bop').value;
	var mudweight = document.getElementById('mudweight').value;
	var g_cf = document.getElementById('g_cf').value;
	var g_sw = document.getElementById('g_sw').value;

	//calculate mud pressure at depth
	var mudPressure = calc_grad( +mudweight ) * (+h_sw + +h_riser - +h_bop);
	
	//determine if MUD or MAWHP is greater
	if (mudPressure > +mawhp) {
		P_well = mudPressure;
		Ptype_well = "MUD";
	} else {
		P_well = +mawhp;
		Ptype_well = "MAWHP";
	}				
	
	//calculate fluid head pressure
	var head_sw = +g_sw * (+h_sw - +h_bop); //seawater head at specified water depth "+" added to convert var to number
	var head_cf = +g_cf * (+h_sw - +h_bop + +h_hpu); //control fluid head at water depth
	
	//set the variables for the BOP type
	var bop_closingarea = check_form_field('bop_closingarea'); // Closing Area = Ac
	var bop_closingratio = check_form_field('bop_closingratio'); // Closing ratio = Cr
	var bop_trarea = check_form_field('bop_trarea');
	
	//get Pressure of seawater at depth
	//calc opening area = Ac + At - Ac/Cr
	var bop_openingarea = bop_closingarea + bop_trarea - (bop_closingarea/bop_closingratio);
	
	//calculate opening force dues to seawater against operator = Psw x Ao
	var ForceO_sw = head_sw * bop_openingarea;
	
	//get Pressure of control fluid at depth = Pcf
	//calculate force of control fluid on closing side = Pcf x Ac
	var ForceC_cf = head_cf * bop_closingarea;
	
	//calculate closing force on the tailrod due to seawater = Psw x At
	var ForceC_tr = head_sw * bop_trarea;
	
	//determine adjustment in closing force due to hydrostatics
	var P_adjust_hyd = ((( ForceO_sw - ForceC_cf - ForceC_tr ) / bop_closingarea)) + ( P_well / bop_closingratio );
	//if (isNaN(P_adjust_hyd)) { var str_Padj = "-"; }
	//else { var str_Padj = P_adjust_hyd.toFixed(0);}
	
	//return values to be displayed
	var Pressures = {};
		Pressures["Press_well"] = P_well;
		Pressures["Press_type"] = Ptype_well;
		Pressures["Press_head_sw"] = head_sw;  
		Pressures["Press_head_cf"] = head_cf;
		Pressures["Press_adj"] = P_adjust_hyd;
	return Pressures;
}

function show_ppf(od,wall,ys) {  //UPDATED NEEDED.  THIS FUNCTION IS NO LONGER USED IT SHOULD BE REMOVED.
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        var xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {  //done and successful
            document.getElementById("pipe_ppf").innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET","include/pipe_weight.php?od="+od+"&wall="+wall+"&minYS="+ys,true);
    xmlhttp.send();
}

function test_ppf(xhttp) {
	document.getElementById("pipe_ppf").innerHTML = xhttp.responseText;
}

function get_c3(od,grade,bop_id) {
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        var xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            return xmlhttp.responseText;  //UPDATE NEEDED.  I don't belive this will work.
        }
    };
    xmlhttp.open("GET","include/c3.php?bop_id="+bop_id+"&pipe_grade="+grade+"&pipe_od="+od,true);
    xmlhttp.send();
}

function Call_ajax(url,cfunc){
	if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        var xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            cfunc(xmlhttp);
        }
    };
    xmlhttp.open("GET",url,true);
    xmlhttp.send();
}
