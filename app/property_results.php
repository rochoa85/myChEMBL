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
    		<h2>Property results</h2>
    		
    	     <h3>Query details</h3>    			

             <p>
             
             <?
			if (!empty($_GET["chemical"])) {
				$query=$_GET["chemical"];
				$queryFormat=$_GET["format"];
			}
			
			if (!empty($_FILES["datafile"])) {   			
   			$queryMol=$_FILES["datafile"];
   			$queryFormat=$_POST["format"];
			}
			
			if($queryFormat=="SMILES"){
				echo "<b>Query:</b> $query<br/>";
			}					
			if($queryFormat=="SMARTS"){
				echo "<b>Query:</b> $query<br/>";
				$query=convertSMARTS($query);	
			} 			
 			
			if($queryFormat=="MOL"){
				$molecule=file_get_contents($queryMol["tmp_name"]);
				$query=convertMOL($molecule);
				echo "<b>Query (MolFile):</b> ".$queryMol["name"]."<br/>";
			} 	
				?>		
             </p>
             
    			
    	     <h3>Results</h3>    			
             <p>
             <?
            $db = pg_connect("user=$db_user dbname=$db_name host=$db_host port=$db_port");
            if (!$db) {die("Error in connection: " . pg_last_error());}
			
			//echo "<b>SUMMARY RESULTS</b><br/><br/>";
			// execute query
 			$sql = "SELECT mol_amw('$query') as amw, mol_logp('$query') as logp, mol_hba('$query') as hba, mol_hbd('$query') as hbd, mol_numatoms('$query') as numatoms, 
 					mol_numheavyatoms('$query') as numheavyatoms, mol_numrotatablebonds('$query') as numrotatablebonds, mol_numheteroatoms('$query') as numheteroatoms, 
 					mol_numrings('$query') as numrings, mol_tpsa('$query') as tpsa, mol_numaromaticrings('$query') as numaromarings, mol_numaliphaticrings('$query') as numalipharings,
 					mol_numsaturatedrings('$query') as numsaturarings,mol_numaromaticheterocycles('$query') as numaromahet,mol_numaliphaticheterocycles('$query') as numaliphahet,
 					mol_numsaturatedheterocycles('$query') as numsaturahet,mol_numaromaticcarbocycles('$query') as numaromacarbo,mol_numaliphaticcarbocycles('$query') as numaliphacarbo,
 					mol_numsaturatedcarbocycles('$query') as numsaturacarbo,mol_fractioncsp3('$query') as fracsp3";			
 					
 			$result = pg_query($db, $sql);
 			if (!$result) {die("Error in SQL query: " . pg_last_error());}       

 			// iterate over result set
 			// print each row
 			$cont=1;
 			$campos=array("Molecular Weight","LogP","Lipinski H-Bond Acceptors", "Lipinski H-Bond Donors","Number of atoms","Number of heavy atoms","Number of rotatable bonds",
 						"Number of Heteroatoms","Number of Rings","Topological Polar Surface Area","Number of Aromatic Rings","Number of Aliphatic Rings","Number of Saturated Rings",
 						"Number of Aromatic Heterocycles","Number of Aliphatic Heterocycles","Number of Saturated Heterocycles","Number of Aromatic Carbocycles","Number of Aliphatic Carbocycles",
 						"Number of Saturated Carbocycles","Fraction of carbons sp3 hybridized");
 			 			
			
			while ($row = pg_fetch_array($result)) {
 				if (empty($row[amw])){
 					echo '<b>No Results, please search again</b>';
 				}
				else{
					echo "<b>$campos[0]:</b> $row[amw]<br/>";
					echo "<b>$campos[1]:</b> $row[logp]<br/>";
					echo "<b>$campos[2]:</b> $row[hba]<br/>";
					echo "<b>$campos[3]:</b> $row[hbd]<br/>";
					echo "<b>$campos[4]:</b> $row[numatoms]<br/>";
					echo "<b>$campos[5]:</b> $row[numheavyatoms]<br/>";
					echo "<b>$campos[6]:</b> $row[numrotatablebonds]<br/>";
					echo "<b>$campos[7]:</b> $row[numheteroatoms]<br/>";
					echo "<b>$campos[8]:</b> $row[numrings]<br/>";
					echo "<b>$campos[9]:</b> $row[tpsa]<br/>";
					echo "<b>$campos[10]:</b> $row[numaromarings]<br/>";
					echo "<b>$campos[11]:</b> $row[numalipharings]<br/>";
					echo "<b>$campos[12]:</b> $row[numsaturarings]<br/>";
					echo "<b>$campos[13]:</b> $row[numaromahet]<br/>";
					echo "<b>$campos[14]:</b> $row[numaliphahet]<br/>";
					echo "<b>$campos[15]:</b> $row[numsaturahet]<br/>";
					echo "<b>$campos[16]:</b> $row[numaromacarbo]<br/>";
					echo "<b>$campos[17]:</b> $row[numaliphacarbo]<br/>";
					echo "<b>$campos[18]:</b> $row[numsaturacarbo]<br/>";
					echo "<b>$campos[19]:</b> $row[fracsp3]";
				}
			} 
 			 
			// free memory
 			pg_free_result($result);			
			
			// close connection
 			pg_close($db);	
             ?> 
             </p>    			
		</section> 
			
    </div>
    
<? include "footer.php"; ?> 

