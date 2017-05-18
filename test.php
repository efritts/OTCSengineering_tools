<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>NWTS tool set</title>
<meta name="description" content="Calculates BOP shear pressure given BOP and pipe variables">
<meta name="author" content="David Hanks">

<!--SCRIPTS-->
<script src="include/shear.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
function calculate(){
	/*
	var minYS = check_form_field('minYS',false);
	var od = check_form_field('od',false);
	var wall_thk = check_form_field('wall',false);
	
	if(minYS && od && wall_thk){
		show_ppf(od,wall_thk,minYS);	
	}
	else{
		document.getElementById("pipe_ppf").innerHTML = "Need more.";
	}
	*/
	
}

function combine(){
	/*
	var od = check_form_field('od',false);
	
	var ppf = document.getElementById('pipe_ppf').value;//check_form_field('pipe_ppf',false);
	//Getting the ppf like this doesn't work.
	
	if (ppf && od){
		combine = ppf + od;
		document.getElementById("result").innerHTML = combine;
	}
	else{
		document.getElementById("result").innerHTML = "didn't work";
	}
	*/
}

</script>
<style>
	.red{color: red;}
</style>
</head>

<body>
    
    <form>
    	YS<input type="text" id="minYS" onkeyup="calculate(); combine()" /><br />
    	OD<input type="text" id="od" onkeyup="calculate(); combine()" /><br />
    	wall<input type="text" id="wall" onkeyup="calculate(); combine()" /><br />
    </form>
    
<div>ppf = <div id="pipe_ppf"></div></div>
<div>Output ppf x od = <div id='result'></div></div>
<input id="btn" type="button" value="click" onclick="combine()"/>

<script>
	$('#btn').click(function(){
		$('form').addClass('red');
	})
</script>
</body>

