
function add_function(){
	//adds rows to accumulator function calculations
	var table = document.getElementById("functions");

    //determine total table rows
    var x = table.rows.length;
    var row = table.insertRow(x);
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    var cell3 = row.insertCell(2);
    cell1.innerHTML = "<input type=\"text\" id=\""+x+"-Fname\">";
    cell2.innerHTML = "<input type=\"text\" id=\""+x+"-Fvol\">";
    cell3.innerHTML = "<input type=\"text\" id=\""+x+"-FMOP\">";
    
    //write the new total rows in the hidden input cell
    document.getElementById("TotF_rows").value=x;
}

function myFunction(){
	document.getElementById("demo").innerHTML="Hi!";
}

function check() {
	//Load form variables
	var quantity = check_form_field('Accum_qty',false);
	var Vgas = check_form_field('Vaccum_gas',false);
	var Prating = check_form_field('Accum_MaxPress', false);
	var gas_type = check_form_field('Accum_gastype',false);
	var precharge = check_form_field('Precharge',false);
	var T0_low = check_form_field('Precharge_lowTemp', false);
	var T0_high = check_form_field('Precharge_highTemp',false);
	
	var Tmax = check_form_field('AirTemp_max',false);
	var Pmud = check_form_field('Pmud',false);
	var MAWHP = check_form_field('mawhp',false);
	var Psw = check_form_field('Psw',false);
	var Pcf = check_form_field('Pcf',false);
	var Psys = check_form_field('Psys',false);
		
	var table = document.getElementById("functions");
    var function_rows = table.rows.length;  //subtract 1 because .lenght will include the header.
    var FVR_total = 0;
    
    var Fname = [];
    var Fvol = [];
    var FMOP = [];
    
    for(i=1; i< function_rows; i++){
    	Fname[i] = check_form_field(i+'-Fname',false);
    	Fvol[i] = check_form_field(i+'-Fvol',false);
    	FMOP[i] = check_form_field(i+'-FMOP',false);
    	var FVR_total = FVR_total + Fvol[i];
    }
	
	
	//Get FVR
	document.getElementById("test").innerHTML = "FVR is "+FVR_total;
	//
	
}
