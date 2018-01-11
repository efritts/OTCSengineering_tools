<form class="w3-container w3-row">
	<div class="w3-col l6 m6">
	<table>
		<tr>
			<td><label>Manufacturer</label></td>
			<td>
				<select name="oem" class="w3-select w3-border">
	  			<option value="Hydril">Hydril</option>
	  			<option value="Cameron">Cameron</option>
	  			<option value="NOV">NOV</option>
	 			<option value="Shaffer">Shaffer</option>
				</select>
			</td>
			<td></td>
		</tr>
		<tr>
			<td><label>Bore Diameter</label></td>
			<td><input class="w3-input w3-border w3-padding-0" type="text" id="bop_bore"></td>
			<td>in</td>
		</tr>
		<tr>
			<td><label>Internal Pressure rating</label></td>
			<td><input class="w3-input w3-border w3-padding-0" type="text" id="bop_rating"></td>
			<td>psi</td>
		</tr>
		<tr>
			<td><label>Operator Diameter</label></td>
			<td><input class="w3-input w3-border w3-padding-0" type="text" id="bop_opSize"></td>
			<td>in</td>
		</tr>
		<tr>
			<td><label>Closing Area</label></td>
			<td><input class="w3-input w3-border w3-padding-0" type="text" id="bop_closingarea"></td>
			<td>in<sup>2</sup></td>
		</tr>
		<tr>
			<td><label>Closing Ratio</label></td>
			<td><input class="w3-input w3-border w3-padding-0" type="text" id="bop_closingratio"></td>
			<td>in<sup>2</sup></td>
		</tr>
		<tr>
			<td><label>Minimum Closing Pressure</label></td>
			<td><input class="w3-input w3-border w3-padding-0" type="text" id="bop_minclose"></td>
			<td>psi</td>
		</tr>
		<tr>
			<td><label>Minimum Opening Pressure</label></td>
			<td><input class="w3-input w3-border w3-padding-0" type="text" id="bop_minopen"></td>
			<td>psi</td>
		</tr>
		<tr>
			<td><label>Piston Rod Diameter</label></td>
			<td><input class="w3-input w3-border w3-padding-0" type="text" id="bop_dia-piston"></td>
			<td>in<sup>2</sup></td>
		</tr>
		<tr>
			<td><label>Tail Rod Diameter</label></td>
			<td><input class="w3-input w3-border w3-padding-0" type="text" id="bop_dia-tail"></td>
			<td>in<sup>2</sup></td>
		</tr>
		<tr>
			<td class="w3-right-align"><input class="w3-check" type="checkbox"></td>
			<td><label class="w3-validate">Lock function</label> </td>
			<td></td>
		</tr>
		<!--show below if lock function -->
		<tr>
			<td><label>Minimum Lock Pressure</label></td>
			<td><input class="w3-input w3-border w3-padding-0" type="text" id="bop_minlock"></td>
			<td>psi</td>
		</tr>
		<tr>
			<td><label>Lock Function Rating</label></td>
			<td><input class="w3-input w3-border w3-padding-0" type="text" id="bop_lockrating"></td>
			<td>psi</td>
		</tr>
		<!--end conditional lock function fields -->
		<tr><td colspan="3"><label>Notes:</label></td></tr>
		<tr><td colspan="3"><textarea rows="4" cols="60"></textarea></td></tr>
		
	</table>

<?php
/*
 * ADD CONTENT
 * 
 * add a form field to include references to support BOP data
 */	
?>
	<hr />
	<p><button class="w3-btn">Add</button></p>
	</div>
	<div class="w3-col l6 m6"></div>
</form>
