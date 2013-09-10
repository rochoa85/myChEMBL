<?php
 /*
===============

(c) 2012 EMBL European Molecular Biology Laboratories

This code is licensed under Version 2.0 of the Open Source Initiative Apache License.
URL: http://www.opensource.org/licenses/apache2.0.php 

===============
 */
?>

<? include_once("../config/config.php"); ?>

<?php

// This is the API, 2 possibility show the user list, and show a specifique user by action.
//header('Content-Disposition: attachment; filename="users.json"');
header("Content-type: application/json");

///////////// Function ///////////////////////

function convertSMARTS($smarts)
{
    global $db_user,$db_name,$db_host,$db_port;
    $db = pg_connect("user=$db_user dbname=$db_name host=$db_host port=$db_port");
    if (!$db) {die("Error in connection: " . pg_last_error());}
    
	$sql = "SELECT mol_from_smarts('$smarts')";
 	$result = pg_query($db, $sql);
 	if (!$result) {die("Error in SQL query: " . pg_last_error());}       
	while ($row = pg_fetch_array($result)) {
 		if (empty($row[0])){
 			echo '<center><b>SMARTS no valid</b></center>';
 		}
 		else{
 			$mol=$row[0];
 		}
   } 				
	return $mol;
}

///////////// Function ///////////////////////

function similarity($moltest, $finger, $method)
{
    $info = array();
	$total = array();
	
	global $db_user,$db_name,$db_host,$db_port;
    $db = pg_connect("user=$db_user dbname=$db_name host=$db_host port=$db_port");
	if (!$db) {die("Error in connection: " . pg_last_error());}

	// section to select between a set of fingerprints
	if($finger=="Morgan"){
		$fp="mfp2";
		$fpTech="morganbv_fp";
	}elseif($finger=="MorganFeat") {
		$fp="ffp2";
		$fpTech="featmorganbv_fp";
	}elseif($finger=="Torsion") {
		$fp="torsionbv";
		$fpTech="torsionbv_fp";
	}elseif($finger=="Atom") {
		$fp="atombv";
		$fpTech="atompairbv_fp";
	}elseif($finger=="RDKit") {
		$fp="rdkfp";
		$fpTech="rdkit_fp";
	}elseif($finger=="Layered") {
		$fp="layeredfp";
		$fpTech="layered_fp";
	}elseif($finger=="MACCS") {
		$fp="maccsfp";
		$fpTech="maccs_fp";
	}
	
	// Section to select the method
	if($method=="Tanimoto") {
		$met="tanimoto_sml";
	}elseif($method=="Dice") {
		$met="dice_sml";
	}
 	
 	$sql = "SELECT DISTINCT mr.molregno,mr.m,$met(fr.$fp,$fpTech('$moltest'::mol)) sim, md.chembl_id FROM mols_rdkit mr, fps_rdkit fr, molecule_dictionary md 
 				where fr.$fp%$fpTech('$moltest'::mol) and mr.molregno=md.molregno and mr.molregno=fr.molregno ORDER BY sim DESC";
 				
 	$result = pg_query($db, $sql);
 	if (!$result) {die("Error in SQL query: " . pg_last_error());}
 	

 	while ($row = pg_fetch_array($result)) {
 		if (empty($row[molregno])){
 			//echo '<center><b>No Results, please search again</b></center>';
 			$info = array("Molregno" => "NO", "ChEMBL_ID" => "NO", "Similarity" => "NO");
 		}
 		else{
 			$info = array("Molregno" => "$row[molregno]", "ChEMBL_ID" => "$row[chembl_id]", "Similarity" => "$row[sim]");
 			//echo "<b>Molecule:</b> " . $row[m] . "<br/>";
 		}
 		array_push($total, $info);
   }
   
  return $total;
}

///////////// Function ///////////////////////

function substructure($molTest,$type,$flag)
{
   $info = array();
	$total = array();
	
	if($type=="subs") {
		$searchSymbol="@>";
		$molType="qmol";
	}elseif($type=="exact") {
		$searchSymbol="@=";
		if($flag=="smiles") {
			$molType="mol";
		}elseif($flag=="smarts") {
			$molType="mol";
			$molTest=convertSMARTS($molTest);
		}
	}
	
	global $db_user,$db_name,$db_host,$db_port;
    $db = pg_connect("user=$db_user dbname=$db_name host=$db_host port=$db_port");
	if (!$db) {die("Error in connection: " . pg_last_error());}
 	
 	$sql = "SELECT DISTINCT mr.molregno,mr.m,md.chembl_id FROM mols_rdkit mr, molecule_dictionary md WHERE mr.m $searchSymbol '$molTest'::$molType AND mr.molregno=md.molregno";
 				
 	$result = pg_query($db, $sql);
 	if (!$result) {die("Error in SQL query: " . pg_last_error());}
 	

 	while ($row = pg_fetch_array($result)) {
 		if (empty($row[molregno])){
 			//echo '<center><b>No Results, please search again</b></center>';
 			$info = array("Molregno" => "NO", "ChEMBL_ID" => "NO", "SMILES" => "NO");
 		}
 		else{
 			$info = array("Molregno" => "$row[molregno]", "ChEMBL_ID" => "$row[chembl_id]", "SMILES" => "$row[m]");
 			//echo "<b>Molecule:</b> " . $row[m] . "<br/>";
 		}
 		array_push($total, $info);
   } 			
  return $total;
}

///////////// Function ///////////////////////

function properties($query)
{
   $info = array();
	$total = array();
	
    global $db_user,$db_name,$db_host,$db_port;	
    $db = pg_connect("user=$db_user dbname=$db_name host=$db_host port=$db_port");
	if (!$db) {die("Error in connection: " . pg_last_error());}
 	
 	$sql = "SELECT mol_amw('$query') as amw, mol_logp('$query') as logp, mol_hba('$query') as hba, mol_hbd('$query') as hbd, mol_numatoms('$query') as numatoms, 
 					mol_numheavyatoms('$query') as numheavyatoms, mol_numrotatablebonds('$query') as numrotatablebonds, mol_numheteroatoms('$query') as numheteroatoms, 
 					mol_numrings('$query') as numrings, mol_tpsa('$query') as tpsa, mol_numaromaticrings('$query') as numaromarings, mol_numaliphaticrings('$query') as numalipharings,
 					mol_numsaturatedrings('$query') as numsaturarings,mol_numaromaticheterocycles('$query') as numaromahet,mol_numaliphaticheterocycles('$query') as numaliphahet,
 					mol_numsaturatedheterocycles('$query') as numsaturahet,mol_numaromaticcarbocycles('$query') as numaromacarbo,mol_numaliphaticcarbocycles('$query') as numaliphacarbo,
 					mol_numsaturatedcarbocycles('$query') as numsaturacarbo,mol_fractioncsp3('$query') as fracsp3";
 				
 	$result = pg_query($db, $sql);
 	if (!$result) {die("Error in SQL query: " . pg_last_error());}
 	

 	while ($row = pg_fetch_array($result)) {
 		if (empty($row[amw])){
 			//echo '<center><b>No Results, please search again</b></center>';
 			$info = array("Molecular Weight" => "NO", "LogP" => "NO", "Lipinski H-Bond Acceptors" => "NO", "Lipinski H-Bond Donors" => "NO",
 			"Number of atoms" => "NO", "Number of heavy atoms" => "NO", "Number of rotatable bonds" => "NO", "Number of Heteroatoms" => "NO",
 			"Number of Rings" => "NO", "Topological Polar Surface Area" => "NO","Number of Aromatic Rings" => "NO","Number of Aliphatic Rings" => "NO","Number of Saturated Rings" => "NO",
 			"Number of Aromatic Heterocycles" => "NO","Number of Aliphatic Heterocycles" => "NO","Number of Saturated Heterocycles" => "NO","Number of Aromatic Carbocycles" => "NO","Number of Aliphatic Carbocycles" => "NO",
 			"Number of Saturated Carbocycles" => "NO","Fraction of carbons sp3 hybridized" => "NO");
 		}
 		else{
 			$info = array("Molecular Weight" => "$row[amw]", "LogP" => "$row[logp]", "Lipinski H-Bond Acceptors" => "$row[hba]", "Lipinski H-Bond Acceptors" => "$row[hba]", "Lipinski H-Bond Donors" => "$row[hbd]",
 			"Number of atoms" => "$row[numatoms]", "Number of heavy atoms" => "$row[numheavyatoms]", "Number of rotatable bonds" => "$row[numrotatablebonds]", "Number of Heteroatoms" => "$row[numheteroatoms]",
 			"Number of Rings" => "$row[numrings]", "Topological Polar Surface Area" => "$row[tpsa]","Number of Aromatic Rings" => "$row[numaromarings]","Number of Aliphatic Rings" => "$row[numalipharings]","Number of Saturated Rings" => "$row[numsaturarings]",
 			"Number of Aromatic Heterocycles" => "$row[numaromahet]","Number of Aliphatic Heterocycles" => "$row[numaliphahet]","Number of Saturated Heterocycles" => "$row[numsaturahet]","Number of Aromatic Carbocycles" => "$row[numaromacarbo]","Number of Aliphatic Carbocycles" => "$row[numaliphacarbo]",
 			"Number of Saturated Carbocycles" => "$row[numsaturacarbo]","Fraction of carbons sp3 hybridized" => "$row[fracsp3]");
 			//echo "<b>Molecule:</b> " . $row[m] . "<br/>";
 		}
 		array_push($total, $info);
   }
   
  return $total;
}

///////////// MAIN ///////////////////////

$possible_url = array("substructure", "similarity", "properties");

$value = "An error has occurred";

if (isset($_GET["action"]) && in_array($_GET["action"], $possible_url))
{
  switch ($_GET["action"])
    {
      case "substructure":
      	if (isset($_GET["smiles"])){
        		$variable=$_GET["smiles"];
        		$flag="smiles";
        	}elseif(isset($_GET["smarts"])){
        		$variable=$_GET["smarts"];
        		$flag="smarts";
        	}
        	
        	// Second section depending of the optional inputs
        	if(isset($variable) && isset($_GET["type"])) {
        		$value=substructure($variable,$_GET["type"],$flag);
        	}else {
        		if (isset($variable)){        				
        			$value = substructure($variable,"subs",$flag);
        		}else{
        			$value = "Missing argument";
        		}
        	}
        break;
        
      case "similarity":
	      //First section to recognize if the input is smiles or smarts
			if(isset($_GET["smiles"])){
				$variable=$_GET["smiles"];	
			}elseif(isset($_GET["smarts"])){
				$variable=convertSMARTS($_GET["smarts"]);
			}       
      
      	//Second section depending of the optional inputs
        if (isset($variable) && isset($_GET["fingerprint"]) && isset($_GET["method"])){
        		$value = similarity($variable,$_GET["fingerprint"],$_GET["method"]);
        }else{
        		if (isset($variable) && isset($_GET["fingerprint"])){
        				$value = similarity($variable,$_GET["fingerprint"],"Tanimoto");
        		}elseif(isset($variable) && isset($_GET["method"])) {
        				$value = similarity($variable,"Morgan",$_GET["method"]);
        		}else{
						if (isset($variable)){        				
        					$value = similarity($variable,"Morgan","Tanimoto");
        				}else{
        					$value = "Missing argument";
        				}
        		}
        }
        break;
        
        case "properties":
      	if (isset($_GET["smiles"])){
        		$variable=$_GET["smiles"];
        		$value=properties($variable);
        	}elseif(isset($_GET["smarts"])){
        		$variable=convertSMARTS($_GET["smarts"]);
        		$value=properties($variable);
        	}else{
        		$value = "Missing argument";
        	}
        break;
    }
}

exit(json_encode($value));

?>

