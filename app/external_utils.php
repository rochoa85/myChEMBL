<?
/*
 ===============

(c) 2012 EMBL European Molecular Biology Laboratories

This code is licensed under Version 2.0 of the Open Source Initiative Apache License.
URL: http://www.opensource.org/licenses/apache2.0.php

===============
*/

session_start();

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

function download_cmpdsearch($params){
        header('Content-Transfer-Encoding: none');
        header("Content-type: text/plain");
        include("../config/config.php");

        $search_type = $params[0];

        // Double check CHEMBL ID format
        if(preg_match("/^sss|sim$/i", $search_type, $matches) == 0){
                 echo "Search type not recognised";
                 exit(0);
        }

        // Default to substructure search
        $tmp_table = $_SESSION["tmp_table_$search_type"];
        $sort_col  = " cp.mw_freebase asc ";       
 
        $cols = array(
            'tmp.molregno',
            'md.chembl_id',
            'md.pref_name',
            'md.max_phase',
            'cp.mw_freebase',
            'cp.hba',
            'cp.hbd',
            'cp.psa',
            'cp.rtb',
            'cp.num_ro5_violations',
            'cp.acd_most_apka',
            'cp.acd_most_bpka',
            'cp.acd_logp',
            'cp.acd_logd',
            'cp.molecular_species',
            'cp.alogp',
            'cs.canonical_smiles'
        );

        if(preg_match("/^sim$/i", $search_type, $matches) == 1){
                array_unshift($cols,"tmp.sim as similarity");
                $sort_col = " tmp.sim desc ";
        } 

        header("Content-Disposition: attachment; filename=mychembl_cmpdsearch_$search_type.txt");

        $db = pg_connect("user=$db_user dbname=$db_name host=$db_host port=$db_port");
        
        $sql = "SELECT 
                 ".implode(", ", $cols)."
                FROM 
                  $tmp_table tmp
                  join molecule_dictionary md  on (md.molregno=tmp.molregno)
                  left outer join compound_structures cs on (md.molregno=cs.molregno)
                  left outer join compound_properties cp on (md.molregno=cp.molregno)
                ORDER BY
                  $sort_col";

        $result = pg_query($db, $sql);
                 if (!$result) {die("Error in SQL query: " . pg_last_error());}


        function clean_col_names($c){
            return strtoupper(preg_replace("/^\w+\sas\s/i", "", preg_replace("/^\w+\./", "", $c)));
        }

        echo implode("\t", array_map("clean_col_names", $cols)) . "\n";

        while($row = pg_fetch_row($result)) {
                echo implode("\t",  $row) . "\n";
        }

}

function download_bioactivity($params){

        header('Content-Transfer-Encoding: none');
        header("Content-type: text/plain");
        include("../config/config.php");

        $chembl_id = $params[0];

        // Double check CHEMBL ID format
        if(preg_match("/^chembl\d+$/i", $chembl_id, $matches) == 0){
                 echo "CHEMBL accession format nor recognised";
                 exit(0);
        }

	header('Content-Disposition: attachment; filename="mychembl_'.$chembl_id.'_bioactivity.txt');

        $db = pg_connect("user=$db_user dbname=$db_name host=$db_host port=$db_port");

        $cols = array(
            'md.molregno', 
            'md.chembl_id as molecule_chembl_id', 
            'md.pref_name', 
            'md.max_phase', 
            'md.structure_type', 
            'ac.activity_id', 
            'ac.published_type', 
            'ac.published_relation', 
            'ac.published_value', 
            'ac.published_units', 
            'ac.standard_type', 
            'ac.standard_relation', 
            'ac.standard_value', 
            'ac.standard_units',
            'ac.activity_comment',
            'ac.pchembl_value', 
            'a.assay_id', 
            'a.chembl_id as assay_chembl_id', 
            'a.assay_type', 
            'a.description', 
            's.src_description',
            'td.tid', 
            'td.chembl_id as target_chembl_id', 
            'td.pref_name', 
            'td.target_type', 
            'td.organism', 
            'd.doc_id',
            'd.chembl_id as document_chembl_id',
            'd.journal',
            'd.year',
            'd.volume',
            'd.issue',
            'd.title',
            'd.doc_type',
            'd.pubmed_id',
            'cs.canonical_smiles'
        );

	$sql = "SELECT 
                 ".implode(", ", $cols)."
                FROM 
                  molecule_dictionary md
                  left outer join compound_structures cs on (md.molregno=cs.molregno)
                  left outer join activities ac on (md.molregno = ac.molregno)
                  left outer join assays a on ( ac.assay_id = a.assay_id)
                  left outer join target_dictionary td on (td.tid = a.tid) 
                  left outer join source s on (a.src_id = s.src_id) 
                  left outer join docs d on (d.doc_id = a.doc_id) 
                WHERE 
                  md.chembl_id='$chembl_id'"; 
 					
	$result = pg_query($db, $sql);
 	         if (!$result) {die("Error in SQL query: " . pg_last_error());} 
 			
 
	function clean_col_names($c){
            return strtoupper(preg_replace("/^\w+\sas\s/i", "", preg_replace("/^\w+\./", "", $c)));
        }
		
        echo implode("\t", array_map("clean_col_names", $cols)) . "\n";	
	
        while($row = pg_fetch_row($result)) {
		echo implode("\t",  $row) . "\n";
	}	
}
?>
