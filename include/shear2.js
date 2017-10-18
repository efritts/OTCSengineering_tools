/**shear.js
 * 
 * This file contains no php statements and is left as a js file to reduce load time of the cached file for the user.  
 * Functions within are only utilized during shear calculations, so this file does not need to be loaded on other pages. 
 * 
 * author David Hanks 
 * Inital release Feb 5, 2016
 *
 */
  var database = firebase.database();
  var dbRefWorksheet = database.ref().child('shearWorksheet');
  var newWorksheet = dbRefWorksheet.push();
  console.log('Created key: '+newWorksheet.key);
$(document).ready(function() {
    "use strict";
	
	//disable the button to get a sharable link until a shear pressure is calculated.
	$("#get_link").prop('disabled',true).attr('title',"Pipe, Well, and BOP data are required to get link.");

	//Expandable tool tips
	//Hide the div below the #expander arrow
	$(".expander").click(function(){
	   $(this).parent().parent().children(":nth-child(2)").toggleClass("w3-show w3-hide");
	   if($(this).html()===' <i class="fa fa-chevron-down" aria-hidden="true"></i> '){
                $(this).html(' <i class="fa fa-chevron-up" aria-hidden="true"></i> ');
            }
            else{$(this).html(' <i class="fa fa-chevron-down" aria-hidden="true"></i> ');}
	});
	/*DELETE covered by $('.expander') above
	$("#about_ssc_expand").click(function(){
		$("#about_ssc").toggleClass("w3-show");
		$("#about_ssc").toggleClass("w3-hide");
		if($("#about_ssc_expand").text()===">"){
			$("#about_ssc_expand").text("v");
		}
		else{$("#about_ssc_expand").text(">");}
	});
	*/
	/*
	 * Form Error Checking
	 */
	//Wall thickness should be a number and it should not start with "."  
	// "0.25" is ok ".25" is not
	//TODO: wall should start with 0 not "."
	$('#pipe_wall').keyup(function(){
	    if($('#pipe_wall').val().match("^.")){
	        //var wall_float = parseFloat('0'+$('pipe_wall').val(),3);
	        //$('#pipe_wall').val(wall_float);
	    }
	});
		
	/*
	 * TUBULAR FORM 
	 */
	//Show the correct pipe grade if pipe, tubing, or casing is selected.
	$("#tubeStrengthType").change(function(){
	    if($("#tubeStrengthType").val()==="grade"){
	        $('#tubeStrength').addClass("w3-hide");
	        
	        if($('#tube_type').val()==="pipe"){
	           $('#tubeGrade').removeClass("w3-hide");
	           $('#casingTubeGrade').addClass("w3-hide");
	         }
	         else if(($('#tube_type').val()=== "tubing") || ($('#tube_type').val()=== "casing")){
	           $('#casingTubeGrade').removeClass("w3-hide");
	           $('#tubeGrade').addClass("w3-hide");
	         }
	    }
	    else{
            $('#tubeGrade').addClass("w3-hide");
            $('#casingTubeGrade').addClass("w3-hide");
            $('#tubeStrength').removeClass("w3-hide");
        }
	});
	
	//Show the appropriate inputs for the type fo tublular selected
	//For wireline, slickline, e-line, or braided cable show OD and breaking strength. 
	//For casing, tubing, and pipe show OD, wall, Elongation and either yield or Grade for strength.
	$('#tube_type').change(function(){
		var tubeType = $('#tube_type').val();
		if (tubeType === 'casing' || tubeType === 'pipe' || tubeType === 'tubing'){
		    $("#tubeStrengthType>option[value='brStrength']").prop("disabled",true);
            $("#tubeStrengthType>option[value='strength']").prop("disabled",false);
            $("#tubeStrengthType>option[value='grade']").prop("disabled",false);
            
            //show od, wall, % elongation
            $('.tubeOnly').removeClass('w3-hide');
            $('.wireOnly').addClass('w3-hide');
            
            //maintain the strength type, but change the grades available if pipe was changed to (casing || tubing) OR vise versa
		    if($('#tubeStrengthType').val()==="grade"){
                if ($('#tube_type').val() === "pipe"){
                   $('#tubeGrade').removeClass('w3-hide');
                   $('#casingTubeGrade').addClass('w3-hide');
                   $('#tubeStrength').addClass('w3-hide');
                }
                else if ($('#tube_type').val() === "casing" || $('#tube_type').val() === "tubing"){
                    $('#casingTubeGrade').removeClass('w3-hide');
                    $('#tubeGrade').addClass('w3-hide');
                    $('#tubeStrength').addClass('w3-hide');
                }
            }else if ($("#tubeStrengthType").val()==="strength"){
                //keep #tubeStrength visible
                //keep #tubeGrade & #casingTubeGrade hidden
            }else{ //by default select grade
                $("#tubeStrengthType>option[value='grade']").prop("selected",true);
                $('#tubeStrengthType').change();
            }
		}else{
			$("#tubeStrengthType>option[value='brStrength']").prop("disabled",false);
			$("#tubeStrengthType>option[value='strength']").prop("disabled",true);
			$("#tubeStrengthType>option[value='grade']").prop("disabled",true);
			$("#tubeStrengthType>option[value='brStrength']").prop("selected",true);
			
			//show od, wall, % elongation
			$('.tubeOnly').addClass('w3-hide');		
			$('.wireOnly').removeClass('w3-hide');	
		}
	});
	
	/*
	 * PIPE TABLE DISPLAY
	 * Listen to the firebase database and update the html table
	 */	
	 
	//When a tubular is added to the database, order them by breaking strength and renumber
    var fb_tubulars = newWorksheet.child('tubulars');
    fb_tubulars.orderByChild('brkStrength_sortDesc').on("value", function(data){
        var newPipeNo = 1, 
            newWireNo = 1;
        
        //update the pipe number based on breaking strength
        data.forEach(function(childData){
            var type = childData.child('type').val();

            if(type === 'pipe' || type === 'tubing' || type === 'casing'){
                childData.ref.update( {pipeNo: newPipeNo});
                newPipeNo +=1;
            }else{
                childData.ref.update( {pipeNo: newWireNo});
                newWireNo +=1;
            }
        });
        
        //Generate a new table
        //Get latest snapshot of data after pipeNo update. "data" uses snapshot before pipeNo is updated.  Need to get "newdata"
        fb_tubulars.orderByChild('pipeNo').once("value", function(newdata){
            
            //Clear the table except the header
            $('.pipeSummaryRow').remove();
            $('.wireSummaryRow').remove();
            newdata.forEach(function(childData){
                var type = childData.child('type').val(),
                    newPipeRow,
                    newWireRow,
                    pipeElong_txt;
                
                //create the pipe table
                if(type === 'pipe' || type === 'tubing' || type === 'casing'){
                    pipeElong_txt = childData.child('elongation').val() === null ? "" : childData.child('elongation').val()+" %";
                    newPipeRow = "<tr class='pipeSummaryRow'><td>"+childData.child('pipeNo').val()+"</td><td>"+childData.child('yieldstr').val()+"</td><td>"+pipeElong_txt+"</td><td>"+childData.child('diameter').val()+"</td><td>"+childData.child('wall').val()+"</td><td title='remove' data-key='"+childData.key+"'><i class='fa fa-trash-o ' aria-hidden='true'></i></td></tr>";
                    $('#tblPipe table').append(newPipeRow);
                }
                //create the wireline table
                else {
                    newWireRow = "<tr class='wireSummaryRow'><td>"+childData.child('pipeNo').val()+"</td><td>"+childData.child('brkStrength').val()+" lbs</td><td>"+childData.child('diameter').val()+"</td><td data-key='"+childData.key+"'><i class='fa fa-trash-o ' aria-hidden='true'></i></td></tr>";
                    $('#tblWire table').append(newWireRow);
                }
            });
        });
        
		//if there's pipes unhide the table
        if(newPipeNo>1){ $('#tblPipe').removeClass('w3-hide');}
        else{$('#tblPipe').addClass('w3-hide');}
        //if there's wires unhide the table
        if(newWireNo>1){ $('#tblWire').removeClass('w3-hide');}
        else{$('#tblWire').addClass('w3-hide');}
        
		console.log("listener saw : ", newPipeNo-1, " pipes &", newWireNo-1, "wires");
        console.log(data.val());
    });
	
	//Add a pipe to the list to be evaluated.
	$("#addPipe").click(function(){
	    //setup some vars
	    var pipeStrVal, pipeNo, newPipeRow, pipeElong_txt, wire_brkStr, pipeArea, rev_brkStr,
            tubeType = $('#tube_type').val(),
            tubeStrengthType = $('#tubeStrengthType').val(),
            pipeODval = $('#pipe_od').val(),
            pipeElongVal = $('#pipe_elong').val(),
            pipeWallVal = $('#pipe_wall').val(),
            pipeGrade = $('#tube_grade option:selected').text(),
            pipeAddError = false;
	    //reset the errors
	    $('#pipe_wall').removeClass("w3-border-red");
        $('#pipe_od').removeClass("w3-border-red");
        $('#brStrength').removeClass("w3-border-red");
        
        //Error checks on tubular form
		if(pipeODval === ""){
            //show error
	        $('#pipe_od').addClass("w3-border-red");
                pipeAddError = true;
		}
		if(tubeType === "pipe" || tubeType === "casing" || tubeType === "tubing"  ){
	    
	       if($('#pipe_wall').val() === ""){
               //show error
               $('#pipe_wall').addClass("w3-border-red");
               pipeAddError = true;
           }
           //TODO: if strength type is selected, then value should exist for yield.
           
		}else{
			if($('#brStrength').val() === ""){
			       //show error
			       $('#brStrength').addClass("w3-border-red");
			       pipeAddError = true;
			}
		}
		//if any errors are seen when adding, then exit the click handler
		if(pipeAddError){return;}

	   //Get the user's new tubular data
	   //if it's a pipe,casing,or tube assign those values to be stored	   
        if(tubeType === "pipe" || tubeType === "casing" || tubeType === "tubing"  ){    
	       pipeStrVal = $('#tubeStrengthType').val() === "grade" ? $('#tube_grade option:selected').val() : $('#pipe_minYS').val();
	       pipeElong_txt = pipeElongVal.length === 0 ? "" : pipeElongVal+" %";
	       pipeNo = $('#tblPipe tr').length;
	       pipeArea = Math.PI*(Math.pow(pipeODval,2)-Math.pow((pipeODval-(2*pipeWallVal)),2))/4;
	       console.log("Area is: ",pipeArea);
	       wire_brkStr = pipeStrVal * pipeArea;
        }else{
            pipeNo = $('#tblWire tr').length;
            wire_brkStr = $('#brStrength').val();
            pipeStrVal = null;
            pipeGrade = null;
            pipeWallVal = null;
        }
		rev_brkStr = 100000000 - wire_brkStr;
			   
        //add pipe to the database
        pipeElongVal = pipeElongVal.length === 0 ? null : pipeElongVal; 
        var pipe_data = {
                pipeNo: pipeNo,
                type: tubeType,
                diameter: pipeODval,
                elongation: pipeElongVal,
                wall: pipeWallVal,
                strengthType: tubeStrengthType,
                grade: pipeGrade,
                yieldstr: pipeStrVal,
                brkStrength: wire_brkStr,
                brkStrength_sortDesc: rev_brkStr
        };
       newWorksheet.child('tubulars').push(pipe_data, function(){console.log('added pipedata for Pipe number ' + pipeNo + pipe_data);});
       
	   //Reset the tubular form
	   $("#tube_type>option[value='pipe']").prop("selected",true);
	   $("#tubeStrengthType>option[value='grade']").prop("selected",true);
	   $("#tube_grade>option[value='75000']").prop("selected",true);
	   $("#tube_type").change();
	   $('#pipe_od').val("");
	   $('#pipe_wall').val("");
	   $('#pipe_elong').val("");
	   $('#brStrength').val("");
    
    });
    
    /*
     * Surface Subsea configuration
     */
    $('#rigBOPLoc').change(function(){
       if($('#rigBOPLoc').val() === 'surface'){
           $('.rigSurface').removeClass('w3-hide');
           $('.rigSubsea').addClass('w3-hide');
       }else{
           $('.rigSubsea').removeClass('w3-hide');
           $('.rigSurface').addClass('w3-hide');
       } 
    });
    
    $('#pipe_od, #pipe_wall, #pipe_elong, #masp, #g_cf, #g_sw, #mud_weight, #h_bop, #h_sw, #h_riser, #rigHPUelevation, #rigBOPLoc').change(function(){
        display_results();
    });
})
//Remove a row from the table.  Register for all new .fa-trash-o  classes added
//TODO: remove from firebase.  Let the table update on it's own.
.on('click', 'table .fa-trash-o ',function(){
   
    //Get the key value from the row attribute.
    var key = $(this).parent().attr('data-key');
    
    //Remove the key for this pipe row from firebase.
    newWorksheet.child('tubulars/'+key).remove().then(function(){
        console.log("removed key:" + key);    
    });
});

function display_ssc_save(xhttp) {
	var response = xhttp.responseText;
	if (isNumeric(response)){
	var link = "http://50.201.150.115/Compliance/?page=calcs&sub=ssc&save="+response;
	document.getElementById("save_link").innerHTML = "<a href="+link+">"+link+"</a>";
	}
	else{//the response may be an error if it is not numeric.
		alert(response);
	}
}
function getShareLink(){
	
	if (validateForm()){
		//create link
		//read each field and assign it to a variable.
		//Select or Specify
		var theForm = document.forms["sheardata"],
            Pipe_choice = theForm.elements["Pipe_select"][1].checked == true?"specify":"select";
		//Pipe grade
		if(document.contains(document.getElementById('pipe_grade'))){  //If a pipe grade has been selected
			var grade_option = document.getElementById("pipe_grade").options,
                grade_index = document.getElementById("pipe_grade").selectedIndex,
                pipe_grade = grade_option[grade_index].text;
		}
		else{ var pipe_grade = false;}
		//Min. Yield Strength
		var min_YS = check_form_field('pipe_minYS',"");
		var POST_min_YS = min_YS ? "minYS=" + min_YS : "";
		//Yield Strength
		var ys = check_form_field('pipe_ys',"");
		var POST_ys = ys ? "ys="+ys : "";
		//Ultimate Strength
		var UTS = check_form_field('pipe_uts',"");
		var POST_UTS = UTS ? "uts="+UTS : ""; 
		//%Elongation
		var elong = check_form_field('pipe_elong',"");
		var POST_elong = elong ? "elong="+elong:"";
		//Outside Diameter
		var od = check_form_field('pipe_od',""); 
		var POST_od = od ? "od="+od:"";
		//Wall Thickness
		var wall = check_form_field('pipe_wall',"");
		var POST_wall = wall ? "wall="+wall : "";
		
		//WELL INFO
		//MAWHP
		var MAWHP = check_form_field('mawhp',"");
		var POST_MAWHP = MAWHP ? "MAWHP="+MAWHP : "";
		//Height of Riser
		var h_riser = check_form_field('h_riser',"");
		var POST_h_riser = h_riser ? "h_riser="+h_riser : "";
		//Water depth
		var h_sw = check_form_field('h_sw',"");
		var POST_h_sw = h_sw ? "h_sw="+h_sw : "";
		//Height of HPU
		var h_hpu = check_form_field('rigHPUelevation',"");
		var POST_h_hpu = h_hpu ? "h_hpu="+h_hpu : "";
		//Height of BOP
		var h_bop = check_form_field('h_bop',"");
		var POST_h_bop = h_bop ? "h_bop="+h_bop :"";
		//MUD weight
		var mudweight = check_form_field('mudweight',"");
		var POST_mudweight = mudweight ? "mudweight="+mudweight : "";
		//Control fluid gradient
		var grad_cf = check_form_field('g_cf',"");
		var POST_grad_cf = grad_cf ? "grad_cf="+grad_cf : "";
		//Seawater gradient
		var grad_sw = check_form_field('g_sw',"");
		var POST_grad_sw = grad_sw ? "grad_sw="+grad_sw : "";
		
	
		//BOP INFO
		var post_BOP_info = "";
		
		//closing area, closing_ratio, and tailrod_area exist whether a BOP was selected or specified.  While we could look up the values if a model was selected, the lookup would not record user changes
		// Therefore we include it so and variations the user has made to the BOP model can be recorded.
		var closing_area = check_form_field('bop_closingarea',""); 
		var closing_ratio = check_form_field('bop_closingratio',"");
		var tailrod_area = check_form_field('bop_trarea',"");
		//if Specify BOP
		if(theForm.elements["BOP_select"][1].checked == true){//"Specify was selected"
			var BOP_choice = "specify";

			post_BOP_info = "bop_closingarea="+closing_area+"&bop_closingratio="+closing_ratio+"&bop_trarea="+tailrod_area+"&bop_choice="+BOP_choice;
		}
		//if Select BOP
		else{
			var BOP_choice = "select";
			//OEM, Model  //the closing_area, closing_ratio, tailrod_area can be determined by the model
			//var OEM_index = document.getElementById("OEM_select").selectedIndex;
			var OEM_element = document.getElementById("OEM_select");
			var OEM_index = OEM_element.options[OEM_element.selectedIndex].value;
			if(document.contains(document.getElementById('BOP_select'))){  //Checks that the Model dropdown is visible.
				//var BOP_index = document.getElementById("BOP_select").selectedIndex;
				var BOP_element = document.getElementById("BOP_select");
				var BOP_index = BOP_element.options[BOP_element.selectedIndex].value;
			}
			post_BOP_info += "bop_closingarea="+closing_area+"&bop_closingratio="+closing_ratio+"&bop_trarea="+tailrod_area+"&bop_choice="+BOP_choice+"&OEM="+OEM_index+"&BOPmodel="+BOP_index;
		}
		
		
		
		//create a url to send to a phpscript  It's long, so I'll prob need $_POST var
		//if pipe is specified
		if(Pipe_choice=="specify"){
			var post_form_data = "pipechoice="+Pipe_choice+"&gr_index="+grade_index+"&"+POST_min_YS+"&"+POST_ys+"&"+POST_UTS+"&"+POST_elong+"&"+POST_od+"&"+POST_wall;	
		}
		else{//if pipe is selected
			var post_form_data = "pipechoice=" + Pipe_choice;
		}
		//Well Data
		post_form_data += "&"+POST_MAWHP+"&"+POST_h_riser+"&"+POST_h_sw+"&"+POST_h_hpu+"&"+POST_h_bop+"&"+POST_mudweight+"&"+POST_grad_cf+"&"+POST_grad_sw;
		//BOP info
		post_form_data += "&"+post_BOP_info;
		
		// "&&" will appear where POST variable have not been entered.  This while statement removes those duplicate &'s
		while (post_form_data.includes("&&")){  //includes is not compatible with older browsers.
			post_form_data = post_form_data.replace("&&","&");	
		}
		
		//$_POST sent to Call_ajax()
		// pipechoice => string "specify"|"select" 
		// gr_index = int index number of the pipe grade
		// minYS => int(6)
		// ys => int(6)
		// uts => int(6)
		// elong => decimal (4,2)
		// od => decimal (4,2)
		// wall => decimal (6,4)
		// MAWHP => int(6)
		// h_riser, h_sw, h_hpu, h_bop = decimal (7,2)
		// mudweight => decimal(4,2)
		// bop_choice => string "specify"|"select"
		// OEM => int(3)
		// BOPmodel => int(4)
		// grad_cf => decimal(6,4)
		// grad_sw => decimal(6,4)
		
		//php script resonse should be the id of the newly created row
		Call_ajax("ShearCalculator/save_ssc_form.php",display_ssc_save, "POST",post_form_data);
	}
}

function load_form_fields(){
	//functions will display fields for selected options by default.  This is generally called on page load.
	BOP_fields();
	pipe_fields();
}

function evaluation_YS(){
	//Determine Evaluation Yield.
	// Preferred strength  UTS > Actual ys/.85 > Max spec YS
	var UTS = check_form_field('pipe_uts',false);
	var ys = check_form_field('pipe_ys',false);
	
	//get grade
	if(document.contains(document.getElementById('pipe_grade'))){  //If a pipe grade has been selected
			var grade_option = document.getElementById("pipe_grade").options;
			var grade_index = document.getElementById("pipe_grade").selectedIndex;
			var pipe_grade = grade_option[grade_index].text;
	}
	else{ var pipe_grade = false;}
		
	if (UTS){eval_yield=UTS/1000;}
	else if(ys){eval_yield=ys/(0.85 * 1000);}
	else{ //pipe_grade -> E75, L80, X95, G105, P110, Q125, S135, Z140, V150
		if(pipe_grade == "E75"){eval_yield=105;}
		else if(pipe_grade == "L80") {eval_yield=110;}  //UPDATE. assumed 110ksi max
		else if(pipe_grade == "X95") {eval_yield=125;}
		else if(pipe_grade == "G105") {eval_yield=135;}
		else if(pipe_grade == "P110") {eval_yield=140;} //UPDATE. assumed 140ksi max
		else if(pipe_grade == "Q125") {eval_yield=155;} //UPDATE.  assumed 155ksi max 
		else if(pipe_grade == "S135") {eval_yield=165;}
		else if(pipe_grade == "Z140") {eval_yield=160;}  //Ref. Grant Prideco
		else if(pipe_grade == "V150") {eval_yield=180;} //Ref. Grant Prideco
	}  
	return eval_yield;
}

function get_minYS(){
		// get_minYS will return the value provided in the form if available
		// otherwise it will provide the pipe grade selected
		// if none are available it will return false.
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
		
		return ys;
}

function calculateArea() {
	var od = check_form_field('pipe_od',false);
	var wall = check_form_field('pipe_wall',false);
	//if (outside == null || outside == "" || isNaN(outside)) {outside=0;}
	//if (wall ==null || wall == "" || isNaN(wall)) {wall = 0;}
	if (od){
		if (!wall){inside=0;} else{ var inside = od - (2 * wall);}
		var area = Math.PI * (Math.pow(od,2) - Math.pow(inside,2)) / 4;
		return area;
	}
	else{ return false;}
}

function display_area(){
	//this function may not be necessary.  I moved this line of code to display_results()
	document.getElementById("pipe_area").innerHTML = check_value_isNumber(calculateArea(),2,"");
}

function display_results(){
	$('#pipe_area').html(check_value_isNumber(calculateArea(),2,""));
	
	//if the pipe weight field is available, show the result. (A Cameron BOP is selected if the ppf field is shown)
	if(document.contains(document.getElementById("pipe_ppf"))){
		var od = check_form_field('pipe_od',""); 
		var wall = check_form_field('pipe_wall',"");
		var ys = get_minYS();
		
		if(od && wall && ys){
		 	var url = "include/pipe_weight.php?od="+od+"&wall="+wall+"&minYS="+ys;
		 	Call_ajax(url,process_ppf,"GET");  //shows the ppf and the Cameron Force value
		 }
	}
	
	//Get the closing area
	var bop_closingarea = check_form_field('bop_closingarea');
	
	//If a shear method can be determined write it to the Force Approximations
	var TableForceApprox = "<tr id=\"Fcam\"></tr>"; //THE row for the Cameron value will always be in here.
	var West = check_value_isNumber(Calculate_shear().West_force,0,false);
	var DistEnergy = check_value_isNumber(Calculate_shear().DE_force,0,false);
	
	
	if(West){ 
		West_info = Calculate_shear().West_info+"&#x0D Given by West Engineering document titled Mini Shear Study for MMS (BSEE TAP 455)";
		TableForceApprox += "<tr><td>F-West <span title=\""+West_info+"\"><span class=\"fa fa-info-circle w3-small\"></span></span></td><td>"+West+"</td><td>kips</td></tr>";
	}
	if(DistEnergy){
		TableForceApprox += "<tr><td>F-DE <span title=\""+Calculate_shear().DE_info+"\"><span class=\"fa fa-info-circle w3-small\"></span></span></td><td>"+DistEnergy+"</td><td>kips</td></tr>";
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
	document.getElementById('final_pressure_row').className = ""; //clear any error notification
	
	
	if(bop_closingarea && Calculate_shear().Recommended_force){	
	//Shear Pressure info
	document.getElementById("final_P_info").title= "= ("+check_value_isNumber(Calculate_shear().Recommended_force,0)+" * 1000) / "+bop_closingarea+" + "+check_value_isNumber(Calc_all().Press_adj,2);
	$("#get_link").prop('disabled',false).attr('title',"Click to get a sharable link");
	}
	//TEST
	//document.getElementById("test").innerHTML = check_value_isNumber(evaluation_YS());

}

function pipe_fields(){
	// change pipe selction method based on radio buttons
	var divobj = document.getElementById("pipe_values");
    var theForm = document.forms["sheardata"];
    var Pipe_choice = theForm.elements["Pipe_select"];
    var BOP_OEM = "";
    
    //UPDATE NEEDED.  Check for previous form values.  Use values in form or insert blank. 
    //Note Initial pipe grade.
    if(document.getElementById("pipe_grade")){var init_pipegrade = document.getElementById("pipe_grade").selectedIndex;}
    
    //UPDATE NEEDED.  Selecting pipe grade changes min YS.  New function needed
	var od = check_form_field('pipe_od',"");
	var wall = check_form_field('pipe_wall',"");
	var ys = check_form_field('pipe_ys',"");
    var elong = check_form_field('pipe_elong',"");
    var minYS = check_form_field('pipe_minYS',"");
    var uts = check_form_field('pipe_uts',"");
    
	//list all rows for Specifying pipe. 
	var pipe_form_od = "<div class=\"w3-row\"><div class=\"w3-col l3 m4 s4\">Outside Diameter</div><div class=\"w3-col l3 m4 s4\"><input type=\"text\" name=\"pipe_od\" id=\"pipe_od\" onkeyup=\"display_results()\" value=\""+od+"\" class=\"w3-input w3-padding-0\"/></div><div class=\"w3-col l1 m1 s1 w3-margin-left\">in</div></div>";
	var pipe_form_wt = "<div class=\"w3-row\"><div class=\"w3-col l3 m4 s4\">Wall Thickness</div><div class=\"w3-col l3 m4 s4\"><input type=\"text\" name=\"pipe_wall\" id=\"pipe_wall\" onkeyup=\"display_results()\" value=\""+wall+"\" class=\"w3-input w3-padding-0\"/></div><div class=\"w3-col l1 m1 s1 w3-margin-left\">in</div></div>";
	var pipe_form_area = "<div class=\"w3-row\"><div class=\"w3-col l3 m4 s4\">Area</div><div class=\"w3-col l3 m4 s4 w3-right-align w3-grey\" id=\"pipe_area\">[INPUT REQUIRED] </div><div class=\"w3-col l1 m1 s1 w3-margin-left\">in<sup>2</sup></div></div>";
	var pipe_form_yield = "<div class=\"w3-row\"><div class=\"w3-col l3 m4 s4\">Yield Strength</div><div class=\"w3-col l3 m4 s4\"><input type=\"text\" id=\"pipe_ys\" onkeyup=\"display_results()\" value=\""+ys+"\" class=\"w3-input w3-padding-0\"/></div><div class=\"w3-col l1 m1 s1 w3-margin-left\">psi</div></div>";
	var pipe_form_uts = "<div class=\"w3-row\"><div class=\"w3-col l3 m4 s4\">Ultimate Strength</div><div class=\"w3-col l3 m4 s4\"><input type=\"text\" id=\"pipe_uts\" onkeyup=\"display_results()\" value=\""+uts+"\" class=\"w3-input w3-padding-0\"/></div><div class=\"w3-col l1 m1 s1 w3-margin-left\">psi</div></div>";
	var pipe_form_el = "<div class=\"w3-row\"><div class=\"w3-col l3 m4 s4\">% Elongation</div><div class=\"w3-col l3 m4 s4\"><input type=\"text\" id=\"pipe_elong\" onkeyup=\"display_results()\" value=\""+elong+"\" class=\"w3-input w3-padding-0\"/></div><div class=\"w3-col l1 m1 s1 w3-margin-left\">%</div></div>";
	var pipe_form_ppf = "";
	var pipe_grade ="<div class=\"w3-row\"><div class=\"w3-col l3 m4 s4\">Pipe Grade</div><div class=\"w3-col l3 m4 s4\"><select id=\"pipe_grade\" onchange=\"display_results()\" class=\"w3-select\"><option value=\"75000\">E75</option><option value=\"80000\">L80</option><option value=\"95000\">X95</option><option value=\"105000\">G105</option><option value=\"110000\">P110</option><option value=\"125000\">Q125</option><option value=\"135000\">S135</option><option value=\"140000\">Z140</option><option value=\"150000\">V150</option></select></div></div>";
	var pipe_form_minYS = "<div class=\"w3-row\"><div class=\"w3-col l3 m4 s4\">Min. Yield Strength</div><div class=\"w3-col l3 m4 s4\"><input type=\"text\" id=\"pipe_minYS\" onkeyup=\"display_results()\"  class=\"w3-input w3-padding-0\"value=\""+minYS+"\"/></div><div class=\"w3-col l1 m1 s1 w3-margin-left\">psi</div></div>";
	//Check if Cameron is selected as OEM.  If so, include pipe weight
	if(document.contains(document.getElementById('OEM_select'))){  //If an OEM has been selected
		BOP_OEM_option = document.getElementById("OEM_select").options;
		OEM_index = document.getElementById("OEM_select").selectedIndex;
		BOP_OEM = BOP_OEM_option[OEM_index].text;
		if (BOP_OEM == "Cameron"){
			pipe_form_ppf = "<div class=\"w3-row\"><div class=\"w3-col l3 m4 s4\">Pipe weight</div><div class=\"w3-col l3 m4 s4 w3-right-align\" id=\"pipe_ppf\"></div><div class=\"w3-col l1 m1 s1 w3-margin-left\">ppf</div></div>";
			//pipe_form_uts = "";
		}
	}
		
	//CHANGED TO jQUERY - if (Pipe_choice[1].checked) { 
	if ($('input[name=Pipe_select]:checked').val()=='specify') {
		//Specify.  
        //CHANGED TO jQUERY - divobj.innerHTML = pipe_grade + pipe_form_minYS + pipe_form_yield + pipe_form_uts + pipe_form_el + pipe_form_od + pipe_form_wt + pipe_form_area + pipe_form_ppf;
        $('#pipe_values').html(pipe_grade + pipe_form_minYS + pipe_form_yield + pipe_form_uts + pipe_form_el + pipe_form_od + pipe_form_wt + pipe_form_area + pipe_form_ppf);
        //maintain the pipe grade
        if(init_pipegrade){document.getElementById("pipe_grade").selectedIndex = init_pipegrade;}
    } 
    else{ //Select
        //CHANGED TO jQUERY - divobj.innerHTML = "<div class=\"w3-row\"><div class=\"w3-col l4 m4 s4\">Not yet availabe.</div><div class=\"w3-col l4 m4 s4\">dropdown</div></div>";
        $('#pipe_values').html("<div class=\"w3-row\"><div class=\"w3-col l4 m4 s4\">Not yet availabe.</div><div class=\"w3-col l4 m4 s4\">dropdown</div></div>");    
    }    
}

function Calculate_shear() {
	/*Function Calculates the shear force in lbs for a given pipe size.  
	* It attempts to use 2 different methods: Distortion Energy &  West
	* The following values are returned:  calculate_shear().West_force & .DE_force
	* A successful evaluation will return a numerical values.  Unsuccesful evaluation will return false.
	* 
	* The Cameron Force is evaluated when the pipe weight is. See process_ppf() which is called in display_results(); 
	* 	This is done because the Cameron force is dependant on two calls to the database.  One for the ppf of the pipe, the other for the C3 value of the operator/pipe combo.
	* 	
	*/ 
	var method = "";
	var ForceValues = {};
	var pipe_elong = check_form_field('pipe_elong',false);
	var min_YS = check_form_field('pipe_minYS',false);
	var UTS = check_form_field('pipe_uts',false);
	var ys = check_form_field('pipe_ys',false);
	//var area = calculateArea().toFixed(2); 
	
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
	
	//-------WEST------
	//if %elongation && (UTS or YS  or pipe grade) && area then compute .West_force
	//Selection Yield (use nominal if available, else use pipe grade)  Selection_yield is used to select from the forumlas.
	//Evaluatioin Yield priority = UTS, YS/.85, max YS.  Evaluation yield is used to calculate the formula
	if(pipe_elong && (UTS || ys || pipe_grade) && (min_YS || pipe_grade) && calculateArea()){
		
		eval_yield = evaluation_YS();
						
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
		var WestEquationStr = C+" + "+A+" * .577 * "+eval_yield+" * "+check_value_isNumber(calculateArea(),2)+" + "+B+" * "+pipe_elong+" + (2 * "+Stdev+")";
		ForceValues["West_force"]=WestForce;
		ForceValues["Recommended_force"]=WestForce;  //UPDATE NEEDED - ONLY WHEN RECOMMENDED.
		ForceValues["West_info"]="West Force = "+WestEquationStr;
	}
	else {ForceValues["West_force"]=false;}
	
	//----Simple Distortion Energy---
	// Determines shear force based on distortion energy therory
	if((UTS || ys || pipe_grade) && calculateArea()){
				
		DE_forceValue = .577 * calculateArea() * evaluation_YS();
		ForceValues["DE_force"]=DE_forceValue;
		ForceValues["DE_info"]="Distortion Energy Force = .577 * "+check_value_isNumber(calculateArea(),2)+" * "+evaluation_YS();
			
		if(!ForceValues["West_force"]){  //Distortion energy should be used to compute the shear pressure if the West force is not available.
			ForceValues["Recommended_force"]=DE_forceValue;
			
		}
	}
	else {ForceValues["DE_force"]=false;}
	//UPDATE
	//Determine the recommended shear force
	//ForceValues["recommended_force"]=WestForce??;
	 	
	return ForceValues;
}

function calc_adj_shear(){
	var bop_closingarea = check_form_field('bop_closingarea');
	//Cameron > Recommended force
	
	var Press_final = (Calculate_shear().Recommended_force * 1000) / bop_closingarea + Calc_all().Press_adj;
	

	if (!isNaN(Press_final) && isFinite(Press_final)){
		return Press_final;
	}
	else {return false;}
}	

function calc_grad(x) {
	"use strict";
	// x value is given in pounds per gallon
	// y is returned in psi per ft of depth
	var y=x*12/231;
	return y;
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
	var masp = $('#masp').val(),
        mawhp = $('#mawhp').val(),
        h_riser = $('#h_riser').val(),
        h_sw = $('#h_sw').val(),
        h_hpu = $('#rigHPUelevation').val(),
        h_bop = $('#h_bop').val(),
        mudweight = $('#mudweight').val(),
        g_cf = $('#g_cf').val(),
        g_sw = $('#g_sw').val(),
	//calculate mud pressure at depth
	   mudPressure = calc_grad( +mudweight ) * (+h_sw + (+h_riser) - +h_bop);
	
	//determine if MUD or MASP/MAWHP is greater
	if($('#rigBOPLoc').val()==='subsea'){
        if (mudPressure > mawhp) {
            P_well = mudPressure;
            Ptype_well = "MUD";
        } else {
            P_well = mawhp;
            Ptype_well = "MAWHP";
        }
    }else{
        P_well = masp;
        Ptype_well = "MASP";
    }				
	
	//calculate fluid head pressure
	var head_sw = +g_sw * (+h_sw - +h_bop), //seawater head at specified water depth "+" added to convert var to number
        head_cf = +g_cf * (+h_sw - +h_bop + (+h_hpu)), //control fluid head at water depth
	
	//set the variables for the BOP type
        bop_closingarea = check_form_field('bop_closingarea'), // Closing Area = Ac
        bop_closingratio = check_form_field('bop_closingratio'), // Closing ratio = Cr
        bop_trarea = check_form_field('bop_trarea'),
	
	//get Pressure of seawater at depth
	//calc opening area = Ac + At - Ac/Cr
        bop_openingarea = bop_closingarea + bop_trarea - (bop_closingarea/bop_closingratio),
	
	//calculate opening force dues to seawater against operator = Psw x Ao
        ForceO_sw = head_sw * bop_openingarea,
	
	//get Pressure of control fluid at depth = Pcf
	//calculate force of control fluid on closing side = Pcf x Ac
        ForceC_cf = head_cf * bop_closingarea,
	
	//calculate closing force on the tailrod due to seawater = Psw x At
        ForceC_tr = head_sw * bop_trarea,
	
	//determine adjustment in closing force due to hydrostatics
        P_adjust_hyd = ((( ForceO_sw - ForceC_cf - ForceC_tr ) / bop_closingarea)) + ( P_well / bop_closingratio ),
        //output_str = 'Force0_sw = '+ForceO_sw+', ForceC_cf = '+ForceC_cf+', ForceC_tr = '+ForceC_tr+', bop_closing_area = '+bop_closingarea+', P_well = '+P_well+', bop_closingratio = '+bop_closingratio+', P_adjust_hyd = '+P_adjust_hyd;
        output_str = 'ForceC_cf = '+ForceC_cf+', head_sw ='+head_sw+', bop_trarea='+bop_trarea+', ForceC_tr = '+ForceC_tr+', P_adjust_hyd = '+P_adjust_hyd;
        console.log(output_str);
	
	//return values to be displayed
	var Pressures = {};
		Pressures["Press_well"] = P_well;
		Pressures["Press_type"] = Ptype_well;
		Pressures["Press_head_sw"] = head_sw;  
		Pressures["Press_head_cf"] = head_cf;
		Pressures["Press_adj"] = P_adjust_hyd;
	return Pressures;
}

function Call_ajax(url,cfunc,type,data){
	//type = "POST", "GET"
	//data = used for "POST"
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
    if (type == "POST"){
    	xmlhttp.open("POST", url, true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send(data);
    }
    else{
    	xmlhttp.open("GET",url,true);
    	xmlhttp.send();	
    }
    
}

function Cameron_shear(od,grade,bop_id,ppf) {
	
	//Generate a force using Cameron's EB 702D.  The following must be available.
	// C3, Cameron BOP, ppf
	// this function is only called after ppf is pulled from the database
	// this function uses od, grade, and bop_id to determine the C3 value
	// once the C3 value is pulled from the database a value is returned in the results table.
	
	ppf = check_value_isNumber(ppf,3,false);
	var bop_closingarea = check_form_field('bop_closingarea');
	
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        var xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var C3 = xmlhttp.responseText;
            var ys = get_minYS();
            CameronForce = parseFloat(ppf * C3 * ys / 1000).toFixed(1);
            CameronPressure = (CameronForce * 1000) / bop_closingarea + Calc_all().Press_adj;
            Cameron_info = "C3 = "+C3+"&#x0D Cameron Force = "+ppf+" * "+C3+" * "+ys+" / 1000";
            document.getElementById("Fcam").innerHTML = "<td>F-Cameron <span title=\""+Cameron_info+"\"><span class=\"fa fa-info-circle w3-small\"></span></span></td><td>"+CameronForce+"</td><td>kips</td>";
 			
 			//Display the Shear Pressure as calculated using Cameron force.
            document.getElementById("final_pressure").innerHTML = check_value_isNumber(CameronPressure,1);
            document.getElementById('final_pressure_row').className = ""; //clear any error notification
            document.getElementById("final_P_info").title= "Cameron Shear Pressure = ("+check_value_isNumber(CameronForce,0)+" * 1000) / "+bop_closingarea+" + "+check_value_isNumber(Calc_all().Press_adj,2);
        }
    };
    xmlhttp.open("GET","include/c3.php?bop_id="+bop_id+"&pipe_grade="+grade+"&pipe_od="+od,true);
    xmlhttp.send();
}

function process_ppf(xhttp) {
	document.getElementById("pipe_ppf").innerHTML = xhttp.responseText;
	
	var ppf = check_value_isNumber(xhttp.responseText,3,false);
	var od = check_form_field('pipe_od',false);
	
	//get grade
	if(document.contains(document.getElementById('pipe_grade'))){  //If a pipe grade has been selected
			var grade_option = document.getElementById("pipe_grade").options;
			var grade_index = document.getElementById("pipe_grade").selectedIndex;
			var pipe_grade = grade_option[grade_index].text;
	}
	else{ var pipe_grade = false;}
	
	//get bop_id
	if(document.contains(document.getElementById('BOP_select'))){  //If a bop has been selected
		var BOP_option = document.getElementById("BOP_select").options;
		var BOP_index = document.getElementById("BOP_select").selectedIndex;
		var BOP_id = BOP_option[BOP_index].value;
		}
	else{ var BOP_id = false;}
		
	if (od && BOP_id && pipe_grade){
			Cameron_shear(od,pipe_grade,BOP_id,ppf);
	}
}

function validateForm() {
	//checks that a shear value has been calculated before generating a valid link.
    var x = check_html_text('final_pressure',false);
    if (!x) {
         
        document.getElementById('warn_validate').style.display='block';
                
        //change class on shear pressure to show missing info;
        document.getElementById('final_pressure_row').className += "w3-border-red w3-leftbar";
        //disable button
        $("#get_link").prop('disabled',true).attr('title',"Pipe, Well, and BOP data are required to get link.");
        return false;
    }
    return true;
}
