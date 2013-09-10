<?
/*
 ===============

(c) 2012 EMBL European Molecular Biology Laboratories

This code is licensed under Version 2.0 of the Open Source Initiative Apache License.
URL: http://www.opensource.org/licenses/apache2.0.php

===============
*/

function molecule_image($params){

	header("Content-type: image/PNG");
	include("../config/config.php");

	$chembl_id = $params[0];
	$image_not_found_file = "$app2base/static/images/app/inf.png";

	// Double check CHEMBL ID format
	if(preg_match("/^chembl\d+$/i", $chembl_id, $matches) == 0){
		header('Content-Length: ' . filesize($image_not_found_file));
		readfile($image_not_found_file);
		exit(0);
	}

	// Get image from database
	$db = pg_connect("user=$db_user dbname=$db_name host=$db_host port=$db_port");
	$sqlImage= "SELECT mp.image from mol_pictures mp, molecule_dictionary md where md.molregno=mp.molregno and md.chembl_id=upper('$chembl_id')";
	$result_p = pg_query($db, $sqlImage);
	$row = pg_fetch_array($result_p);
	 
	// Print image
	if($row){
		print(pg_unescape_bytea($row[image]));
	} else {
		header('Content-Length: ' . filesize($image_not_found_file));
		readfile($image_not_found_file);
		exit(0);
	}
}
?>
