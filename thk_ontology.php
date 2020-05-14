<?php
/*
*Ontology connection to THK 
*/

		extract($request,EXTR_SKIP);
			include("easyrdf/lib/EasyRdf.php");
			require_once "easyrdf/examples/html_tag_helpers.php";
			
			// Setup some prefixes
			EasyRdf_Namespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
			EasyRdf_Namespace::set('rdfs', 'http://www.w3.org/2000/01/rdf-schema#');
			EasyRdf_Namespace::set('owl', 'http://www.w3.org/2002/07/owl#');
			EasyRdf_Namespace::set('thk', 'http://dpch.oss.web.id/Bali/TriHitaKarana.owl#');
			
			$sparql = new EasyRdf_Sparql_Client('http://localhost:3030/thk2/query');

?>