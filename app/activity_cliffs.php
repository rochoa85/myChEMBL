<?php
 /*
===============

(c) 2012 EMBL European Molecular Biology Laboratories

This code is licensed under Version 2.0 of the Open Source Initiative Apache License.
URL: http://www.opensource.org/licenses/apache2.0.php 

===============
 */
?>

<? include "header.php"; ?> 
	           
    <div id="content" role="main" class="grid_24 clearfix">
    		   
	   <section>
     
    		<h2>Activity Cliffs</h2>
    		 <p>
            In this section the user can calculate Activity Cliffs for assays (IC50, EC50 or Ki) or targets (Ki) within the ChEMBL database.
          </p>
         <div id="assay">
				<form name="assayCliffs" method="get" action="activity_report.php" class="formulario">
					1. Enter an assay ChEMBL ID <input type="text" name="assay" value="CHEMBL1040691" size="20"/>
					<input type="submit" name="accion" value="Assay Activity Cliffs" id="runDisplay"/>
				</form>
			</div>
			<br/>
			<div id="target">
				<form name="targetCliffs" method="get" action="target_report.php" class="formulario">
					2. Enter a target  ChEMBL ID <input type="text" name="target" value="CHEMBL260" size="20"/>
					<input type="submit" name="accion" value="Target Activity Cliffs" id="runDisplay"/>
				</form>
			</div>
          <h3>ChEMBL NTD assay datasets</h3>
			<p>
            Based on the NTD screening datasets deposited at the <a href="https://www.ebi.ac.uk/chemblntd">ChEMBL NTD website</a>, the following are NTD assays suitable for Activity Cliffs calculation.
            <br/><br/><b>Please select the desired dataset:</b>
          </p>          
          <div id="NTDassays">
				<form name="ntd" method="get" action="activity_report.php" class="formulario">
					<input type="radio" name="assay" value="CHEMBL1040691" checked="checked"/> Novartis Malaria Screening (P.falciparum 3D7)<br/>
					<input type="radio" name="assay" value="CHEMBL1040692"/> Novartis Malaria Screening (P.falciparum W2)<br/>
					<input type="radio" name="assay" value="CHEMBL730079"/> St Jude Malaria Screening (P.falciparum 3D7)<br/>
					<input type="radio" name="assay" value="CHEMBL730080"/> St Jude Malaria Screening (P.falciparum K1)<br/>
					<input type="radio" name="assay" value="CHEMBL1789906"/> Novartis Malaria Screening (P.yoelii)<br/>
					<input type="radio" name="assay" value="CHEMBL1862746"/> DNDi HAT Dataset (T.brucei SBRI 427)<br/>
					<input type="radio" name="assay" value="CHEMBL2095144"/> DNDi HAT Dataset (T.brucei STIB 795)<br/>
					<input type="radio" name="assay" value="CHEMBL1863510"/> DNDi Chagas Dataset (T.cruzi)<br/>
					<input type="radio" name="assay" value="CHEMBL2092592"/> Harvard Malaria Screening (P.berghei)<br/>
					<input type="radio" name="assay" value="CHEMBL2092593"/> Harvard Malaria Screening (P.falciparum 3D7)<br/>
					<input type="radio" name="assay" value="CHEMBL2092594"/> Harvard Malaria Screening (P.falciparum Dd2)<br/><br/>
					<input type="submit" name="accion" value="Activity Cliffs" id="runDisplay"/>
				</form>
			</div>	
             			
		</section> 		
    </div>
    
 <? include "footer.php"; ?> 

