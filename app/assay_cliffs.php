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
		<h2>Activity Cliffs results</h2>

		<h3>Query Details</h3>

		<p>
			<?
			if (!empty($_GET["assay"])) {
				$query=$_GET["assay"];
			}
						
			if (!empty($_GET["threshold"])) {
				$threshold=$_GET["threshold"];
			}			
			
			if (!empty($_GET["act"])) {
				$activity=$_GET["act"];
			}
			
			if (!empty($_GET["fingerprint"])) {
				$fingerprint=$_GET["fingerprint"];
			}
			
			if($fingerprint=="Morgan") {
				$fingerprint_cols   = "mfp2";		
			}elseif($fingerprint=="MorganFeat") {
				$fingerprint_cols   = "ffp2";
			}elseif($fingerprint=="Torsion") {
				$fingerprint_cols   = "torsionbv";
			}elseif($fingerprint=="Atom") {
				$fingerprint_cols   = "atombv";
			}			
			
			echo "<table>
           <tr>
           <th width='120px'>Assay ID</th>
           <td width='550px'>$query</td>
           </tr>
           <tr>
           <th>Similarity Threshold</th>
           <td>$threshold</td>
           </tr>
           <tr>
           <th>Fingerprint</th>
           <td>$fingerprint</td>
           </tr>
           <tr>
           <th>Activity Type</th>
           <td>$activity</td>
           </tr>
           </table>";
					
			$sql_activity="CREATE TEMP TABLE activity_data AS (
						SELECT md.molregno, ac.standard_value, fr.$fingerprint_cols
						FROM molecule_dictionary md, compound_records cr, activities ac, assays a, fps_rdkit fr
						WHERE md.molregno = cr.molregno AND cr.record_id = ac.record_id AND ac.assay_id = a.assay_id AND md.molregno = fr.molregno
						AND a.chembl_id='$query' AND ac.standard_type='$activity' AND ac.standard_units='nM' 
						AND ac.standard_relation = '=' AND ac.standard_value IS NOT NULL)";
				
				
			$db = pg_connect("user=$db_user dbname=$db_name host=$db_host port=$db_port");
			if (!$db) {die("Error in connection: " . pg_last_error());}
			
				// set tanimoto threshold
				$sql_p = "SELECT 'c1ccc1'::mol";
				$result_p = pg_query($db, $sql_p);
				
				$sql_p2 = "SET rdkit.tanimoto_threshold=$threshold";
				$result_p2 = pg_query($db, $sql_p2);
				
				// creating the temporary table
				$result_temp = pg_query($db, $sql_activity);
				
				$sql_index = "CREATE INDEX fps_idx ON activity_data USING gist($fingerprint_cols)";
				$result_index = pg_query($db, $sql_index);
			
			// execute query
			
 			$sql = "SELECT DISTINCT *,(pact1-pact2)/dist disparity,pact2-pact1 dact FROM ( 
							SELECT md1.chembl_id cid1, mr1.m smiles1, md2.chembl_id cid2, mr2.m smiles2, dist,
							-1*log(ad1.standard_value*1e-9) pact1,
							-1*log(ad2.standard_value*1e-9) pact2	
							FROM ( 
								SELECT fp1.molregno id1,fp2.molregno id2,
								1.0-tanimoto_sml(fp1.$fingerprint_cols,fp2.$fingerprint_cols) dist
								FROM activity_data AS fp1
								CROSS JOIN activity_data AS fp2
								WHERE fp1.$fingerprint_cols%fp2.$fingerprint_cols and fp1.molregno!=fp2.molregno
							) cliff_pairs
							join molecule_dictionary md1 on (id1=md1.molregno)
							join molecule_dictionary md2 on (id2=md2.molregno)
							join mols_rdkit mr1 on (id1=mr1.molregno)
							join mols_rdkit mr2 on (id2=mr2.molregno)
							join activity_data ad1 on (id1=ad1.molregno)
							join activity_data ad2 on (id2=ad2.molregno) 
							WHERE dist>0
						) tmp
						WHERE pact1>=pact2 AND (pact1-pact2)>.1
						ORDER BY disparity desc";			
 					
 			$result = pg_query($db, $sql);
 			if (!$result) {die("Error in SQL query: " . pg_last_error());}
 				 			
 			echo "<h3>Activity cliffs for $query</h3>";
 			echo "<p><b>Explanation:</b> Disparity is the final measure, and is given by: (Activity difference)/(Similarity Difference).
 			In this case the greater the Disparity value is, the better the activity cliff, because a small molecular change is
 			affecting in a substantial way the activity performance</p>";
 			$check=pg_fetch_row($result);
 			if (empty($check[0])){	 			
 					echo '<p><b>There are not cliff pairs for this query</b></p>';
 			}
 			else { 			
	 			$campos=array("Molecule 1","Molecule 2","Disparity","Similarity difference","Activity Mol1","Activity Mol2","Activity difference");
	 			echo "<table><tr>";
				foreach($campos as $campo){
					echo "<th>$campo</th>";	
				}
				echo "</tr>";
				
				while ($row = pg_fetch_array($result)) {
	 				
	 					echo "<tr>";
						//round($row[sim],2)
						$distScore=round($row[dist],3);
						$pact1Score=round($row[pact1],3);
						$pact2Score=round($row[pact2],3);
						$disparityScore=round($row[disparity],3);
						$dactScore=round($row[dact],3);
						echo "<td><img src='dispatcher.php/molecule_image/$row[cid1]' width='150' height='150'/><br/><a href='report.php?id=$row[cid1]'>$row[cid1]</a></td>
						<td><img src='dispatcher.php/molecule_image/$row[cid2]' width='150' height='150'/><br/><a href='report.php?id=$row[cid2]'>$row[cid2]</a></td>
						<td>$disparityScore</td><td>$distScore</td><td>$pact1Score</td><td>$pact2Score</td><td>$dactScore</td>";
						echo "</tr>";
	 				
				}
	 			echo "</table>";
			}			
			?>
			<form><input TYPE="button" VALUE="Back" onClick="history.go(-1);return true;"></form>
		</p>
	</div>


<? include "footer.php"; ?> 