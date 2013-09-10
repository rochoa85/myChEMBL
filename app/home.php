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
     
    		<h2>Welcome to myChEMBL</h2>
    		 <p>
            The aim of the myChEMBL Project is to make the process installing and running a local copy of the ChEMBL database as easy as possible. Additional goals of the myChEMBL Project included:
            </p>
              <ul>
                <li>The database we provide should be 'Chemically Aware', allowing users to run chemical substructure and similarity searches.</li>
                <li>The removal of any cost restrictions imposed to software licensing, so only freely available open-source software should be used in the project.</li>            
            </ul>
            <p>
            In order to achieve each of these goals we have created a Virtual Machine, which contains the following:
            </p>
            <ul>
              <li>PostgreSQL version of the ChEMBL database (Release: <?= $chembl_version ?>).</li>
              <li>The RDKit Chemical Cartridge, which is an open source software library and adds 'Chemical-Awareness' to a PostgreSQL database.</li>
              <li>A web application (this web application), which demonstrates some of the functionality of the RDKit Chemical Cartridge, such as chemical structure searching and chemical structure property calculations.*</li>
            </ul>
            <p>
            *Note you do not need to use the web application and can connect directly to the database using PostgreSQL client.
            </p>
            
            <p>
             Your feedback will help us determine how successful this project has been and also help drive the projects future direction. So please get in <a href="https://www.ebi.ac.uk/chembldb/group/contact">get in touch</a> and share with us your myChEMBL comments and questions.
            </p>
         

    	     <h3>myChEMBL Web Application Overview</h3>    			

             <p>
             The myChEMBL web application is used to expose some of the functionality provided by the RDKit Chemical Cartridge. It allows a user to draw or upload a chemical structures, which can then be used in <a href="structure.php">chemical structure searches</a> against the ChEMBL database or in <a href="property.php">chemical property calculations</a>. 
             Programmatic access to each of these services is also provided through a <a href="ws.php">RESTful Web Service</a>               
             </p>
             
    		 <p style="text-align:center;">
               <img width="65%" src="<?=$app2base?>static/images/app/structureVM_border.png" alt="">
             </p>
    		
    			
    	     <h3>License</h3>    			
             <p>        
				This code is licensed under Version 2.0 of the Open Source Initiative Apache License.<br/>
				URL: <a href="http://www.opensource.org/licenses/apache2.0.php">http://www.opensource.org/licenses/apache2.0.php</a><br/>
				<br/>
				(c) 2012 EMBL European Molecular Biology Laboratories	
             </p>    
             			
		</section> 		
    </div>
    
 <? include "footer.php"; ?> 
