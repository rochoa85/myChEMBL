/////////////////////////////////////////////////////////
// Some extra JavaScript functions
/////////////////////////////////////////////////////////

$(document).ready(function() {
	$("input[name=chembl]:radio").attr("checked", false);
	$("input[name=chemblSim]:radio").attr("checked", false);
	
	$("#structure-search-type option:first").attr('selected', 'selected' );
	$("#substructure-search-input option:first").attr('selected', 'selected' );
	$("#similarity-search-input option:first").attr('selected', 'selected' );
	$("#property-search-input option:first").attr('selected', 'selected' );
	
	$('#substructure-input-div').hide();
	$('#similarity-input-div').hide();
	
	
	$('#structure-search-type').change(function(){
		if(this.value === 'sub'){
			$('#substructure-input-div').show();
			$('#similarity-input-div').hide();			
		} else if(this.value === 'sim'){
			$('#substructure-input-div').hide();
			$('#similarity-input-div').show();			
		}
		$("#structure-search-type option:first").attr('selected', 'selected' );
	})

	$('#property-search-input').change(function(){
		if(this.value === 'substructure-draw'){
	  		document.getElementById("struct_sketch_property").style.display="block";
			
	  		if(!document.JSME_PROP){
	            jsmeApplet = new JSApplet.JSME("struct_sketch_prop", "620px", "480px", {
		            "options" : "query,hydrogens"
	            });
	            document.JSME_PROP = jsmeApplet;
	  		}
	  		
	  		document.getElementById("entrada").style.display="none";
	  		document.getElementById("stringMOL").style.display="none";
			document.getElementById("stringSMARTS").style.display="none";
			document.getElementById("stringSMILES").style.display="none";		
		} else if(this.value === 'substructure-upload'){
	  		document.getElementById("struct_sketch_property").style.display="none";
	  		document.getElementById("entrada").style.display="block";		
		}
		$("#property-search-input option:first").attr('selected', 'selected' );
	})
	
	$('#substructure-search-input').change(function(){
		if(this.value === 'substructure-draw'){
	  		document.getElementById("substructure_section").style.display="block";
			
	  		if(!document.JSME_SUB){
	            jsmeApplet = new JSApplet.JSME("struct_sketch_sub", "620px", "480px", {
		            "options" : "query,hydrogens"
	            });
	            document.JSME_SUB = jsmeApplet;
	  		}
	  		document.getElementById("entrada").style.display="none";
	  		document.getElementById("stringMOL").style.display="none";
			document.getElementById("stringSMARTS").style.display="none";
			document.getElementById("stringSMILES").style.display="none";		
		} else if(this.value === 'substructure-upload'){
	  		document.getElementById("substructure_section").style.display="none";
	  		document.getElementById("entrada").style.display="block";		
		}
		$("#substructure-search-input option:first").attr('selected', 'selected' );
	})
	
	$('#similarity-search-input').change(function(){
		if(this.value === 'similarity-draw'){
	  		document.getElementById("similarity_section").style.display="block";
	  		
	  		if(!document.JSME_SIM){
	            jsmeApplet = new JSApplet.JSME("struct_sketch_sim", "620px", "480px", {
		            "options" : "query,hydrogens"
	            });
	            document.JSME_SIM = jsmeApplet;
	  		}
	  		
	  		document.getElementById("entradaSim").style.display="none";
	  		document.getElementById("stringMOLSim").style.display="none";
			document.getElementById("stringSMARTSSim").style.display="none";
			document.getElementById("stringSMILESSim").style.display="none";		
		} else if(this.value === 'similarity-upload'){
	  		document.getElementById("similarity_section").style.display="none";
	  		document.getElementById("entradaSim").style.display="block";		
		}
		$("#similarity-search-input option:first").attr('selected', 'selected' );
	})	
});

function export_mol(type) {
	if(type == 'sub'){
		var data = document.JSME_SUB.smiles();
		if (data){
			document.getElementById("formSpecialSub").value = "SMILES";
			document.getElementById('marSmartsSub').value = data;
		}			
	}else if (type == 'sim'){
		var data = document.JSME_SIM.smiles();
		if (data){
			document.getElementById('marSmartsSim').value = data;
		}	
	}else if (type == 'prop'){
		var data = document.JSME_PROP.smiles();
		if (data){
			document.getElementById('marSmarts').value = data;
		}	
	} else {
		alert('Opps unexpected export type');
	}
}

function despliegaInformacion(divinfo,pagina)
{
	var x=new XMLHttpRequest();
	div=document.getElementById(divinfo);
	x.onreadystatechange=function(){
		if(x.readyState==4){
			div.innerHTML=x.responseText;
		}else{
			div.innerHTML="<center>Waiting..</center>";
		}
	}
	x.open("GET",pagina,true);
	x.send();
}

function string(){
	if(document.getElementById("format").value=="SMILES"){
		document.getElementById("stringSMILES").style.display="block";
		document.getElementById("stringSMARTS").style.display="none";
		document.getElementById("stringMOL").style.display="none";
	}
	else if(document.getElementById("format").value=="ARTS"){	
		document.getElementById("stringSMARTS").style.display="block";
		document.getElementById("stringMOL").style.display="none";
		document.getElementById("stringSMILES").style.display="none";	
	}
	else if(document.getElementById("format").value=="MOL"){
		document.getElementById("stringMOL").style.display="block";
		document.getElementById("stringSMARTS").style.display="none";
		document.getElementById("stringSMILES").style.display="none";
	}
	else if(document.getElementById("format").value=="None"){
		document.getElementById("stringMOL").style.display="none";
		document.getElementById("stringSMARTS").style.display="none";
		document.getElementById("stringSMILES").style.display="none";
	}
}

function Simstring(){
	if(document.getElementById("formatSim").value=="SMILESSim"){
		document.getElementById("stringSMILESSim").style.display="block";
		document.getElementById("stringSMARTSSim").style.display="none";
		document.getElementById("stringMOLSim").style.display="none";
	}
	else if(document.getElementById("formatSim").value=="ARTSSim"){	
		document.getElementById("stringSMARTSSim").style.display="block";
		document.getElementById("stringMOLSim").style.display="none";
		document.getElementById("stringSMILESSim").style.display="none";	
	}
	else if(document.getElementById("formatSim").value=="MOLSim"){
		document.getElementById("stringMOLSim").style.display="block";
		document.getElementById("stringSMARTSSim").style.display="none";
		document.getElementById("stringSMILESSim").style.display="none";
	}
	else if(document.getElementById("formatSim").value=="None"){
		document.getElementById("stringMOLSim").style.display="none";
		document.getElementById("stringSMARTSSim").style.display="none";
		document.getElementById("stringSMILESSim").style.display="none";
	}
}

function dataChembl(){
	if(document.getElementById("dataChem").value=="ntd"){
		document.getElementById("chemblData").style.display="none";
	}
	else if(document.getElementById("dataChem").value=="chembl"){	
		document.getElementById("chemblData").style.display="block";
	}
}

function python(){
	if(document.getElementById("pycode").style.display=="none"){
		document.getElementById("pycode").style.display="block";
	}else if (document.getElementById("pycode").style.display=="block"){
		document.getElementById("pycode").style.display="none";
	}
}

function load(){
	var Scr = new ActiveXObject("Scripting.FileSystemObject");
	var CTF = Scr.OpenTextFile("test.php", 1, true);
	data = CTF.ReadAll(); 
	alert(data);
	CTF.Close();
}

function GetSelectedItem() {
	chosen = "";
	len = document.setTest.chembl.length;
	for (i = 0; i < len; i++) {
		if (document.setTest.chembl[i].checked) {
			chosen = document.setTest.chembl[i].value;
		}
	}
	if(chosen="yes") {
  		document.getElementById("chemblSet").style.display="block";
	}else if(chosen="no") {
  		document.getElementById("chemblSet").style.display="none";
	}else{
		alert();	
	}
}
