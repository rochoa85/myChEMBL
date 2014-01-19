<?php
 /*
===============

(c) 2012 EMBL European Molecular Biology Laboratories

This code is licensed under Version 2.0 of the Open Source Initiative Apache License.
URL: http://www.opensource.org/licenses/apache2.0.php 

===============
 */
?>

<? include_once("functions.php"); ?>

<? include "header.php"; ?> 

	<div id="content" role="main" class="grid_24 clearfix">
	<section>
		<h2>Activity Cliffs calculation</h2>

		<h3>Assay details</h3>

		<p>
		
		<?
		
			if (!empty($_GET["assay"])) {
				$assay=$_GET["assay"];
			}
			
						
			$db = pg_connect("user=$db_user dbname=$db_name host=$db_host port=$db_port");
			if (!$db) {die("Error in connection: " . pg_last_error());}
			
			// execute query
 			$sql = "SELECT a.description, a.assay_organism, a.assay_type 
 						FROM assays a
 						WHERE a.chembl_id='$assay'";			
 					
 			$result = pg_query($db, $sql);
 			if (!$result) {die("Error in SQL query: " . pg_last_error());} 
 						
 						
 			$campos=array("ChEMBL ID (web link)","Description","Organism","Type");
 			
 			echo "<p>"; 			
			while ($row = pg_fetch_array($result)) {
 				if (empty($row[description])){
 					echo '<b>No Results, please search again</b>';
 				}
				else{
					echo "
			  <table>
           <tr>
           <th width='120px'>$campos[0]</th>
           <td width='550px'><a href='https://www.ebi.ac.uk/chembldb/assay/inspect/$assay'>$assay</a></td>
           </tr>
           <tr>
           <th>$campos[1]</th>
           <td>$row[description]</td>
           </tr>
           <tr>
           <th>$campos[2]</th>
           <td>$row[assay_organism]</td>
           </tr>
           <tr>
           <th>$campos[3]</th>
           <td>$row[assay_type]</td>
           </tr>
           </table>";
				}
			}  
			echo "</p>";
			echo "<h3>Assay activity statistics</h3>";
			$sql_count = "SELECT count(md.molregno)
									FROM molecule_dictionary md, compound_records cr, activities ac, assays a
									WHERE md.molregno = cr.molregno AND cr.record_id = ac.record_id AND ac.assay_id = a.assay_id
									AND a.chembl_id='$assay' AND ac.standard_type='IC50' AND ac.standard_units='nM' 
									AND ac.standard_relation = '=' AND ac.standard_value IS NOT NULL"; 			
 			
			$result_count = pg_query($db, $sql_count);
 			if (!$result_count) {die("Error in SQL query: " . pg_last_error());} 
 			
			$IC50=pg_fetch_row($result_count); 			
 			
			$sql_count = "SELECT count(md.molregno)
									FROM molecule_dictionary md, compound_records cr, activities ac, assays a
									WHERE md.molregno = cr.molregno AND cr.record_id = ac.record_id AND ac.assay_id = a.assay_id
									AND a.chembl_id='$assay' AND ac.standard_type='Ki' AND ac.standard_units='nM' 
									AND ac.standard_relation = '=' AND ac.standard_value IS NOT NULL"; 			
 			
			$result_count = pg_query($db, $sql_count);
 			if (!$result_count) {die("Error in SQL query: " . pg_last_error());} 
 			
			$Ki=pg_fetch_row($result_count); 			
	 			
 			$sql_count = "SELECT count(md.molregno)
									FROM molecule_dictionary md, compound_records cr, activities ac, assays a
									WHERE md.molregno = cr.molregno AND cr.record_id = ac.record_id AND ac.assay_id = a.assay_id
									AND a.chembl_id='$assay' AND ac.standard_type='EC50' AND ac.standard_units='nM' 
									AND ac.standard_relation = '=' AND ac.standard_value IS NOT NULL"; 			
 			
			$result_count = pg_query($db, $sql_count);
 			if (!$result_count) {die("Error in SQL query: " . pg_last_error());} 
 			
			$EC50=pg_fetch_row($result_count);		
 			
			$campos=array("Number of IC50","Number of Ki","Number of EC50");
 			
 			echo "<p>";
 			echo "<table>
           <tr>
           <th width='120px'>$campos[0]</th>
           <td width='550px'>$IC50[0]</td>
           </tr>
           <tr>
           <th>$campos[1]</th>
           <td>$Ki[0]</td>
           </tr>
           <tr>
           <th>$campos[2]</th>
           <td>$EC50[0]</td>
           </tr>
           </table>";
			echo "</p>";
			echo "<h3>Search details</h3>";
			echo "<form name='assayCliff' method='get' action='assay_cliffs.php' class='formulario'>
					1. Select one kind of activity:
					<input type='radio' name='act' checked='checked' id='IC50' value='IC50'/> IC50
					<input type='radio' name='act' id='Ki' value='Ki'/> Ki
					<input type='radio' name='act' id='EC50' value='EC50'/> EC50
					<br /><br />
					2. Select one kind of fingerprint:
					<select name='fingerprint' id='fingerprint'>
						<option value='Morgan' selected='selected' class='listheader'>Morgan</option>
						<option value='MorganFeat' class='listheader'>Morgan features</option>
						<option value='Torsion' class='listheader'>Topological-Torsion</option>
						<option value='Atom' class='listheader'>Atom-Pair</option>
					</select><br/><br />
					3. Enter the similarity threshold:
					<input type='text' name='threshold' value='0.7' size='10'><br/><br/> 
					<input type='hidden' name='assay' value='$assay' /> 
					<input type='submit' value='Find activity cliffs' />
					</form>";
			
			?>	
			<br /><br />
		<form><input TYPE="button" VALUE="Back" onClick="history.go(-1);return true;"></form>
		</p>
		</section>
	</div>


<? include "footer.php"; ?>