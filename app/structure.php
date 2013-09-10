<?php
/*
 ===============

(c) 2012 EMBL European Molecular Biology Laboratories

This code is licensed under Version 2.0 of the Open Source Initiative Apache License.
URL: http://www.opensource.org/licenses/apache2.0.php

===============
*/
?>

<? include "header.php"; ?>

<script type="text/javascript" src="<?=$app2base?>static/js/jsme/jsme.nocache.js"></script>    

<div id="content" role="main" class="grid_24 clearfix">

	<section>
		<h2>Structure Search</h2>
		<p>
			The section of the myChEMBL system allows users to conduct a
			substructure or similarity based against the molecules store in
			<?= $chembl_version ?>
			database <br /> <br /> <select id="structure-search-type">
				<option value="ignore">Please choose search type...</option>
				<option value="sub">Substructure</option>
				<option value="sim">Similarity</option>
			</select>
		</p>

		<!-- Substructure Start -->
		<div id="substructure-input-div">
			<h3>Substructure Search</h3>
			<p>
				In this section the user can choose between substructure and exact
				search, using as input format SMILES strings, SMARTS queries and MOL
				files stored in your computer. <br /> <b>NOTE:</b> The exact search
				only works for SMILES and MOL formats. 
				<br /> 
				<br /> 
				<select id="substructure-search-input">
					<option value="ignore">Please choose input type...</option>
					<option value="substructure-draw">Draw structure</option>
					<option value="substructure-upload">Upload file</option>
				</select>
			</p>
			<div style="display:none; text-align:center;" id="substructure_section">
            
                <div id="struct_sketch_sub"></div>
            
				<form name="marSketchSub" method="get"
					action="substructure_results.php" class="formulario">
					<input type="text" style="display: none" id="marSmartsSub"
						name="chemical" size="50"><br /> <br /> 1. Select one kind of
					search: <input type="radio" name="match" checked="checked"
						id="subSub" value="subs" /> Substructure <input type="radio"
						name="match" id="exSub" value="exact" /> Exact <br /> <br /> <input
						type="hidden" name="format" id="formSpecialSub" value="SMARTS" />
					<input TYPE="submit" align="left" VALUE="Search"
						onClick="export_mol('sub');">
				</form>
				
			</div>

			<div style="display: none" id="entrada">
				<p>
					1. Please select one of the following formats for the input: <select
						name="format" id="format" onclick="string();">
						<option value="None" selected="selected" class="listheader">---</option>
						<option value="SMILES" class="listheader">SMILES</option>
						<option value="MOL" class="listheader">MOL</option>
						<option value="ARTS" class="listheader">SMARTS</option>
					</select>
				</p>
			</div>

			<div style="display: none" id="stringSMILES">
				<form name="rdkit" method="get" action="substructure_results.php"
					class="formulario">
					2. Select one kind of search: <input type="radio" name="match"
						checked="checked" id="sub" value="subs" /> Substructure <input
						type="radio" name="match" id="ex" value="exact" /> Exact<br /> <br />
					3. Enter the string of characters (for <b>SMILES</b> queries): <input
						type="text" name="chemical" value="Oc1ccc(\C=C\C=O)cc1O" size="50" /><br />
					<br /> <input type="hidden" name="format" value="SMILES" /> <input
						type="submit" name="accion" value="Run SMILES" id="runDisplay" />
				</form>
			</div>

			<div style="display: none" id="stringSMARTS">
				<form name="smarts" method="get" action="substructure_results.php"
					class="formulario">
					<input type="hidden" name="match" value="subs" /> 2. Enter the
					string of characters (for <b>SMARTS</b> queries): <input
						type="text" name="chemical"
						value="[#6;X4]-1-[#6](=[#8])-[#7]-[#7]-[#6]-1=[#8]" size="50" /><br />
					<br /> <input type="hidden" name="format" value="SMARTS" /> <input
						type="submit" name="accion" value="Run SMARTS" id="runDisplay" />
				</form>
			</div>

			<div style="display: none" id="stringMOL">
				<form name="molfile" method="post" action="substructure_results.php"
					enctype="multipart/form-data" class="formulario">
					2. Select one kind of search: <input type="radio" name="match"
						checked="checked" id="sub" value="subs" /> Substructure <input
						type="radio" name="match" id="ex" value="exact" /> Exact<br /> <br />
					3. Select a <b>MOL</b> file from your computer: <input type="file"
						name="datafile" id="datafile" size="40" /><br /> <br /> <input
						type="hidden" name="format" value="MOL" /> <input type="submit"
						name="accion" value="Run MOL" id="runDisplay" />
				</form>
			</div>
		</div>

		<!-- Substructure End -->



		<!-- Similarity Start -->

		<div id="similarity-input-div">

			<h3>Similarity Search</h3>
			<p>
				In this section the user can run similarity searches, selecting
				different class of fingerprints, and select between similarity
				coefficients. The input formats are SMILES strings, SMARTS queries
				or MOL files stored in your computer. <br /> <b>NOTE:</b> The
				Layered fingerprint is experimental and takes a lot of time for
				running. 
				<br /> 
				<br /> 
				<select id="similarity-search-input">
					<option value="ignore">Please choose input type...</option>
					<option value="similarity-draw">Draw structure</option>
					<option value="similarity-upload">Upload file</option>
				</select>				
			</p>

			<div style="display: none; text-align: center;" id="similarity_section">
	
	            <div id="struct_sketch_sim"></div>
	
				<form name="marSketchSim" method="get"
					action="similarity_results.php" class="formulario">
					<input type="text" style="display: none" id="marSmartsSim"
						name="chemical" size="50"><br /> <br /> 1. Select one kind of
					fingerprints (Morgan (ECFP-like) by default): <select
						name="fingerprint" id="fingerprint">
						<option value="Morgan" selected="selected" class="listheader">Morgan</option>
						<option value="MorganFeat" class="listheader">Morgan features</option>
						<option value="Torsion" class="listheader">Topological-Torsion</option>
						<option value="Atom" class="listheader">Atom-Pair</option>
						<option value="RDKit" class="listheader">RDKit</option>
						<option value="Layered" class="listheader">Layered (exp)</option>
						<option value="MACCS" class="listheader">MACCS</option>
					</select><br /> <br /> 2. Select one similarity coefficient
					(Tanimoto by default): <select name="similarity" id="similarity">
						<option value="Tanimoto" selected="selected" class="listheader">Tanimoto</option>
						<option value="Dice" class="listheader">Dice</option>
					</select><br /> <br /> <input type="hidden" name="format"
						value="SMARTS" /> <input TYPE="submit" align="left" VALUE="Search"
						onClick="export_mol('sim');">
				</form>
			</div>

			<div style="display: none" id="entradaSim">
				<p>
					1. Please select one of the following formats for the input: <select
						name="format" id="formatSim" onclick="Simstring();">
						<option value="None" selected="selected" class="listheader">---</option>
						<option value="SMILESSim" class="listheader">SMILES</option>
						<option value="MOLSim" class="listheader">MOL</option>
						<option value="ARTSSim" class="listheader">SMARTS</option>
					</select>
				</p>
			</div>

			<div style="display: none" id="stringSMILESSim">
				<form name="rdkit" method="get" action="similarity_results.php"
					class="formulario">
					2. Select one kind of fingerprints (Morgan (ECFP-like) by default):
					<select name="fingerprint" id="fingerprint">
						<option value="Morgan" selected="selected" class="listheader">Morgan</option>
						<option value="MorganFeat" class="listheader">Morgan features</option>
						<option value="Torsion" class="listheader">Topological-Torsion</option>
						<option value="Atom" class="listheader">Atom-Pair</option>
						<option value="RDKit" class="listheader">RDKit</option>
						<option value="Layered" class="listheader">Layered (exp)</option>
						<option value="MACCS" class="listheader">MACCS</option>
					</select><br /> <br /> 3. Select one similarity coefficient
					(Tanimoto by default): <select name="similarity" id="similarity">
						<option value="Tanimoto" selected="selected" class="listheader">Tanimoto</option>
						<option value="Dice" class="listheader">Dice</option>
					</select><br /> <br /> 4. Enter the string of characters (for <b>SMILES</b>
					queries): <input type="text" name="chemical"
						value="CC1=CC(C)(C)Nc2cc3oc(=O)cc(C(F)(F)F)c3cc21" size="50"><br />
					<br /> <input type="hidden" name="format" value="SMILES" /> <input
						type="submit" name="accion" value="Run SMILES" id="runDisplay">
				</form>
			</div>

			<div style="display: none" id="stringSMARTSSim">
				<form name="smarts" method="get" action="similarity_results.php"
					class="formulario">
					2. Select one kind of fingerprints (Morgan (ECFP-like) by default):
					<select name="fingerprint" id="fingerprint">
						<option value="Morgan" selected="selected" class="listheader">Morgan</option>
						<option value="MorganFeat" class="listheader">Morgan features</option>
						<option value="Torsion" class="listheader">Topological-Torsion</option>
						<option value="Atom" class="listheader">Atom-Pair</option>
					</select><br /> <br /> 3. Select one similarity coefficient
					(Tanimoto by default): <select name="similarity" id="similarity">
						<option value="Tanimoto" selected="selected" class="listheader">Tanimoto</option>
						<option value="Dice" class="listheader">Dice</option>
					</select><br /> <br /> 4. Enter the string of characters (for <b>SMARTS</b>
					queries): <input type="text" name="chemical"
						value="[#6;X4]-1-[#6](=[#8])-[#7]-[#7]-[#6]-1=[#8]" size="50"><br />
					<br /> <input type="hidden" name="format" value="SMARTS" /> <input
						type="submit" name="accion" value="Run SMARTS" id="runDisplay">
				</form>
			</div>

			<div style="display: none" id="stringMOLSim">
				<form name="molfile" method="post" action="similarity_results.php"
					enctype="multipart/form-data" class="formulario">
					2. Select one kind of fingerprints (Morgan (ECFP-like) by default):
					<select name="fingerprint" id="fingerprint">
						<option value="Morgan" selected="selected" class="listheader">Morgan</option>
						<option value="MorganFeat" class="listheader">Morgan features</option>
						<option value="Torsion" class="listheader">Topological-Torsion</option>
						<option value="Atom" class="listheader">Atom-Pair</option>
					</select><br /> <br /> 3. Select one similarity coefficient
					(Tanimoto by default): <select name="similarity" id="similarity">
						<option value="Tanimoto" selected="selected" class="listheader">Tanimoto</option>
						<option value="Dice" class="listheader">Dice</option>
					</select><br /> <br /> 4. Select a <b>MOL</b> file from your
					computer: <input type="file" name="datafile" id="datafile"
						size="40"><br /> <br /> <input type="hidden" name="format"
						value="MOL" /> <input type="submit" name="accion" value="Run MOL"
						id="runDisplay">
				</form>
			</div>
		</div>

		<!-- Similarity End -->

	</section>

</div>

<? include "footer.php"; ?>

