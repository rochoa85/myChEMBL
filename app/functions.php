<?php
 /*
===============

(c) 2012 EMBL European Molecular Biology Laboratories

This code is licensed under Version 2.0 of the Open Source Initiative Apache License.
URL: http://www.opensource.org/licenses/apache2.0.php 

===============
 */

function convertSMARTS($smarts)
{
	include("../config/config.php");
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

function convertMOL($molfile)
{
	include("../config/config.php");
        $db = pg_connect("user=$db_user dbname=$db_name host=$db_host port=$db_port");	
	$sql = "SELECT mol_from_ctab('$molfile'::cstring)";
 	$result = pg_query($db, $sql);
 	if (!$result) {die("Error in SQL query: " . pg_last_error());}       
	while ($row = pg_fetch_array($result)) {
 		if (empty($row[0])){
 			echo '<center><b>MOLFILE no valid</b></center>';
 		}
 		else{
 			$mol=$row[0];
 		}
   } 				
	return $mol;
}

function uriConversion($query){
	$charOriginal=array("!","#","$","&","'","(",")","*","+",",","/",":",";","=","?","@","[","]");
	$charChange=array("%21","%23","%24","%26","%27","%28","%29","%2A","%2B","%2C","%2F","%3A","%3B","%3D","%3F","%40","%5B","%5D");
	$newQuery=str_replace($charOriginal, $charChange, $query);
	return $newQuery;
}


function sss($searchOperator, $query, $molformat){
    include("../config/config.php");
    $db = pg_connect("user=$db_user dbname=$db_name host=$db_host port=$db_port");
    if (!$db) {die("Error in connection: " . pg_last_error());}
    
    $qry_md5 = md5($searchOperator.$query.$molformat);
    $table   = "octmp_sss_".$qry_md5;
    
    // If table exists return name
    $sql_table_exists = "SELECT  relname FROM pg_class r JOIN pg_namespace n ON (relnamespace = n.oid) WHERE relkind = 'r' AND n.nspname = 'public' and relname like '".$table."';";
    $qry_table_exists = pg_query($db, $sql_table_exists);
    $row = pg_fetch_row($qry_table_exists);
    
    // Results already exists
    if($row){
        // Extend life of the table
        $sql_update_tmp_table = "UPDATE octmp_summary SET table_created = now() WHERE table_name = '$row[0]'";
        pg_query($db, $sql_update_tmp_table);
        return $row[0];
    }
    
    // Create entry in octmp_summary
    $sql_load_octmp_summary = "INSERT INTO octmp_summary (table_name, table_created, query_md5) VALUES('$table', now(), '$qry_md5')";
    $qry_load_octmp_summary = pg_query($db, $sql_load_octmp_summary);
    if (!$qry_load_octmp_summary) {die("Error in SQL query: " . pg_last_error());}
    
    // Run sss command
    $sql = "CREATE TABLE $table AS SELECT DISTINCT mr.molregno,mr.m,md.chembl_id FROM mols_rdkit mr, molecule_dictionary md WHERE mr.m $searchOperator '$query'::$molformat AND mr.molregno=md.molregno";
    $result = pg_query($db, $sql);
    if (!$result) {die("Error in SQL query: " . pg_last_error());}

    // Return table name
    return $table;

}

function sim($fingerprint,$similarity, $query){
	include("../config/config.php");
	$db = pg_connect("user=$db_user dbname=$db_name host=$db_host port=$db_port");
	if (!$db) {die("Error in connection: " . pg_last_error());}

	$qry_md5 = md5($fingerprint.$similarity.$query);
	$table   = "octmp_sim_".$qry_md5;

	// If table exists return name
	$sql_table_exists = "SELECT  relname FROM pg_class r JOIN pg_namespace n ON (relnamespace = n.oid) WHERE relkind = 'r' AND n.nspname = 'public' and relname like '".$table."';";
	$qry_table_exists = pg_query($db, $sql_table_exists);
	$row = pg_fetch_row($qry_table_exists);

	// Results already exists
	if($row){
		// Extend life of the table
		$sql_update_tmp_table = "UPDATE octmp_summary SET table_created = now() WHERE table_name = '$row[0]'";
		pg_query($db, $sql_update_tmp_table);
		return $row[0];
	}

	// Create entry in octmp_summary
	$sql_load_octmp_summary = "INSERT INTO octmp_summary (table_name, table_created, query_md5) VALUES('$table', now(), '$qry_md5')";
	$qry_load_octmp_summary = pg_query($db, $sql_load_octmp_summary);
	if (!$qry_load_octmp_summary) {die("Error in SQL query: " . pg_last_error());}
    
	// Define query parameters
	$sim_query          = false;
	$fingerprint_method = false;
	$fingerprint_cols   = false;
	
	if($similarity=="Tanimoto") {
		$sim_query="tanimoto_sml";
	}elseif($similarity=="Dice") {
		$sim_query="dice_sml";
	}
		
	// execute query
	if($fingerprint=="Morgan") {
		$fingerprint_method = "morganbv_fp";
		$fingerprint_cols   = "mfp2";		
	}elseif($fingerprint=="MorganFeat") {
		$fingerprint_method = "featmorganbv_fp";
		$fingerprint_cols   = "ffp2";
	}elseif($fingerprint=="Torsion") {
		$fingerprint_method = "torsionbv_fp";
		$fingerprint_cols   = "torsionbv";
	}elseif($fingerprint=="Atom") {
		$fingerprint_method = "atompairbv_fp";
		$fingerprint_cols   = "atombv";
	}elseif($fingerprint=="RDKit") {
		$fingerprint_method = "rdkit_fp";
		$fingerprint_cols   = "rdkfp";
	}elseif($fingerprint=="Layered") {
		$fingerprint_method = "layered_fp";
		$fingerprint_cols   = "layeredfp";
	}elseif($fingerprint=="MACCS") {
		$fingerprint_method = "maccs_fp";
		$fingerprint_cols   = "maccsfp";
	}
		
	// Run sss command
	$sql = "CREATE TABLE $table AS 
	        SELECT DISTINCT mr.molregno,mr.m,$sim_query(fr.$fingerprint_cols,$fingerprint_method('$query'::mol)) sim, md.chembl_id 
	          FROM mols_rdkit mr, fps_rdkit fr, molecule_dictionary md 
 		     WHERE fr.$fingerprint_cols%$fingerprint_method('$query'::mol) 
	           AND mr.molregno=md.molregno 
	           AND mr.molregno=fr.molregno";
		
	$result = pg_query($db, $sql);
	if (!$result) {die("Error in SQL query: " . pg_last_error());}

	// Return table name
	return $table;

}

function deleteTmpTables(){
    include("../config/config.php");
    $db = pg_connect("user=$db_user dbname=$db_name host=$db_host port=$db_port"); 
    if (!$db) {die("Error in connection: " . pg_last_error());}

    // If table exists return name
    $sql_old_table = "SELECT table_name FROM octmp_summary WHERE table_created < ( now() - interval '20 minutes' );";
    $qry_old_table = pg_query($db, $sql_old_table);
    
    while ($row = pg_fetch_array($qry_old_table)) {
        $sql_delete_old_table = "DELETE FROM octmp_summary WHERE table_name like '$row[0]'";
        pg_query($db, $sql_delete_old_table);

        $sql_drop_old_table = "DROP TABLE $row[0]";
        pg_query($db, $sql_drop_old_table);
    }
}
?>
