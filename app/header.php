<?php
 /*
===============

(c) 2012 EMBL European Molecular Biology Laboratories

This code is licensed under Version 2.0 of the Open Source Initiative Apache License.
URL: http://www.opensource.org/licenses/apache2.0.php 

===============
 */

include_once("../config/config.php");

?>
<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!-- Consider adding an manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->

<!-- 
===============

(c) 2012 EMBL European Molecular Biology Laboratories

This code is licensed under Version 2.0 of the Open Source Initiative Apache License.
URL: http://www.opensource.org/licenses/apache2.0.php 

===============
-->


<head>
  <meta charset="utf-8">

  <!-- Use the .htaccess and remove these lines to avoid edge case issues.
       More info: h5bp.com/b/378 -->
  <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> -->	<!-- Not yet implemented -->

  <title>myChEMBL</title>
  <meta name="description" content="myChEMBL">
  <meta name="keywords" content="open-data, chembl, bioinformatics, europe, institute">
  <meta name="author" content="Rodrigo Ochoa" >

  <!-- Mobile viewport optimized: j.mp/bplateviewport -->
  <meta name="viewport" content="width=device-width,initial-scale=1">

  <link rel="stylesheet" href="<?=$app2base?>static/css/ebi-fluid-embl.css">
  <!-- end CSS-->

</head>

<body class="level2">

  <div id="wrapper" class="container_24">
    <header>
    	<div id="global-masthead" class="masthead grid_24">
			<p><!-- EMBL-EBI  --><img src="<?=$app2base?>static/images/app/logo-EBI.png" alt="European Bioinformatics Institute"></p>
			
			<nav>
				<ul id="global-nav">
					<li class="first" id="services"><a href="http://www.rdkit.org" title="Services">RDKit</a></li>
					<li id="research"><a href="http://ora2pg.darold.net/" title="Research">Ora2Pg</a></li>
					<li id="training"><a href="http://chembl.blogspot.co.uk/" title="Training">ChEMBL-og</a></li>
				</ul>
			</nav>
			
		</div>
		
		<div id="local-masthead" class="masthead grid_24 nomenu">
						
			<div id="local-title">
				<h1>myChEMBL <img src="<?=$app2base?>static/images/app/chembl_symbol.png" width="5%" height="5%"></h1>		
			</div>
			
			<nav>
				<ul class="grid_24" id="local-nav">
					<li class="first"><a href="home.php" title="">Home</a></li>
					<li><a href="structure.php">Structure Search</a></li>
	    			<li><a href="ws.php">Web Services</a></li>
	    			<li><a href="property.php">Property Calculation</a></li>
	    			<li><a href="tutorial.php">Tutorial</a></li>
	    			<li><a href="acknowledgements.php">Acknowledgements</a></li>
				</ul>
			</nav>
		</div>
    </header>