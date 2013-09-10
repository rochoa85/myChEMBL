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
    		<h2>Tutorial</h2>
    		<h3>Using structure search</h3>
    		 <p>In this section, the user can select between substructure and similiraty search</p>
			<p style="text-align:center;"><img src="<?=$app2base?>static/images/app/generalStructure.png" align="center" width="60%"/></p>
    	     <p>For both strategies, there is the option to draw the molecule using Marvin Sketch, or insert the molecules using three different formats: SMILES, SMARTS and MOL files.
    	     <br/>For substructure search, the user has the option to select between <i>substructure</i> and <i>exact</i> methodologies.</p> 
			<p style="text-align:center;"><img src="<?=$app2base?>static/images/app/substructure.png" align="center" width="60%"/></p>
			<p><br/>For similarity search, the user can select between a set of fingerprints, and similarity coefficients like <i>tanimoto</i> and <i>dice</i>.</p>
			<p style="text-align:center;"><img src="<?=$app2base?>static/images/app/similarity.png" align="center" width="60%"/></p>
			<h3>Using property calculation</h3>
			<p><br/>Additionally, the user can calculate molecular properties using the same input formats as before. This is an example of some available properties</p>
			<p style="text-align:center;"><img src="<?=$app2base?>static/images/app/property.png" align="center" width="60%"/></p>
			<h3>Using web services</h3>			
			<p><br/>All the functionalities mentioned before can be executed through some RESTful web services. To achieve that there are some examples explaining how to build the URI queries.</p>
			<p style="text-align:center;"><img src="<?=$app2base?>static/images/app/webServices.png" align="center" width="60%"/></p>
		</section> 
			
    </div>

 <? include "footer.php"; ?> 
