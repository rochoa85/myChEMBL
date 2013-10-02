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

<? 
   if (!empty($_GET["id"])) {
       $query=$_GET["id"];
   }
?>      
 
    <div id="content" role="main" class="grid_24 clearfix">
    		   
	   <section>
                       <?
                        
                       echo "<h2>$query Compound Report</h2>";


			$db = pg_connect("user=$db_user dbname=$db_name host=$db_host port=$db_port");
			if (!$db) {
				die("Error in connection: " . pg_last_error());
			}


                       $sql = "SELECT 
                                 md.molregno,
                                 md.pref_name,
                                 md.max_phase,
                                 string_agg(distinct sn.synonyms, ' | ') as synonyms,
                                 string_agg(distinct tn.synonyms, ' | ') as tradenames
                               FROM
                                 molecule_dictionary md, 
                                 molecule_synonyms sn,
                                 molecule_synonyms tn
                               WHERE
                                 md.molregno=sn.molregno
                                 AND md.molregno=tn.molregno
                                 AND md.chembl_id='$query'
                                 AND sn.syn_type not like 'TRADE_NAME'
                                 AND tn.syn_type like 'TRADE_NAME'
                               GROUP BY
                                 md.molregno,
                                 md.pref_name,
                                 md.max_phase";

                        $result = pg_query($db, $sql);
                        if (!$result) {die("Error in SQL query: " . pg_last_error());}

                        $sum_row = pg_fetch_array($result);

                        echo "<br/><h3>$query Summary</h3>";

                        $sql = "SELECT 
                                  cs.canonical_smiles,
                                  cs.standard_inchi, 
                                  cs.standard_inchi_key
                                FROM 
                                  compound_structures cs, 
                                  molecule_dictionary md 
                                WHERE 
                                  cs.molregno=md.molregno 
                                AND
                                  md.chembl_id='$query'";                

                        $result = pg_query($db, $sql);
                        if (!$result) {die("Error in SQL query: " . pg_last_error());}

                        $str_row = pg_fetch_array($result);

                        echo "

<script type='text/javascript'>
    var canonical_smiles = '".$str_row[canonical_smiles]."';
    var standard_inchi   = '".$str_row[standard_inchi]."';
</script>

                              <table>
                               <tr>
                                 <th width='150px'>Name</th>
                                 <td width='550px'>$sum_row[pref_name]</td>
                                 <td rowspan='7' style='text-align:center'>
                                   <img src='dispatcher.php/molecule_image/$query' width='250' height='250'/>
                                   <br/><a href='https://www.ebi.ac.uk/chembldb/compound/inspect/$query'>ChEMBL website link</a>
                                 </td>
                               </tr>
                               <tr>
                                 <th>Synonyms</th>
                                 <td>$sum_row[synonyms]</td>
                               </tr>
                               <tr>
                                 <th>Trade Names</th>
                                 <td>$sum_row[tradenames]</td>
                               </tr>
                               <tr>
                                 <th>Highest Development Phase</th>
                                 <td>$sum_row[max_phase]</td>
                               </tr>
                               <tr>
                                 <th>Canonical Smiles</th>
                                 <td>";
                                 
                                 $str_max_length = 75; 
                                 if(strlen($str_row[canonical_smiles])> $str_max_length){
                                     echo substr($str_row[canonical_smiles],0,$str_max_length)."....&nbsp;&nbsp;[<span class='str-popup' onclick='alert(canonical_smiles);'>Show Full Canonical Smiles</span>]";
                                 } else{
                                     echo "$str_row[canonical_smiles]";
                                 }
                               
                               echo"
                                 </td>
                               </tr>
                               <tr>
                                 <th>Standard InChI</th>
                               
                                 <td>";

                                 if(strlen($str_row[standard_inchi])> $str_max_length){
                                     echo substr($str_row[standard_inchi],0, $str_max_length)."....&nbsp;&nbsp;[<span class='str-popup' onclick='alert(standard_inchi);'>Show Full Standard InChI</span>]";
                                 } else{
                                     echo "$str_row[standard_inchi]";
                                 }

                               echo"
                                 </td>
                               </tr>
                               <tr>
                                 <th>Standard InChI Key</th>
                                 <td>$str_row[standard_inchi_key]</td>
                               </tr>
                             </table>";



                        echo "<br/><h3>$query Properties</h3>";

                        $sql = "SELECT
                                  cp.mw_freebase, 
                                  cp.alogp, 
                                  cp.hba,
                                  cp.hbd,
                                  cp.psa,
                                  cp.num_ro5_violations,
                                  cp.molecular_species,
                                  cp.acd_most_apka,
                                  cp.acd_most_bpka,
                                  cp.acd_logd,
                                  cp.acd_logp
                                FROM 
                                  compound_properties cp, 
                                  molecule_dictionary md 
                                WHERE 
                                  cp.molregno=md.molregno 
                                AND
                                  md.chembl_id='$query'";

                        $result = pg_query($db, $sql);
                        if (!$result) {die("Error in SQL query: " . pg_last_error());}

                        $cp_row = pg_fetch_array($result);

                        
                        echo "<table>
                               <tr>
                                 <th>Molecular Weight Freebase</th>
                                 <th>ALogP</th>
                                 <th>HBA</th>
                                 <th>HBD</th>
                                 <th>PSA</th>
                                 <th>Number RO5 Violations</th>
                                 <th>ACD Acidic pKa</th>
                                 <th>ACD Basic pKa</th>
                                 <th>ACD LogP</th>
                                 <th>ACD LogD</th>
                                 <th>Molecular Species</th>
                               </tr>
                               <tr>
                                 <td>$cp_row[mw_freebase]</td>
                                 <td>$cp_row[alogp]</td>
                                 <td>$cp_row[hba]</td>
                                 <td>$cp_row[hbd]</td>
                                 <td>$cp_row[psa]</td>
                                 <td>$cp_row[num_ro5_violations]</td>
                                 <td>$cp_row[acd_most_apka]</td>
                                 <td>$cp_row[acd_most_bpka]</td>
                                 <td>$cp_row[acd_logd]</td>
                                 <td>$cp_row[acd_logp]</td>
                                 <td>$cp_row[molecular_species]</td>
                               </tr>
                             </table>";


			echo "<br/><h3>$query Bioactivity Data</h3>";
                        echo "<p>";
                        echo "Maximum of 100 rows displayed in table below<span style='float:right;'><button id='btn-download-bioactivity' value='$query'>Download All Bioactivity Data</button></span>";
	

			// execute query
	                $sql = "SELECT 
                                  a.chembl_id as assay_chembl_id,
                                  a.description, 
                                  a.assay_type,
                                  ac.standard_type, 
                                  ac.standard_relation, 
                                  ac.standard_value, 
                                  ac.standard_units,
                                  ac.activity_comment, 
                                  td.chembl_id as target_chembl_id,
                                  td.pref_name
                                FROM 
                                  molecule_dictionary md
                                  left outer join compound_structures cs on (md.molregno=cs.molregno)
                                  left outer join activities ac on (md.molregno = ac.molregno)
                                  left outer join assays a on ( ac.assay_id = a.assay_id)
                                  left outer join target_dictionary td on (td.tid = a.tid) 
                                  left outer join source s on (a.src_id = s.src_id) 
                                  left outer join docs d on (d.doc_id = a.doc_id) 
                                WHERE 
                                  md.chembl_id='$query'
                                LIMIT 100"; 
 					
 			$result = pg_query($db, $sql);
 			if (!$result) {die("Error in SQL query: " . pg_last_error());} 
 			
 			$campos=array("Assay ChEMBL ID", "Assay Description", "Assay Type", "Standard Type", "Standard Relation", "Standard Value", "Standard Units", "Activity Comment", "Target ChEMBL ID", "Target Name");
 			
 			echo "<table><tr>";
			foreach($campos as $campo){
				echo "<th>$campo</th>";	
			}
			echo "</tr>";
			
			while ($row = pg_fetch_array($result)) {
					echo "<tr>
					        <td><a href='https://www.ebi.ac.uk/chembl/assay/inspect/$row[assay_chembl_id]'>$row[assay_chembl_id]</a></td>
                                                <td>$row[description]</td>
                                                <td>$row[assay_type]</td>
                                                <td>$row[standard_type]</td>
                                                <td>$row[standard_relation]</td>
                                                <td>$row[standard_value]</td>
                                                <td>$row[standard_units]</td>
                                                <td>$row[activity_comment]</td>
                                                <td><a href='https://www.ebi.ac.uk/chembl/target/inspect/$row[target_chembl_id]'>$row[target_chembl_id]</a></td>
                                                <td>$row[pref_name]</td>
	                                      </tr>";
			} 						
			echo "</table></p>";
			?>
    	    
             </p>    			
		</section> 
			
    </div>
        
<? include "footer.php"; ?> 

