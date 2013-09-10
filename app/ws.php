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
    		<h2>Web Services</h2>
    	     
    	     <h3>Substructure Searches</h3>  
    	     <p>
    	   	In this section you can find examples to run a simple URI query to retrieve substructure and exact searches.<br/>
				<b>Categories:</b> action, smiles, smarts, type (subs, exact).  	     
    	     </p>
				    	     
    	     
    	     <h5>SMILES Based</h5>
    	     <p>
    	     <?
				$command="/sbin/ifconfig wlan0 | grep 'inet addr:' | cut -d: -f2 | awk '{ print $1}'";
				$localIP = exec ($command);
				if(empty($localIP)){
					$command="/sbin/ifconfig eth0 | grep 'inet addr:' | cut -d: -f2 | awk '{ print $1}'";
					$localIP = exec ($command);
				
					if(empty($localIP)){
						$localIP = "localhost";	
					}
				}
				echo "Example: <a href='http://$localIP/openchembl/rest/api_chembl.php?action=substructure&smiles=CC1=CC(C)(C)Nc2cc3oc(=O)cc(C(F)(F)F)c3cc21&type=exact'>
				http://$localIP/openchembl/rest/api_chembl.php?action=substructure&smiles=CC1=CC(C)(C)Nc2cc3oc(=O)cc(C(F)(F)F)c3cc21&type=exact</a>";
				?>
             </p>    	     
    	     
    	     <h5>SMARTS Based</h5>
    	     <p>
    	     <?
				echo "Example: <a href='http://$localIP/openchembl/rest/api_chembl.php?action=substructure&smarts=[%236;X4]-1-[%236](=[%238])-[%237]-[%237]-[%236]-1=[%238]&type=subs'>
				http://$localIP/openchembl/rest/api_chembl.php?action=substructure&smarts=[%236;X4]-1-[%236](=[%238])-[%237]-[%237]-[%236]-1=[%238]&type=subs</a>";
				?> 
             </p>    	

    	     <h3>Similarity Searches</h3>    			
				<p>
				In this section you can find examples to run a simple URI query to retrieve similarity searches, selecting different class of fingerprints and similarity coefficients.<br/>
				<b>Categories:</b> action, smiles, smarts, fingerprint (Morgan, MorganFeat, Torsion, Atom, RDKit, Layered, MACCS), 	method (Tanimoto, Dice).				
				</p>
				
				<h5>SMILES Based</h5>
    	     	<p>
    	     	<?
    	     	echo "Example: <a href='http://$localIP/openchembl/rest/api_chembl.php?action=similarity&smiles=C(=O)C1=CCnCC1&fingerprint=MACCS&method=Dice'>
			http://$localIP/openchembl/rest/api_chembl.php?action=similarity&smiles=C(=O)C1=CCnCC1&fingerprint=MACCS&method=Dice</a>";
    	     	?>
    	     	</p>				
				
				<h5>SMARTS Based</h5>
    	     	<p>
    	     <?
					echo "Example: <a href='http://$localIP/openchembl/rest/api_chembl.php?action=similarity&smarts=[%236;X4]-1-[%236](=[%238])-[%237]-[%237]-[%236]-1=[%238]&fingerprint=Morgan&method=Tanimoto'>
					http://$localIP/openchembl/rest/api_chembl.php?action=similarity&smarts=[%236;X4]-1-[%236](=[%238])-[%237]-[%237]-[%236]-1=[%238]&fingerprint=Morgan&method=Tanimoto</a>";
			?>
    	     	</p>				
				
    	     <h3>Property Calculations</h3>    			
				<p>
				In this section you can find examples to run a simple URI query to retrieve molecular properties for any molecule.<br/>
				<b>Categories:</b> action, smiles, smarts.			
				</p>
				
				<h5>SMILES Based</h5>
    	     	<p>
    	     	<?
    	     	echo "Example: <a href='http://$localIP/openchembl/rest/api_chembl.php?action=properties&smiles=C(=O)C1=CCnCC1'>
			http://$localIP/openchembl/rest/api_chembl.php?action=properties&smiles=C(=O)C1=CCnCC1</a>";
    	     	?>
    	     	</p>
             
   			<h5>SMARTS Based</h5>
    	     	<p>
    	     	<?
    	     echo "Example: <a href='http://$localIP/openchembl/rest/api_chembl.php?action=properties&smarts=[%236;X4]-1-[%236](=[%238])-[%237]-[%237]-[%236]-1=[%238]'>
			http://$localIP/openchembl/rest/api_chembl.php?action=properties&smarts=[%236;X4]-1-[%236](=[%238])-[%237]-[%237]-[%236]-1=[%238]</a>";
    	     	?>
    	     	</p>
    	     	
				<h3>Client</h3>
				<p>  	     	
    	     	<a href="#" onclick="python();">Check the PYTHON Client</a>
			<div style="display:none" id="pycode">			
			<pre><code style="font-size:11px">
#! /usr/bin/env python

import urllib2
import urllib
import json

##########################################################################
"""Functions"""
##########################################################################
def translateURI(query):
    quoted_url = urllib.quote(query) # change the characters with trouble
    return quoted_url

##########################################################################
"""Categories"""
##########################################################################

# General inputs using smiles or smarts formats
smiles='C(=O)C1=CCnCC1'
smarts='[#6;X4]-1-[#6](=[#8])-[#7]-[#7]-[#6]-1=[#8]'

# Categories for substructure searches
typeQuery = 'subs'

# Categories for similarity searches
fingerprint = 'Morgan'
method = 'Tanimoto'

# Changing the query to an URI format
smiles=translateURI(smiles)
smarts=translateURI(smarts)

##########################################################################
"""1. Example using substructure search with SMILES"""
##########################################################################
print "Results from SMILES ..."

# NOTE: The localhost must be changed by the current IP
substructure_data = json.loads(urllib2.urlopen("http://localhost/openchembl/rest/api_chembl.php?\
action=substructure&smiles=%s&type=%s" % (smiles,typeQuery)).read())

# Printing the records
for record in substructure_data:
    print "ChemblID: %s" % record['ChEMBL_ID']
    print "Molregno: %s" % record['Molregno']
    print "SMILES: %s" % record['SMILES']
    print

##########################################################################
"""2. Example using similarity search with SMARTS"""
##########################################################################
print "Results from SMARTS ..."

# NOTE: The localhost must be changed by the current IP
similarity_data = json.loads(urllib2.urlopen("http://localhost/openchembl/rest/api_chembl.php?\
action=similarity&smarts=%s&fingerprint=%s&method=%s" % (smarts,fingerprint,method)).read())

for record in similarity_data:
    print "ChemblID: %s" % record['ChEMBL_ID']
    print "Molregno: %s" % record['Molregno']
    print "Similarity: %s" % record['Similarity']
    print
    
##########################################################################
"""3. Example using properties calculation with SMILES"""
##########################################################################
print "Results from SMILES ..."

# NOTE: The localhost must be changed by the current IP
properties_data = json.loads(urllib2.urlopen("http://localhost/openchembl/rest/api_chembl.php?\
action=properties&smiles=%s" % (smiles)).read())

# Printing the records
for record in properties_data:
    print "SMILES: %s" % smilesP
    print "Molecular Weight: %s" % record['Molecular Weight']
    print "LogP: %s" % record['LogP']
    print "Lipinski H-Bond Acceptors: %s" % record['Lipinski H-Bond Acceptors']
    print "Lipinski H-Bond Donors: %s" % record['Lipinski H-Bond Donors']
    print "Number of atoms: %s" % record['Number of atoms']
    print "Number of heavy atoms: %s" % record['Number of heavy atoms']
    print "Number of rotatable bonds: %s" % record['Number of rotatable bonds']
    print "Number of Heteroatoms: %s" % record['Number of Heteroatoms']
    print "Number of Rings: %s" % record['Number of Rings']
    print "Topological Polar Surface Area: %s" % record['Topological Polar Surface Area']
    print "Number of Aromatic Rings: %s" % record['Number of Aromatic Rings']
    print "Number of Aliphatic Rings: %s" % record['Number of Aliphatic Rings']
    print "Number of Saturated Rings: %s" % record['Number of Saturated Rings']
    print "Number of Aromatic Heterocycles: %s" % record['Number of Aromatic Heterocycles']
    print "Number of Aliphatic Heterocycles: %s" % record['Number of Aliphatic Heterocycles']
    print "Number of Saturated Heterocycles: %s" % record['Number of Saturated Heterocycles']
    print "Number of Aromatic Carbocycles: %s" % record['Number of Aromatic Carbocycles']
    print "Number of Aliphatic Carbocycles: %s" % record['Number of Aliphatic Carbocycles']
    print "Number of Saturated Carbocycles: %s" % record['Number of Saturated Carbocycles']
    print "Fraction of carbons sp3 hybridized: %s" % record['Fraction of carbons sp3 hybridized']
    print
			</code></pre>
			</div>
    	   </p>  	
		</section> 
			
    </div>
    
    
<? include "footer.php"; ?> 
