<?php
/*
 ===============

(c) 2012 EMBL European Molecular Biology Laboratories

This code is licensed under Version 2.0 of the Open Source Initiative Apache License.
URL: http://www.opensource.org/licenses/apache2.0.php

===============
*/

// Starting a new session
session_start();
?>

<? include_once("functions.php"); ?>

<? include "header.php"; ?>

<div id="content" role="main" class="grid_24 clearfix">

	<section>
		<h2>Substructure results</h2>

		<h3>Query details</h3>

		<p>

			<?
			if (!empty($_GET["chemical"])) {
				$query=$_GET["chemical"];
				$queryFormat=$_GET["format"];
				$match=$_GET["match"];
			}

			if (!empty($_FILES["datafile"])) {
				$queryMol=$_FILES["datafile"];
				$queryFormat=$_POST["format"];
				$match=$_POST["match"];
			}

			if($match=="subs"){
				$searchOperator="@>";
				$molformat="qmol";
				$searchType="Substructure";
			}
			elseif($match=="exact") {
					$searchOperator="@=";
					$molformat="mol";
					$searchType="Exact";
				}
					

				if($queryFormat=="MOL"){
				$molecule=file_get_contents($queryMol["tmp_name"]);
				$query=convertMOL($molecule);
				echo "<b>Query (MolFile):</b> ".$queryMol["name"]."<br/>";
				echo "<b>Search Type:</b> $searchType";
			}else{
				echo "<b>Query:</b> $query <br/>";
				echo "<b>Search Type:</b> $searchType";
			}
			?>
		</p>


		<h3>Results</h3>
		<p>
			<?
			$db = pg_connect("user=$db_user dbname=$db_name host=$db_host port=$db_port");
			if (!$db) {
				die("Error in connection: " . pg_last_error());
			}

			$pagenum=$_GET["pagenum"];

			if (empty($pagenum)){
				$_SESSION['tmp_table_sss'] = sss($searchOperator, $query, $molformat);
				deleteTmpTables();
				$pagenum = 1;
			}

			$sqlCont = "SELECT count(*) FROM ".$_SESSION['tmp_table_sss'];
			$resultCont = pg_query($db,$sqlCont);
			$rowOne = pg_fetch_row($resultCont);
			$rows = $rowOne[0];
							
			if(!$resultCont || $rows == 0){
				echo '<b>No Results, please search again</b>';
			} else {
				// Number of results
				$page_rows = 11;
				$last = ceil($rows/$page_rows);

				if ($pagenum < 1){
					$pagenum = 1;
				}elseif ($pagenum > $last){
					$pagenum = $last;
				}

				//This sets the range to display in our query
				$max = 'LIMIT ' .$page_rows." OFFSET ".($pagenum-1)*$page_rows;

				$sql_p = "SELECT DISTINCT molregno,m,chembl_id FROM ".$_SESSION['tmp_table_sss']." $max";
				$result_p = pg_query($db, $sql_p);

				if (!$result_p) {
					die("Error in SQL query: " . pg_last_error());
				}

				$cont=1;
				echo "<table><tr>";

				while ($row = pg_fetch_array($result_p)) {

					if (empty($row[molregno])){
						echo '<b>No Results, please search again</b>';
						echo "</tr>";
					}else {
					    if ($cont <= 5){
						    echo "<td><img src='dispatcher.php/molecule_image/$row[chembl_id]' width='150' height='150'/><br/>
						    <a href='report.php?id=$row[chembl_id]'>$row[chembl_id]</a><br/></td>";
					    } else {
							echo "</tr><tr>";
							$cont=0;
						}
						$cont=$cont+1;
 					}
				}

				echo "</table>";
				echo "</p><p>";
				echo "Page <b>$pagenum</b> of <b>$last</b> <br/>";

				if ($pagenum == 1) {
				} else {
					$query=uriConversion($query);
					echo " <a href='{$_SERVER['PHP_SELF']}?match=$match&chemical=$query&format=$queryFormat&pagenum=1' > <<-First</a> ";
					echo "    ";
					$previous = $pagenum-1;
					echo " <a href='{$_SERVER['PHP_SELF']}?match=$match&chemical=$query&format=$queryFormat&pagenum=$previous' > <-Previous</a> ";
				}

				echo " ---- ";

				if ($pagenum == $last) {
				} else {
					$query=uriConversion($query);
					$next = $pagenum+1;
					echo " <a href='{$_SERVER['PHP_SELF']}?match=$match&chemical=$query&format=$queryFormat&pagenum=$next' >Next -></a> ";
					echo "    ";
					echo " <a href='{$_SERVER['PHP_SELF']}?match=$match&chemical=$query&format=$queryFormat&pagenum=$last' >Last ->></a> ";
				}
			} 
			// close connection
			pg_close($db);
			?>
		</p>
	</section>

</div>

<? include "footer.php"; ?>

