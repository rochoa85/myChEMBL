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

		<h3>Target details</h3>

		<p>
		
		<?			
			if (!empty($_GET["target"])) {
				$target=$_GET["target"];
			}			
			
			
			$db = pg_connect("user=$db_user dbname=$db_name host=$db_host port=$db_port");
			if (!$db) {die("Error in connection: " . pg_last_error());}
			
			// execute query
 			$sql = "SELECT td.pref_name, td.organism, td.target_type 
 						FROM target_dictionary td
 						WHERE td.chembl_id='$target'";			
 					
 			$result = pg_query($db, $sql);
 			if (!$result) {die("Error in SQL query: " . pg_last_error());} 
 						
 						
 			$campos=array("ChEMBL ID (web link)","Preferred Name","Organism","Type");
 			
 			echo "<p>"; 			
			while ($row = pg_fetch_array($result)) {
 				if (empty($row[pref_name])){
 					echo '<b>No Results, please search again</b>';
 				}
				else{
					echo "
								<table>
           <tr>
           <th width='120px'>$campos[0]</th>
           <td width='550px'><a href='https://www.ebi.ac.uk/chembldb/target/inspect/$target'>$target</a></td>
           </tr>
           <tr>
           <th>$campos[1]</th>
           <td>$row[pref_name]</td>
           </tr>
           <tr>
           <th>$campos[2]</th>
           <td>$row[organism]</td>
           </tr>
           <tr>
           <th>$campos[3]</th>
           <td>$row[target_type]</td>
           </tr>
           </table>";
				}
			}
			
			echo "</p>";
			echo "<h3>Target activity statistics </h3>";		
 			
			$sql_count = "SELECT count(md.molregno)
									FROM molecule_dictionary md, compound_records cr, activities ac, assays a, target_dictionary td
									WHERE md.molregno = cr.molregno AND cr.record_id = ac.record_id AND ac.assay_id = a.assay_id
									AND a.tid = td.tid
									AND td.chembl_id='$target' AND ac.standard_type='Ki' AND ac.standard_units='nM' 
									AND ac.standard_relation = '=' AND ac.standard_value IS NOT NULL"; 			
 			
			$result_count = pg_query($db, $sql_count);
 			if (!$result_count) {die("Error in SQL query: " . pg_last_error());} 
 			
			$Ki=pg_fetch_row($result_count); 			
	 					
 			
			$campos=array("Number of Ki");
 			echo "<p>";
 			echo "
								<table>
           <tr>
           <th width='120px'>$campos[0]</th>
           <td width='550px'>$Ki[0]</td>
           </tr>
         </table>";
			echo "</p>";
			
			echo "<h3>Search details</h3>";
 			echo "<form name='targetCliff' method='get' action='target_cliffs.php' class='formulario'>
					1. Select one kind of fingerprint:
					<select name='fingerprint' id='fingerprint'>
						<option value='Morgan' selected='selected' class='listheader'>Morgan</option>
						<option value='MorganFeat' class='listheader'>Morgan features</option>
						<option value='Torsion' class='listheader'>Topological-Torsion</option>
						<option value='Atom' class='listheader'>Atom-Pair</option>
					</select><br/><br />
					2. Enter the similarity threshold:
					<input type='text' name='threshold' value='0.7' size='10'><br/><br/> 
					<input type='hidden' name='target' value='$target' />
					<input type='submit' value='Find activity cliffs' />
					</form>";
			?>
			<br/><br />
		<form><input TYPE="button" VALUE="Back" onClick="history.go(-1);return true;"></form>
		</p>
		</section>
	</div>


<? include "footer.php"; ?>s
