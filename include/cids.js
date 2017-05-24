/*cids.js
 * 
 * A javascript file for the Client Input Data Sheet
 */

//On document load do this...
$(function() {
	
	/* REMOVE THIS DOESN'T CLEAR THE INPUTS ON NEW ROWS.  NEED TO DELEGATE
	//Clear the hints on some inputs
	$("[id*='pipe_field'] input").one('click',function(){
		$(this).val('');  //UPDATE - These should remove the "od" or "id" text and the class that changes the color
		$(this).removeClass('inputhint');
	});
	*/
	
	//Clear the hints on some inputs
	$(document).on('click', "[id*='pipe_field'] input", function() {
	  // if this element has already been clicked, abort
	  if ($(this).data('clicked')) {
	    return false;
	  }
	   
	  	//perform click actions
		$(this).val(''); 
		$(this).removeClass('inputhint');
	 
	  // mark this element as clicked
	  $(this).data('clicked',true);
	});
	
	ShowDiagram('h_riser','diagram_h_riser');
	ShowDiagram('h_sw','diagram_h_sw');
	ShowDiagram('h_bop','diagram_h_bop');
	ShowDiagram('h_hpu','diagram_h_hpu');
	
	//show subsea by default
	$('#fm_bop_loc').val('subsea');
	$('#form_subsea').addClass('w3-show').removeClass('w3-hide');
	
	//show bop fields by default
	BOP_fields();
	
	//click handler for the submit button
	$('#frm_submit').click(ProcessForm);
	
	//Removes the last pipe row from the table when the "-" is clicked.
	$('#rmPiperow').click(function(){
		if (CountPipeRows() > 1){
			$('#pipe_table > div').last().remove();	
		}
	});
	
	//Adds a new pipe row to the CIDS form
	$('#addPiperow').click(function(){
		var max_row_num = CountPipeRows();
		var new_row_num = max_row_num +1;
		
		//clone the first row and configure it
		var $new_row = $('#pipe_row_template').clone();
		$new_row.attr('id','pipe_row'+new_row_num).addClass('w3-show').removeClass('w3-hide');
		$new_row.find('*[id^="pipe_field"]').removeClass('w3-show').addClass('w3-hide').attr('id','pipe_field'+new_row_num);  //reset the visibility and create the appropriate id
		var str_onchange = "hideshow('select','','pipe_field"+new_row_num+",wire_field"+new_row_num+",combo_field"+new_row_num+"'); hideshow('pipe,tube','pipe_field"+new_row_num+"','wire_field"+new_row_num+",combo_field"+new_row_num+"'); hideshow('wireline,slickline,eline','wire_field"+new_row_num+"','pipe_field"+new_row_num+",combo_field"+new_row_num+"'); hideshow('combo','combo_field"+new_row_num+"','pipe_field"+new_row_num+",wire_field"+new_row_num+"');";
		$new_row.find('*[id^="fm_pipetype-"]').attr('onchange',str_onchange); 
		$new_row.find('*[id^="fm_pipetype-"]').attr('id','fm_pipetype-'+new_row_num).attr('name','fm_pipetype-'+new_row_num); 
		$new_row.find('*[id^="fm_pipestrtype-"]').attr('name','fm_pipestrtype-'+new_row_num).attr('id','fm_pipestrtype-'+new_row_num);
		$new_row.find('*[id^="fm_pipestr-"]').attr('name','fm_pipestr-'+new_row_num).attr('id','fm_pipestr-'+new_row_num);
		$new_row.find('*[id^="pipe_od"]').attr('name','pipe_od'+new_row_num).attr('id','pipe_od'+new_row_num);
		$new_row.find('*[id^="pipe_id"]').attr('name','pipe_id'+new_row_num).attr('id','pipe_id'+new_row_num);
		$new_row.find('*[id^="wire_field"]').removeClass('w3-show').addClass('w3-hide').attr('id','wire_field'+new_row_num);  //reset the visibility and create the appropriate id
		$new_row.find('*[id^="fm_pipebrstr-"]').attr('name','fm_pipebrstr-'+new_row_num).attr('id','fm_pipebrstr-'+new_row_num);
		$new_row.find('*[id^="fm_pipe_qty"]').attr('name','fm_pipe_qty'+new_row_num).attr('id','fm_pipe_qty'+new_row_num);
		$new_row.find('*[id^="combo_field"]').removeClass('w3-show').addClass('w3-hide').attr('id','combo_field'+new_row_num);
		
		$('#pipe_table').append($new_row.clone());
	});
	
	
});
function ProcessForm(){
	//lock the fields
	$('#cids').find("input, select, button, textarea").prop("disabled", true);
	//$('#cids w3-button').prop("disabled", true);  UPDATE - these aren't form elements and can't be disabled.  do I remove the class and add them back?
	//UPDATE lock the buttons  (submit, add, minus)
	
	//check fields and 
	var FormGood = true;
	var errormsg = "";
	
	//START FORM ERROR CHECK
	//check the subsea fields for errors.  This doesn't need to be done if the class is hidden
		if($('#form_subsea').hasClass('w3-show') && !$('#form_subsea').hasClass('w3-hide')){
			if(!CheckField('mawhp','numeric','label_mawhp')){FormGood = false;};
			if(!CheckField('h_riser','numeric','label_h_riser')){FormGood = false;};
			if(!CheckField('h_sw','numeric','label_h_sw')){FormGood = false;};
			if(!CheckField('h_hpu','numeric','label_h_hpu')){FormGood = false;};
			if(!CheckField('h_bop','numeric','label_h_bop')){FormGood = false;};
			if(!CheckField('mudweight','numeric','label_mudweight')){FormGood = false;};
			if(!CheckField('g_cf','numeric','label_g_cf')){FormGood = false;};
		}
		else{
			if(!CheckField('masp','numeric','label_masp')){FormGood = false;};
		}
		
		//check emails are the same. 
		if($('#fm_email').val() !== $('#fm_email2').val()){
			errormsg += "Emails must match. <br />";
			FormGood = false;
			$('#fm_email').parent().siblings('label').addClass('error');
			$('#fm_email2').parent().siblings('label').addClass('error');
		}
		//check emails are not empty
		else if(!$('#fm_email').val()){ 
			errormsg += "Emails are required. <br />";
			FormGood = false;
			$('#fm_email').parent().siblings('label').addClass('error');
			$('#fm_email2').parent().siblings('label').addClass('error');
		}
		else{
			$('#fm_email').parent().siblings('label').removeClass('error');
			$('#fm_email2').parent().siblings('label').removeClass('error'); 
		}
		
		//check rig name are not empty
		if(!$('#fm_rig').val()){ 
			errormsg += "Rig name is required. <br />";
			FormGood = false;
			$('#fm_rig').parent().siblings('label').addClass('error');
		}
		else{
			$('#fm_rig').parent().siblings('label').removeClass('error');
		}
		
		//UPDATE - location, company is not empty
	//END FORM ERROR CHECK
	
	//if an error occurs send a message to the user
	if(!FormGood){
		$('#warn_validate_msg').html('<p><b>You messed something up</b></p><p> '+errormsg+' <p>Fields in <span class="w3-red">red text</span> are required.</p> ');
		$('#warn_validate').css("display","block");
				
		//unlock the form
		$('#cids').find("input, select, button, textarea").prop("disabled", false);
		return false;
	}
	//if no errors, then submit data to email list
	else{
		console.log("The form checked out!");
		//inputs must be disabled to pass to .serialize();
		$('#cids').find("input, select, button, textarea").prop("disabled", false);
		var request = $.post('include/cids_emailer.php', $('#cids').serialize());
		
		//on successful email
		request.done(function(data){
			console.log("cids_emailer complete" + data);
			$('#modal_success h2').text("Form Submitted");
			$('#modal_success_msg').html('<p>Well data sent to OTC - Solutions</p><p>Emailed group</p><p>Status:'+data+'</p>');
			$('#modal_success').css("display","block");
		});
		
		//do something if the emailer did not succeed
		request.fail(function(data){
			
		});
		
		//do this regardless of result
		request.always(function(){
			//unlock the form
			
		});
	}

	$('#cids').find("input, select, button, textarea").prop("disabled", false);
	
	//A test of the form submission
	$('#test').html('<h3>Test Purposes Only</h3><div id="testsub1" class="w3-container w3-light-grey"></div>');
	var fieldValuePairs = $('#cids').serializeArray();
	$.each(fieldValuePairs, function(index, fieldValuePair) {
		if(fieldValuePair.value){
    		$('#testsub1').append("Item " + index + " is [" + fieldValuePair.name + " = " + fieldValuePair.value + "]<br />");
    	}
	});
	console.log(fieldValuePairs);
}

function CheckField(field_id,checktype,errorfield_id){
	//checks the given field to ensure the appropriate data is entered.
	//it will update the class of an incorrect/correct field
	//it will return TRUE if the field_id is correct and FALSE if it is not.
	//field_id is the id of the field to be evaluated
	//check type is "numeric","string","date"
	//errorfield_id is the id of the row to change the class of if the field_id could not pass the check type
	if(checktype=="numeric"){
		if($.isNumeric($('#'+field_id).val())){
			$('#'+errorfield_id).removeClass('error');
			return true;
		}
		else{
			$('#'+errorfield_id).addClass('error');
			return false;
		}
	}
}
function CountPipeRows(){
	return $('#pipe_table > div').length - 1;
}
function ShowDiagram(hover_id,diagram_id){
	//when hovering over the #h_riser row, the diagram should be shown, on mouse out it should be hidden.
	$('#'+hover_id).parent().parent('.w3-row').hover(function(){
		$('#'+hover_id).parent().parent('.w3-row').addClass('w3-grey');
		$('#'+diagram_id).addClass('w3-show').removeClass('w3-hide');;
	//on mouse leave
	}, function(){  
		$('#'+diagram_id).addClass('w3-hide').removeClass('w3-show');
		$('#'+hover_id).parent().parent('.w3-row').removeClass('w3-grey');		
	});
}


function cids_add_equipment(){
	//adds rows to stack equipment
	var table = document.getElementById("fm_StackEquip");

    //determine total table rows
    var x = table.rows.length;
    var row = table.insertRow(x);
    
    //UPDATE - check that there's content in the last row before adding another'
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    var cell3 = row.insertCell(2);
    cell1.innerHTML = x;
    cell2.innerHTML = "<label for=\"fm_stk_type"+x+"\" class=\"w3-hide\">Type</label><select name=\"fm_stk_type"+x+"\" id=\"fm_stk_type"+x+"\" class=\"w3-select  w3-padding-0\"><option value=\"select\">[SELECT]</option><option value=\"surface\">Annular</option><option value=\"subsea\">BOP cavity</option></select>";
    cell3.innerHTML = "<label for=\"fm_stk_desc"+x+"\" class=\"w3-hide\">Description</label><input type=\"text\" name=\"fm_stk_desc"+x+"\" id=\"fm_stk_desc"+x+"\" class=\"w3_input w3-padding-0\" style=\"display:table-cell; width:100%\">";
    
    row.classList.add("w3-row");
    $(cell1).addClass('w3-col s2 w3-padding-0');
    $(cell2).addClass('w3-col s3 w3-padding-0');
    $(cell3).addClass('w3-col s7 w3-padding-0');
    
    //REMOVE - replaced with above jQuery
    //cell1.classList.add("w3-col");
    //cell1.classList.add("s2");
    //cell2.classList.add("w3-col");
    //cell2.classList.add("s3");
    //cell3.classList.add("w3-col");
    //cell3.classList.add("s7");
    
    if (x == 2){
    	//add the minus sign
    	show_accordian("eqp_rm");
    }
    //write the new total rows in the hidden input cell
    document.getElementById("Totstkeqp_rows").value=x;
}

function cids_rm_equipment(){
	//removes rows from stack equipment
	var table = document.getElementById("fm_StackEquip");

    //determine total table rows
    var x = table.rows.length;	
    
    //remove last row if total rows is greater than 2
    if(x > 2){
    	table.deleteRow(x-1);
    	if (x==3){
    		//hide the minus	
    		hide_accordian("eqp_rm");
    	}
    	
    }
}
