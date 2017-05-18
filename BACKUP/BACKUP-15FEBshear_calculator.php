
			<div class="w3-row">
			<div class="w3-col l8 m8">			
				<div id="form" class="w3-container">
				<form action="" id="sheardata">
									
				<h2>Pipe</h2>
				<input type="radio" name="Pipe_select" value="select" onclick="pipe_fields()"/> Select
				<input type="radio" name="Pipe_select" value="specify" onclick="pipe_fields()" checked/> Specify
				<div id='pipe_values'></div>
				<table style="width:auto">
					<tr><td>Outside Diameter</td><td><input type="text" name="pipe_od" id="pipe_od" onkeyup="display_results()"></td><td>in</td></tr>
					<tr><td>Wall Thickness</td><td><input type="text" name="pipe_wall" id="pipe_wall" value="0" onkeyup="display_results()"></td><td>in</td></tr>
					<tr><td>Area</td><td id="pipe_area">0.00</td><td style="text-align:left">in<sup>2</sup></td></tr>
					<tr><td>Ultimate Tensile</td><td><input type="text" id="pipe_ys" onkeyup="display_results()" /></td><td>psi</td></tr>
					<tr><td>% Elongation</td><td><input type="text" id="pipe_elong" onkeyup="display_results()"/></td><td>%</td></tr>
				</table>
				<div id="pipe_shear"><br /></div>
				
				<h2>Well</h2>
				<div id="gr_mud"></div>
				<table style="width:auto">
					<tr><td>MAWHP</td><td><input type="text" name="MAWHP" id="mawhp" onkeyup="display_results()"/></td><td>psi</td></tr>
					<tr><td>Height of Riser</td><td><input type="text" name="h_riser" id="h_riser" value="80" onkeyup="display_results()" /></td><td>feet</td></tr>
					<tr><td>Water Depth</td><td><input type="text" name="h_sw" id="h_sw" value="8000" onkeyup="display_results()"/></td><td>feet</td></tr>
					<tr><td>Height of HPU</td><td><input type="text" name="h_hpu" id="h_hpu" value="40" onkeyup="display_results()"/></td><td>feet</td></tr>
					<tr><td>Height of BOP above sea floor</td><td><input type="text" name="h_bop" id="h_bop" value="20" onkeyup="display_results()"/></td><td>feet</td></tr>
					<tr><td>Mud weight</td><td><input type="text" name="w_mud" id="mudweight" value="12" onkeyup="display_results()"/></td><td>ppg</td></tr>
					<tr><td>Control fluid gradient</td><td><input type="text" name="grad_cf" id="g_cf" value=".432" onkeyup="display_results()"/></td><td>psi/ft</td></tr>
					<tr><td>seawater gradient</td><td><input type="text" name="grad_sw" id="g_sw" value=".447" onkeyup="display_results()"/></td><td>psi/ft</td></tr>
				</table>
								
				<h2>BOP</h2>
				<!-- The user will select the BOP OEM, model, blades or input generic values -->
				<input type="radio" name="BOP_select" value="select" onclick="BOP_fields()"/>Select
				<input type="radio" name="BOP_select" value="specify" onclick="BOP_fields()" checked/>Specify
				<div id="BOP_values"><table style="width:auto">
					<tr><td>Closing Area</td><td><input type="text" id="bop_closingarea" onkeyup="display_results()"/></td><td>in<sup>2</sup></td></tr><tr>
					<td>Closing Ratio</td><td><input type="text" id="bop_closingratio" onkeyup="display_results()"/></td><td></td></tr>
					<tr><td>Tailrod Area</td><td><input type="text" id="bop_trarea" onkeyup="display_results()"/></td><td>in<sup>2</sup></td></tr>
				</table></div>
				<br />
<!--				
				<div id="p_summary">X psi in the wellbore due to X<br />X psi seawater head pressue <br />X psi control fluid head pressure.<br /><br />The above configuration requires a shear pressure adjustment of X psi.</div>
			
				<h2>Calculation Method</h2>
				+ West 1<br />
				+ West 2<br />
				+ West 3<br />
				+ Cameron<br />
				+ direct shear<br />
				
				<h2>Shear Estimate</h2>

				<div id="shear_press_final">{100,000 psi}</div>
-->				
				</form>
			</div>
			</div>
			
			<div class="w3-col l4 m4" >
				<div class="w3-card-4">
				<h3>Summary</h3>
					<table class="w3-table w3-bordered w3-striped">
						<tr><td>Well Pressure</td><td id="P_mud" class="values"></td><td>psi</td></tr>
						<tr><td>Dominate Well Pressure</td><td id="WellP_type"></td><td>psi</td></tr>
						<tr><td>Seawater head pressure</td><td id="P_head_sw"></td><td>psi</td></tr>
						<tr><td>Control fluid head pressure</td><td id="P_head_cf"></td><td>psi</td></tr>
						<tr><td>Force to shear pipe</td><td id="Shear_force"></td><td>kips</td></tr>
						<tr><td>Closing Pressure adjustment</td><td id="P_adj"></td><td>psi</td></tr>
						<tr><td>Shear Method</td><td id="shear_method">........</td><td></td></tr>
						<tr class="answer"><td><b>SHEAR PRESSURE</b></td><td id="final_pressure"></td><td>psi</td></tr>
					</table>
				</div>
			</div>
			</div>