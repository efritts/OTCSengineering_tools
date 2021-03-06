
			<div class="w3-row">
				<div class="w3-col w3-margin">
					<h1 style="display: inline">Simple Shear Calculator.</h1><span id="about_ssc_expand">></span>
				</div>
			</div>
			<div class="w3-row w3-hide w3-light-grey w3-animate-left" id="about_ssc">
				<div class="w3-col w3-margin">
				<p class="w3-container">This calculator will determine an approximate shear pressure for a specific pipe and BOP under certain well parameters.  To get a pressure a pipe must be selected, well parameters, and a BOP.</p>
				<p class="w3-container">Up to three force approximations can be generated based on the type of equipment being utilized:</p>
				<div class="w3-container">
					<ul class="w3-container"><li><u>F-Cameron:</u>  This force is preferred when using a Cameron BOP.  It is evaluated using parameters found in Cameron EB 702D Rev E1.  For this force to be calculated the user must select a Cameron BOP, a Pipe Outside Diameter, and wall thickness.</li>
						<li><u>F-West:</u>  This force is preferred is a Cameron BOP is not selected and a pipe Elongation % is available.  It is based on <a href="https://www.bsee.gov/research-record/tap-463-evaluation-sheer-ram-capabilities">BSEE TAP 463</a> generated by West Engineering</li>
						<li><u>F-DE:</u> This force is based on the distortion energy theory.  It is the least preferred and is presented for a comparison.</li>
					</ul>
				</div>
				</div>
				</div>
			</div>
			<div class="w3-row">
			<div class="w3-col l8 m8 w3-container" id="form">			
				<form action="" id="sheardata" name="shear_inputs">
				<div class="w3-row"><h3 style="display: inline">Pipe</h3><span id="about_pipe_expand">></span></div>	
				<div class="w3-row w3-hide w3-light-grey w3-animate-left" id="about_pipe">
					<p>Required fields: Strength, Outside Diameter</p><p>Multiple values for pipe strength are available. Grade, Min. Yield Strength, Yield Strength, Ultimate Strength can all be used to evaluate the strength of a pipe.  This program prioritizes them as follows: Ultimate Strength > Yield Strength/.85 > Max spec YS.  That is the Ultimate Stength is preferred over the Yield Strength which is preferred over the max yield.  The Max Yield Strength is given by the pipe grade's specification in API 5DP.  Min. Yield Strength is only used  when a Cameron BOP is being utilized. </p>
				</div>				
				<div class="w3-row">
					<div class="w3-col l3 m4 w3-light-grey"><input type="radio" name="Pipe_select" value="select" onclick="pipe_fields()" class="w3-radio w3-border-bottom" disabled/> Select </div>
					<div class="w3-col l4 m5 w3-light-grey"><input type="radio" name="Pipe_select" value="specify" onclick="pipe_fields()" class="w3-radio w3-border-bottom" checked/> Specify</div>
				</div>
				<div id='pipe_values'></div>
				<div id="pipe_shear"></div>
				<br />
				
				<h3 style="display: inline">Well</h3><span id="about_well_expand">></span>
				<div class="w3-row w3-hide w3-light-grey w3-animate-left" id="about_well">
					<p>Required fields: None</p><p>These fields specify the parameters of the well.  They are required to accurately calculate the well pressure.  Not including them will calculate the shear pressure on surface with no internal well pressure.</p>
				</div>
				<div id="gr_mud"></div>
				<div class="w3-row">
					<div class="w3-col l3 m4 s4">MAWHP  <span title="Maximum Anticipated Well Head Pressure"><span class="fa fa-info-circle w3-small"></span></span></div>
					<div class="w3-col l3 m4 s4"><input type="text" name="MAWHP" id="mawhp" onkeyup="display_results()" class="w3-input w3-padding-0"/></div>
					<div class="w3-col l1 m1 s1 w3-margin-left">psi</div>
				</div>
				<div class="w3-row">
					<div class="w3-col l3 m4 s4">Height of Riser  <span title="Distance from sea level to the top of the riser."><span class="fa fa-info-circle w3-small"></span></span></div>
					<div class="w3-col l3 m4 s4"><input type="text" name="h_riser" id="h_riser" value="80" onkeyup="display_results()" class="w3-input w3-padding-0"/></div>
					<div class="w3-col l1 m1 s1 w3-margin-left">feet</div>
				</div>
				<div class="w3-row">
					<div class="w3-col l3 m4 s4">Water Depth</div>
					<div class="w3-col l3 m4 s4"><input type="text" name="h_sw" id="h_sw" value="8000" onkeyup="display_results()" class="w3-input w3-padding-0"/></div>
					<div class="w3-col l1 m1 s1 w3-margin-left">feet</div>
				</div>
				<div class="w3-row">
					<div class="w3-col l3 m4 s4">Height of HPU <span title="Elevation of the HPU above the water line"><span class="fa fa-info-circle w3-small"></span></span></div>
					<div class="w3-col l3 m4 s4"><input type="text" name="h_hpu" id="h_hpu" value="40" onkeyup="display_results()" class="w3-input w3-padding-0"/></div>
					<div class="w3-col l1 m1 s1 w3-margin-left">feet</div>
				</div>
				<div class="w3-row">
					<div class="w3-col l3 m4 s4">Height of BOP <span title="Elevation of the BOP operator above the sea floor"><span class="fa fa-info-circle w3-small"></span></span></div>
					<div class="w3-col l3 m4 s4"><input type="text" name="h_bop" id="h_bop" value="20" onkeyup="display_results()" class="w3-input w3-padding-0"/></div>
					<div class="w3-col l1 m1 s1 w3-margin-left">feet</div>
				</div>
				<div class="w3-row">
					<div class="w3-col l3 m4 s4">Mud weight</div>
					<div class="w3-col l3 m4 s4"><input type="text" name="w_mud" id="mudweight" value="12" onkeyup="display_results()" class="w3-input w3-padding-0"/></div>
					<div class="w3-col l1 m1 s1 w3-margin-left">ppg</div>
				</div>
				<div class="w3-row">
					<div class="w3-col l3 m4 s4">Control fluid gradient</div>
					<div class="w3-col l3 m4 s4"><input type="text" name="grad_cf" id="g_cf" value=".432" onkeyup="display_results()" class="w3-input w3-padding-0"/></div>
					<div class="w3-col l1 m1 s1 w3-margin-left">psi/ft</div>
				</div>
				<div class="w3-row">
					<div class="w3-col l3 m4 s4">Seawater gradient</div>
					<div class="w3-col l3 m4 s4"><input type="text" name="grad_sw" id="g_sw" value=".447" onkeyup="display_results()" class="w3-input w3-padding-0"/></div>
					<div class="w3-col l1 m1 s1 w3-margin-left">psi/ft</div>
				</div>
				<div class="w3-row"><h3>BOP</h3></div>
                <!-- The user will select the BOP OEM, model, blades or input generic values -->
                <div class="w3-row ">				
					<div class="w3-col l3 m4 w3-light-grey"><input type="radio" name="BOP_select" value="select" onclick="BOP_fields()" class="w3-radio w3-border-bottom"/><label>Select from list</label></div>
					<div class="w3-col l4 m5 w3-light-grey"><input type="radio" name="BOP_select" value="specify" onclick="BOP_fields()" checked class="w3-radio w3-border-bottom"/><label>Specify parameters</label></div>
                </div>
                <div id="BOP_values"></div>
				<br />
		
				</form>
				<div id="test"></div>
			</div>
			
			<div class="w3-col l4 m4" >
				<div class="w3-card-4">
				<h3>Summary</h3>
					<table class="w3-table w3-bordered w3-striped">
						<tr><td>Well Pressure <span title="The greater of the pressure due to the mud weight at the depth of the BOP or the MAWHP"><span class="fa fa-info-circle w3-small"></span></span></td><td id="P_mud" class="values"></td><td>psi</td></tr>
						<tr><td>Dominate Well Pressure  <span title="Displays which pressure is displayed in the &quot;Well Pressure&quot; row"><span class="fa fa-info-circle w3-small"></span></span></td><td id="WellP_type"></td><td>psi</td></tr>
						<tr><td>Seawater head pressure <span title="Acts on the opening side and the tailrod if present."><span class="fa fa-info-circle w3-small"></span></span></td><td id="P_head_sw"></td><td>psi</td></tr>
						<tr><td>Control fluid head pressure <span title="Acts on the closing side."><span class="fa fa-info-circle w3-small"></span></span></td><td id="P_head_cf"></td><td>psi</td></tr>
                        <tr><td>Closing Pressure adjustment <span title="Added to the shear pressure to account for the Well, Seawater head, and Control fluid head pressures."><span class="fa fa-info-circle w3-small"></span></span></td><td id="P_adj"></td><td>psi</td></tr>						
                        <tr><td></td><td></td><td></td></tr>
                        <tr><td colspan="3"><u>Force Approximations</u>  <span title="If enough information is provided the program will provide one to three different methods of shear force approximations."><span class="fa fa-info-circle w3-small"></span></span></td></tr>
                        <tr><td colspan="3"><table class="w3-table w3-bordered w3-striped" id="approx_forces"></table></td></tr>
						<tr class="answer"><td id="final_pressure_row" class="" title="Shear pressure considers F-West, F-Cam, and F-DE and uses the most appropriate.  A pipe OD and BOP must be selected to get a value."><b>SHEAR PRESSURE</b> <span id="final_P_info" ><span class="fa fa-info-circle w3-small"></span></span></td> <td id="final_pressure">-</td><td>psi</td></tr>
					</table>
				</div>
				<div class="w3-center"><button class="w3-button w3-blue w3-margin" id="get_link" onclick="getShareLink()">Get Sharable Link</button></div>
				<div class="w3-container" style="word-wrap:break-word;" id="save_link"></div>
			</div>
			</div>
			<div id="warn_validate" class="w3-modal" onclick="this.style.display='none'">
				<div class="w3-modal-content w3-animate-zoom">
					<header class="w3-container w3-red">
						<span class="w3-closebtn">&times;</span>	
						<h2>Warning</h2>
					</header>
					<p>Shear Pressure must be calculated before saving.</p>
					<footer class="w3-red w3-panel">
						<button class="w3-button w3-white w3-closebtn w3-medium w3-left-align">OK</button>						
					</footer>
				</div>
			</div>
			
			