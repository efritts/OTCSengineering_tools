<?php
/* This form was setup to be processed with Javascript */

?>
			
			<div class="w3-row">
			<div class="w3-col l8 m8">
				<h2>Accumulator sizing verification.</h2>
				<p>This program takes the given data and determines if the accumulator capacity will meet the functional volume requirement.</p>			
				<div id="form" class="w3-container">
				<form action="" id="accumdata">	
					<h3>Accumulator</h3>
					<table style="width:auto">
						<tr><td>quantity</td><td><input type="text" id="Accum_qty" style="text-align: right;"/></td><td></td></tr>
						<tr><td>gas volume</td><td><input type="text" id="Vaccum_gas"/></td><td>gal</td></tr>
						<tr><td>pressure rating</td><td><input type="text" id="Accum_MaxPress"/></td><td>psig</td></tr>
						<tr><td>gas type</td><td><input type="text" id="Accum_gastype"/ value="Nitrogen"></td><td></td></tr>
						<tr><td>target precharge</td><td><input type="text" id="Precharge"/></td><td>psig</td></tr>
						<tr><td>Precharge temperature (low)</td><td><input type="text" id="Precharge_lowTemp"/></td><td>F</td></tr>
						<tr><td>Precharge temperature (high)</td><td><input type="text" id="Precharge_highTemp"/></td><td>F</td></tr>
					</table>
						
					<h3>Well Conditions</h3>
					<table style="width:auto">
						<tr><td>Max Ambient Air temperature</td><td><input type="text" id="AirTemp_max" value="120"/></td><td>F</td></tr>
						<tr><td>MUD pressure @ BOP</td><td><input type="text" id="Pmud"/></td><td>psig</td></tr>
						<tr><td>MAWHP</td><td><input type="text" id="mawhp"/></td><td>psig</td></tr>
						<tr><td>Seawater head @ BOP</td><td><input type="text" id="Psw"/></td><td>psig</td></tr>
						<tr><td>Control fluid head @ BOP</td><td><input type="text" id="Pcf"/></td><td>psig</td></tr>
						<tr><td>System Pressure <span title="Pressure at which the HPU operates"><span class="fa fa-info-circle w3-small"></span></span></td><td><input type="text" id="Psys"/></td><td>psig</td></tr>
					</table>							
					
					<h3>Functions</h3>
					<table style="width:auto" id="functions">
						<tr><th>Function Name</th><th>gallons</th><th>MOP @ depth (psia)</th></tr>
						<tr><td><input type="text" id="1-Fname"/></td><td><input type="text" id="1-Fvol"></td><td><input type="text" id="1-FMOP"></td></tr>
					</table>
					<br />
					<button onclick="add_function()" type="button"><span class="fa fa-plus w3-small"></span></button>
					
					<hr />
					
					<button onclick="accum_check()" type="button">Check</button>
					
					<div id="demo"></div>
					
					<!--Reuse?
					<table style="width:auto">
						<tr><td>MAWHP  <span title="Maximum Anticipated Well Head Pressure"><span class="fa fa-info-circle w3-small"></span></span></td><td><input type="text" name="MAWHP" id="mawhp" onkeyup="display_results()"/></td><td>psi</td></tr>
						<tr><td>Height of Riser</td><td><input type="text" name="h_riser" id="h_riser" value="80" onkeyup="display_results()" /></td><td>feet</td></tr>
						<tr><td>Water Depth</td><td><input type="text" name="h_sw" id="h_sw" value="8000" onkeyup="display_results()"/></td><td>feet</td></tr>
						<tr><td>Height of HPU<span title="Elevation of the HPU above the water line"><span class="fa fa-info-circle w3-small"></span></span></td><td><input type="text" name="h_hpu" id="h_hpu" value="40" onkeyup="display_results()"/></td><td>feet</td></tr>
						<tr><td>Height of BOP above sea floor</td><td><input type="text" name="h_bop" id="h_bop" value="20" onkeyup="display_results()"/></td><td>feet</td></tr>
						<tr><td>Mud weight</td><td><input type="text" name="w_mud" id="mudweight" value="12" onkeyup="display_results()"/></td><td>ppg</td></tr>
						<tr><td>Control fluid gradient</td><td><input type="text" name="grad_cf" id="g_cf" value=".432" onkeyup="display_results()"/></td><td>psi/ft</td></tr>
						<tr><td>seawater gradient</td><td><input type="text" name="grad_sw" id="g_sw" value=".447" onkeyup="display_results()"/></td><td>psi/ft</td></tr>
					</table>
					-->
				</form>
				<hr />
				<div id="test"></div>
			</div>
			</div>	
			</div>
			
			