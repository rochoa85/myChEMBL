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
    		<h2>Compound report</h2>
    		 <p>  	     
    	     <?
				if (!empty($_GET["id"])) {
					$query=$_GET["id"];
				}
				echo "<h3>$query</h3>";
				
				echo "<p><a href='https://www.ebi.ac.uk/chembldb/compound/inspect/$query'>ChEMBL website link</a></p>";				
				echo "<h5>Chemical representations</h5>";
	
            $db = pg_connect("user=$db_user dbname=$db_name host=$db_host port=$db_port");
			if (!$db) {die("Error in connection: " . pg_last_error());}
			
			// execute query
 			$sql = "SELECT cs.canonical_smiles, cs.standard_inchi, cs.standard_inchi_key, cs.molregno FROM compound_structures cs, molecule_dictionary md WHERE cs.molregno=md.molregno and md.chembl_id='$query'";			
 					
 			$result = pg_query($db, $sql);
 			if (!$result) {die("Error in SQL query: " . pg_last_error());} 
 			
 			$campos=array("Canonical SMILES","Standard InChI","Standard InChI-Key");
 			
 			echo "<p>"; 			
			while ($row = pg_fetch_array($result)) {
 				if (empty($row[canonical_smiles])){
 					echo '<b>No Results, please search again</b>';
 				}
				else{
					echo "<b>$campos[0]:</b> $row[canonical_smiles]<br/><br/>";
					echo "<b>$campos[1]:</b> $row[standard_inchi]<br/><br/>";
					echo "<b>$campos[2]:</b> $row[standard_inchi_key]";
					$molregno=$row[molregno];
				}
			} 
			echo "</p>";
			echo "<h5>Structure</h5>";
			echo "<p><img src='dispatcher.php/molecule_image/$query' width='250' height='250'/></p>";
			echo "<h5>Bioactivity data</h5>";
			

			// execute query
 			$sql = "SELECT a.assay_id, ac.standard_type, ac.standard_relation, ac.standard_value, ac.standard_units, td.chembl_id FROM compound_records cr, molecule_dictionary md, 
						target_dictionary td, assays a, activities ac WHERE 
						md.molregno = cr.molregno AND 
						cr.record_id = ac.record_id AND 
						ac.assay_id = a.assay_id AND 
						td.tid = a.tid AND
						ac.standard_type in ('IC50', 'Ki', 'EC50', 'Kd') AND
						md.chembl_id='$query'";	
						//a2t.confidence_score >= 7 AND
						//ac.standard_units = 'nM' AND		
 					
 			$result = pg_query($db, $sql);
 			if (!$result) {die("Error in SQL query: " . pg_last_error());} 
 			
 			$campos=array("Assay ID","Assay Type","Assay Relation","Value","Units","Target");
 			
 			echo "<table><tr>";
			foreach($campos as $campo){
				echo "<th>$campo</th>";	
			}
			echo "</tr>";
			
			while ($row = pg_fetch_array($result)) {
 				if (empty($row[assay_id])){
 					echo '<b>No Results, please search again</b>';
 				}
				else{
					echo "<tr>";
					echo "<td><a href='https://www.ebi.ac.uk/chembldb/assay/inspect/$row[assay_id]'>$row[assay_id]</a></td><td>$row[standard_type]<td>$row[standard_relation]</td><td>$row[standard_value]</td><td>$row[standard_units]</td><td>
					<a href='https://www.ebi.ac.uk/chembldb/target/inspect/$row[chembl_id]'>$row[chembl_id]</a></td>";
					echo "</tr>";
				}
			} 						
			echo "</table>";
			?>
			<form><input TYPE="button" VALUE="Back" onClick="history.go(-1);return true;"></form>
    	    
             </p>    			
		</section> 
			
    </div>
        
<? include "footer.php"; ?> 

