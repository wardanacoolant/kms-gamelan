<?php
    /**
     * @package    	Searching through gamelan
     * @copyright  	Copyright (c) 2020 I Made Wardana
     * @developer	made.wardana44@gmail.com
     * @license    	GNU
     */
    


    set_include_path(get_include_path() . PATH_SEPARATOR . './easyrdf/lib/');
    require_once "./easyrdf/lib/EasyRdf.php";
    require_once "./easyrdf/examples/html_tag_helpers.php";

    // Setup some additional prefixes
    EasyRdf_Namespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
    EasyRdf_Namespace::set('rdfs', 'http://www.w3.org/2000/01/rdf-schema#');
    EasyRdf_Namespace::set('owl', 'http://www.w3.org/2002/07/owl#');
	EasyRdf_Namespace::set('thk', 'http://dpch.oss.web.id/Bali/TriHitaKarana.owl#');

    $sparql = new EasyRdf_Sparql_Client('http://localhost:3030/thk/query');

    function viewData($request){
		extract($request,EXTR_SKIP);
		

		include ("thk_ontology.php");
		$error ="";
		if($cboutput==""){
			$error .="Tidak ada output yang dipilih!<br>";
		}
		if($error==""){
			$qoansambel = "";
			$qoarah = "";
			$qofungsi = "";
			$qojumlah = "";
			$qosound = "";
			$qodimension = "";
			$qopengangge = "";
			$qobahanbaku = "";
			$qotipesuara = "";

			//kondisi untuk query output
			if($cboutput=="aktivitas"){
				$qofungsi = "?GamelanEnsemble thk:isUsedFor ?aktivitas .
							   ?aktivitas a ?groupactivity .
							   ?tempat thk:hasKulkul ?GamelanEnsemble .";
			}
			if($cboutput=="ansambel"){
				$qoansambel = "?Upacara thk:hasEnsamble ?GamelanEnsemble .
							?tempat thk:hasKulkul ?Upacara .";
			}
			if($cboutput=="instrumen"){
				$qoansambel = "?GamelanEnsemble thk:hasInstrument ?GamelanInstrument .
							?tempat thk:hasKulkul ?GamelanEnsemble .";
			}
			if($cboutput=="fitur"){
				$qofungsi = "?GamelanInstrument thk:hasFeature ?GamelanInstrumentFeature .
							   ?tempat thk:hasKulkul ?GamelanInstrument .";
			}
			if($cboutput=="peran"){
				$qofungsi = "?GamelanInstrument thk:hasRole ?GamelanInstrumentRole .
							   ?tempat thk:hasKulkul ?GamelanInstrument .";
			}
			if($cboutput=="sumberSuara"){
				$qofungsi = "?GamelanInstrument thk:hasSoundSource ?GamelanInstrumentSoundSource .
							   ?tempat thk:hasKulkul ?GamelanInstrument .";
			}
			if($cboutput=="laras"){
				$qofungsi = "?GamelanInstrument thk:hasScale ?GamelanScale .
							   ?tempat thk:hasKulkul ?GamelanInstrument .";
			}
			if($cboutput=="direction"){
				$qoarah = "?kulkulName thk:hasDirection ?direction .
							?tempat thk:hasKulkul ?kulkulName .";
			}
			
			if($cboutput=="jumlahkulkul"){
				$qojumlah = "?kulkulName thk:numberKulkul ?jumlahkulkul .
							?tempat thk:hasKulkul ?kulkulName .";
			}
			if($cboutput=="sound"){
			  $qosound = "?kulkulName thk:hasSound ?sound01 .
			        ?sound01 rdfs:label ?sound .
			        ?tempat thk:hasKulkul ?kulkulName .
							?sound01 thk:isSoundFor ?aktivitas .";
			}
			if($cboutput=="dimension"){
				$qodimension = "?kulkulName thk:hasDimension ?dimension .
									  ?dimension rdfs:label ?dimension02 .
								?tempat thk:hasKulkul ?kulkulName .";
			}
			if($cboutput=="pengangge"){
				$qopengangge = "?kulkulName thk:hasPengangge ?pengangge .
								?tempat thk:hasKulkul ?kulkulName .";
			}
			if($cboutput=="rawMaterial"){
				$qobahanbaku = "?kulkulName thk:hasRawMaterial ?rawMaterial .
								?tempat thk:hasKulkul ?kulkulName .";
			}
			if($cboutput=="resourceType"){
				$qotipesuara = "?kulkulName thk:hasSound ?sound01 .
								?sound01 thk:hasSoundFile ?soundFile .
										?kulkulName thk:hasSoundFile ?soundFile .
										?soundFile thk:hasUrl ?soundUrl .
										?soundFile thk:hasResourceType ?resourceType .
								?tempat thk:hasKulkul ?kulkulName .";
			}

			$qjumlah = "";
			$qtempat = "";
			$qfungsi = "";
			$qarah = "";
			$qsuara = "";
			$qdimensi = "";
			$qpengangge = "";
			$qbahan = "";
			$qtipesuara = "";
			//kondisi untuk query input
			$s_input = "";
			if($cbinputaktivitas!=""){
				$qfungsi = "?GamelanEnsemble thk:isUsedFor ?aktivitas .
						   ?aktivitas a thk:$cbinputaktivitas .
						   ?tempat thk:hasKulkul ?GamelanEnsemble .";
				$s_input .="Aktivitas : ".$cbinputaktivitas.",";
			}
			if($cbinputansambel!=""){
				$qarah = "?kulkulName thk:hasDirection thk:$cbinputansambel .
								?tempat thk:hasKulkul ?kulkulName .";
				$s_input .=" Ansambel : ".$cbinputansambel.",";
			}
			if($cbinputinstrumen!=""){
				$qbahan = "?kulkulName thk:hasRawMaterial thk:$cbinputinstrumen .
							?tempat thk:hasKulkul ?kulkulName .";
				$s_input .=" Bahan Baku Kulkul : ".$cbinputinstrumen.",";
			}
			/*if($inpt_jumlah!=""){
				$qjumlah = "?kulkulName thk:numberKulkul $inpt_jumlah .
								?tempat thk:hasKulkul ?kulkulName .";
				$s_input .=" Jumlah Kulkul : ".$inpt_jumlah.",";
			}
			if($inpt_tempat!=""){
				$qtempat = "?tempat thk:hasKulkul ?kulkulName .
							  ?tempat a thk:$inpt_tempat .
					  FILTER (?tempat NOT IN (owl:NamedIndividual))
					?tempat thk:isPartOf* ?kabupaten .
					?kabupaten a thk:Kabupaten .";
				$s_input .=" Lokasi : ".$inpt_tempat.",";
			}
			if($inpt_pengangge!=""){
				$qpengangge = "?kulkulName thk:hasPengangge thk:$inpt_pengangge .
								?tempat thk:hasKulkul ?kulkulName .";
				$s_input .=" Pengangge : ".$inpt_pengangge.",";
			}
			if($inpt_suara!=""){
				$qsuara = "?kulkulName thk:hasSound ?sound01 .
							?sound01 rdfs:label ?sound .
							?tempat thk:hasKulkul ?kulkulName .
							?sound01 thk:isSoundFor ?aktivitas .
							FILTER (CONTAINS (?sound, '$inpt_suara'))";
				$s_input .=" Suara Kulkul : ".$inpt_suara.",";
			}
			if($inpt_tipesuara!=""){
				$qtipesuara = "?kulkulName thk:hasSound ?sound01 .
								?sound01 thk:hasSoundFile ?soundFile .
										?kulkulName thk:hasSoundFile ?soundFile .
										?soundFile thk:hasUrl ?soundUrl .
										?soundFile thk:hasResourceType thk:$inpt_tipesuara .
								?tempat thk:hasKulkul ?kulkulName .";
				$s_input .=" Tipe Suara : ".$inpt_tipesuara.",";
			}
			if($inpt_ukuran!=""){
				$qdimensi = "?kulkulName thk:hasDimension thk:$inpt_ukuran .
									  thk:$inpt_ukuran rdfs:label ?dimension02 .
							?tempat thk:hasKulkul ?kulkulName .";
				$s_input .=" Ukuran Kulkul : ".$inpt_ukuran.",";
			}*/

			


			$qc = $sparql->query(
					"SELECT DISTINCT (?$cboutput as ?output)
					{
						".$qoarah."
						".$qofungsi."
						".$qojumlah."
						".$qosound."
						".$qodimension."
						".$qopengangge."
						".$qobahanbaku."
						".$qotipesuara."

						".$qtempat."
						".$qjumlah."
						".$qdimensi."
						".$qpengangge."
						".$qfungsi."
						".$qarah."
						".$qbahan."
						".$qsuara."
						".$qtipesuara."
					} ORDER BY ?output");
			$view ="<div class=\"row\">
						<div class=\"col-md-12\">
							<div class=\"box box-success\" style=\"padding:20px 20px 20px 40px\">
								<div class=\"row\">
									<div class=\"col-md-5\">
									<h3>OUTPUT</h3>";
			$i = 1;
			foreach ($qc as $dc) {
				if($cboutput=="jumlahkulkul"){
					$voutput = $dc->output;
					$voutput = "<a href=\"?page=Browsing&action=viewlink&value=".$voutput."&tipe=kulkuljumlah\">".$voutput."</a>";
				}else if($cboutput=="tempat"){
					$voutput = trim(parsingString("#",$dc->output,1));
					$outputlink = str_replace(' ', '', $voutput);
					//banjar
					$temp_tempat = substr($outputlink,0,4);
					if($temp_tempat=="Banj"){
						$voutput = "<a href=\"?page=Browsing&action=viewdetailbanjar&banjar_desc=".$outputlink."&kab_desc=".$kab_desc."\">".$voutput."</a>";
					}else if($temp_tempat=="Desa"){
						$voutput = "<a href=\"?page=Browsing&language=".$language."&domain=".$domain."&action=banjar&desa_desc=".$outputlink."&kab_desc=".$kab_desc."\">".$voutput."</a>";
					}else if($temp_tempat=="Pura"){
						$tempat_desc_pura = str_replace('PuraDesa','',$outputlink);
						$tempat_desc_pura = str_replace('PuraPuseh','',$tempat_desc_pura);
						$tempat_desc_pura = str_replace('PuraDalem','',$tempat_desc_pura);
						$voutput = "<a href=\"?page=Pura&action=pura&desa_desc=".$tempat_desc_pura."&kab_desc=".$kab_desc."&purakhayangan=".$outputlink."\">".$voutput."</a>";
					}

				}else if($cboutput=="rawMaterial"){
					$voutput = trim(parsingString("#",$dc->output,1));
					$outputlink = str_replace(' ', '', $voutput);
					$voutput = "<a href=\"?page=Browsing&action=viewlink&value=".$outputlink."&tipe=kulkulbahan\">".$voutput."</a>";
				}else if($cboutput=="dimension"){
					$voutput = trim(parsingString("#",$dc->output,1));
					$outputlink = str_replace(' ', '', $voutput);
					$voutput = "<a href=\"?page=Browsing&action=viewlink&value=".$outputlink."&tipe=kulkulukuran\">".$voutput."</a>";
				}else if($cboutput=="pengangge"){
					$voutput = trim(parsingString("#",$dc->output,1));
					$outputlink = str_replace(' ', '', $voutput);
					$voutput = "<a href=\"?page=Browsing&action=viewlink&value=".$outputlink."&tipe=kulkulpengangge\">".$voutput."</a>";
				}else if($cboutput=="sound"){
					$voutput = $dc->output;
					$outputlink = str_replace(' ', '', $voutput);
					$voutput = "<a href=\"?page=Browsing&action=viewlink&value=".$voutput."&tipe=kulkulsound\">".$voutput."</a>";
				}else if($cboutput=="resourceType"){
					$voutput = trim(parsingString("#",$dc->output,1));
					$outputlink = str_replace(' ', '', $voutput);
					$voutput = "<a href=\"?page=Browsing&action=viewlink&value=".$outputlink."&tipe=kulkultypesuara\">".$voutput."</a>";
				}else if($cboutput=="aktivitas"){
					$voutput = trim(parsingString("#",$dc->output,1));
					$outputlink = str_replace(' ', '', $voutput);
					$voutput = "<a href=\"?page=Browsing&action=viewlink&value=".$outputlink."&tipe=kulkulactivity\">
									".$voutput."
								</a>";
				}else if($cboutput=="direction"){
					$voutput = trim(parsingString("#",$dc->output,1));
					$outputlink = str_replace(' ', '', $voutput);
					$voutput = "<a href=\"?page=Browsing&action=viewlink&value=".$voutput."&tipe=kulkularah\">".$outputlink."</a>";
				}else{
					$voutput = parsingString("#",$dc->output,1);
				}

				$view .=$i.". ".$voutput."<br>";
				$i++;
			}
			$view .="				</div>
									<div class=\"col-md-7\">
										<div class=\"alert alert-info alert-dismissable\">
											<i class=\"fa fa-info\"></i>
											<p style=\"font-size:20px\"><b>QUERY</b></p>
											SELECT DISTINCT (?$cboutput as ?output)
											{
												".$qoarah."
												".$qofungsi."
												".$qojumlah."
												".$qosound."
												".$qodimension."
												".$qopengangge."
												".$qobahanbaku."
												".$qotipesuara."

												".$qtempat."
												".$qjumlah."
												".$qdimensi."
												".$qpengangge."
												".$qfungsi."
												".$qarah."
												".$qbahan."
												".$qsuara."
												".$qtipesuara."
											} ORDER BY ?output  <br><br>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>";
		}else{
			$view ="<div class=\"row\">
						<div class=\"col-md-12\">
							<div class=\"box box-success\" style=\"padding:20px\"><br>
								".$error."";
			$view .="		</div>
						</div>
					</div>";
		}

		return $view;
	}

	function viewFormSearch($request){
		extract($request,EXTR_SKIP);
		include ("thk_ontology.php");	

		$instances = "";	//variabel nama instances
      	$instances .= preg_replace('/(?<! )[A-Z]/', ' $0', $value);  //Kasi spasi nama instances untuk judul h1
      	$instances = str_replace('_', ' ', $instances);

      	//Kumpulin dulu property yang ada di instance tsb
      	$resultProperty = $sparql->query( //query sparql
        "select distinct ?property where {
		 thk:".$value." ?property ?subject FILTER (?property != <http://www.w3.org/1999/02/22-rdf-syntax-ns#type>) . FILTER (?property != <http://schema.org/image>)}");

      	$resultImages = $sparql->query("select distinct ?property ?subject where {
  		thk:".$value." ?property ?subject filter (?property = <http://schema.org/image>) }");
		//hitung jumlah property
		$jumlahProperty = count($resultProperty); 
		//inisialisasi array penampung dan counter dan inisialisasi penampung query masing2 property
		$arrayProperty = array();
		$queryStringProperty = array();
		$i = 0;
		$tempArray = array();

		foreach ($resultImages as $row) {
			$array = explode('""', trim($row->subject, '""'));
			$string = $array[0];
          $tempImages = $string;
		}
		//Lakukan perulangan untuk Masukin ke array dan melakukan concat query masing2 property
		foreach ($resultProperty as $row) {  //perulangan option
    		if(strstr($row->property, '#') == false){
		          	$tempArray = explode("/",$row->property);
		          	$arrayProperty[$i] = $tempArray[3];
	          	}
	          	else{
		          	$tempArray = explode("#",$row->property);
		          	$arrayProperty[$i] = $tempArray[1];
	          	}
    		$tempProperty = $arrayProperty[$i];
			$tempProperty = preg_replace('/(?<! )[A-Z]/', ' $0', $tempProperty);
			$tempProperty = str_replace('_', ' ', $tempProperty);
			$arrayStringProperty[$i] = $tempProperty;
    		if ($arrayStringProperty[$i]=="comment") {
    			$querySpecificProperty[$i] = "select distinct ?object where { thk:".$value." rdfs:".$arrayProperty[$i]." ?object }";
   			}
   			else{
    			$querySpecificProperty[$i] = "select distinct ?object where { thk:".$value." thk:".$arrayProperty[$i]." ?object }";
   			}
			
    		$i++;
		}
		//reset counter dan inisialisasi array penampung jumlah object
		$i = 0;
		$j = 0;
		$jumlahObject = array();
		$arrayTempProperty = array();
		$arrayStringObject = array();
		$arrayValueObject = array();
		//Testing isi array query property
		//for ($i=0; $i < $jumlahProperty; $i++) { 
		//  	echo " | ".$querySpecificProperty[$i]." | <br> ";
		//  }  
		//$i = 0;
		
		//Lakukan perulangan untuk setiap anggota array untuk melakukan query masing2 property
		for ($i=0; $i < $jumlahProperty; $i++) {  //perulangan option
			$resultSpecificProperty = $sparql->query( $querySpecificProperty[$i] );
			$jumlahObject[$i] = count($resultSpecificProperty); 
			foreach ($resultSpecificProperty as $row) {  //perulangan option
				if(strstr($row->object, '#') == false && strstr($row->object, '/') == true){
					//echo "Condition 1: ".$row->object." ,"; //Testing kondisi 1
		          	$arrayTempProperty = explode("/",$row->object);
		          	$string = $arrayTempProperty[3];
	          	}
	          	elseif(strstr($row->object, '#') == true){
	          		//echo "Condition 2: ".$row->object." ,"; //Testing kondisi 1
		          	$arrayTempProperty = explode("#",$row->object);
		          	$string = $arrayTempProperty[1];
	          	}
	          	else{
	          		//echo "Condition 3: ".$row->object." ,"; //Testing kondisi 1
	          		$string = $row->object;
	          	}
				$arrayValueObject[$i][$j] = $string;
				$string = preg_replace('/(?<! )[A-Z]/', ' $0', $string);
				$string = str_replace('_', ' ', $string);	
				$arrayStringObject[$i][$j] = $string;
				$j++;
	        }
	        $j=0;
		}
		//inisialisasi penampung string front end list
		$stringSpecificProperty = "";
		//reset counter 
		$i = 0;
		$j = 0;
		//Testing isi array object masing" query property
		//for ($i=0; $i < $jumlahProperty; $i++) { 
		//	for ($j=0; $j < $jumlahObject[$i]; $j++) { 
		//		echo " ".$i."." ;
		//		echo " ".$j."." ;
		//		echo " | ".$arrayValueObject[$i][$j]." | <br> ";
		//	}
		//}
		//Lakukan perulangan untuk memasukkan ke list bullet
		for ($i=0; $i < $jumlahProperty; $i++) { 
			$stringSpecificProperty .= "<strong><i class=\"far fa-file-alt mr-1\"></i> ".$arrayStringProperty[$i]." : </strong><ul>";
			for ($j=0; $j < $jumlahObject[$i]; $j++) { 
				if ($arrayStringProperty[$i]=="comment") {
					$stringSpecificProperty .= "<li><p>".$arrayStringObject[$i][$j]."</p></li>";
				}
				else{
					$stringSpecificProperty .= "<li><a href=\"./browsingList.php?action=viewlink&value=".$arrayValueObject[$i][$j]." \">".$arrayStringObject[$i][$j]."</a></li>";
				}
				
			}
			$stringSpecificProperty .= "</ul><hr>";
		}

        $formAktivitas = "";
		$tempAktivitas = "";
		$resultAktivitas = $sparql->query( "SELECT DISTINCT ?column { ?column rdfs:subClassOf thk:Upacara . }  ORDER BY ?column");
        foreach ($resultAktivitas as $row) {  //perulangan option
          $array = explode("#",$row->column);
          $string = $array[1];
          $tempAktivitas = $string;
          $string = preg_replace('/(?<! )[A-Z]/', ' $0', $string);
          $string = preg_replace('/s+/', '', $string);	
          $formAktivitas .=  "<li><a href=\"./browsingList.php?action=viewlink&value=".$tempAktivitas."\">".$string."</a></li>";
        }

        $formGolongan = "";
        $liGolongan = "";
        $tempGolongan = "";
        $resultGolongan = $sparql->query( "SELECT DISTINCT * { ?column rdf:type thk:Golongan }");
        foreach ($resultGolongan as $row) {  //perulangan option
          $array = explode("#",$row->column);
          $string = $array[1];
          $tempGolongan = $string;
          $string = preg_replace('/(?<! )[A-Z]/', ' $0', $string);
          $formGolongan .= "<li><a href=\"./browsingList.php?action=viewlink&value=".$tempGolongan."\">".$string."</a></li>";
          $liGolongan .= "<li class=\"nav-item\">
                <a href=\"./browsingList.php?action=viewlink&value=".$tempGolongan."\" class=\"nav-link\">
                  <i class=\"far fa-circle nav-icon\"></i>
                  <p>".$string."</p>
                </a>
              </li>";
        }

        $formInstrumen = "";
        $liInstrumen = "";
        $tempInstrumen = "";
        $resultInstrumen = $sparql->query( "SELECT DISTINCT * { ?column rdf:type thk:NamaInstrumen }");
        foreach ($resultInstrumen as $row) {  //perulangan option
          $array = explode("#",$row->column);
          $string = $array[1];
          $tempInstrumen = $string;
          $string = preg_replace('/(?<! )[A-Z]/', ' $0', $string);
          $formInstrumen .= "<li><a href=\"./browsingList.php?action=viewlink&value=".$tempInstrumen."\">".$string."</a></li>";
          $liInstrumen .= "<li class=\"nav-item\">
                <a href=\"./browsingList.php?action=viewlink&value=".$tempInstrumen."\" class=\"nav-link\">
                  <i class=\"far fa-circle nav-icon\"></i>
                  <p>".$string."</p>
                </a>
              </li>";
        }

        $formJumlahNada = "";
        $liJumlahNada = "";
        $tempJumlahNada = "";
        $resultJumlahNada = $sparql->query( //query sparql
        "SELECT DISTINCT *
        { ?column rdf:type thk:JumlahNada }");
        foreach ($resultJumlahNada as $row) {  //perulangan option
          $array = explode("#",$row->column);
          $string = $array[1];
          $tempJumlahNada = $string;
          $string = preg_replace('/(?<! )[A-Z]/', ' $0', $string);
          $string = str_replace('_', '', $string);
          $formJumlahNada .= "<li><a href=\"./browsingList.php?action=viewlink&value=".$tempJumlahNada."\">".$string."</a></li>";
          $liJumlahNada .= "<li class=\"nav-item\">
                <a href=\"./browsingList.php?action=viewlink&value=".$tempJumlahNada."\" class=\"nav-link\">
                  <i class=\"far fa-circle nav-icon\"></i>
                  <p>".$string."</p>
                </a>
              </li>";
        }

        $formJumlahPemainGamelan = "";
        $liJumlahPemainGamelan = "";
        $tempJumlahPemainGamelan = "";
        $resultJumlahPemainGamelan = $sparql->query( //query sparql
        "SELECT DISTINCT *
        { ?column rdf:type thk:JumlahPemainGamelan }");
        foreach ($resultJumlahPemainGamelan as $row) {  //perulangan option
          $array = explode("#",$row->column);
          $string = $array[1];
          $tempJumlahPemainGamelan = $string;
          $string = preg_replace('/(?<! )[A-Z]/', ' $0', $string);
          $string = str_replace('_', '', $string);
          $formJumlahPemainGamelan .= "<li><a href=\"./browsingList.php?action=viewlink&value=".$tempJumlahPemainGamelan."\">".$string."</a></li>";
          $liJumlahPemainGamelan .= "<li class=\"nav-item\">
                <a href=\"./browsingList.php?action=viewlink&value=".$tempJumlahPemainGamelan."\" class=\"nav-link\">
                  <i class=\"far fa-circle nav-icon\"></i>
                  <p>".$string."</p>
                </a>
              </li>";
        }

        $formKategori = "";
	    $liKategori = "";
	    $resultKategori = $sparql->query( //query sparql
        "SELECT DISTINCT *
        { ?column rdf:type thk:Kategori }");
        foreach ($resultKategori as $row) {  //perulangan option
          $array = explode("#",$row->column);
          $string = $array[1];
          $tempKategori = $string;
          $string = preg_replace('/(?<! )[A-Z]/', ' $0', $string);
          $formKategori .= "<li><a href=\"./browsingList.php?action=viewlink&value=".$tempKategori."\">".$string."</a></li>";
          $liKategori .= "<li class=\"nav-item\">
                <a href=\"./browsingList.php?action=viewlink&value=".$tempKategori."\" class=\"nav-link\">
                  <i class=\"far fa-circle nav-icon\"></i>
                  <p>".$string."</p>
                </a>
              </li>";
        }

        $formLaras = "";
	    $liLaras = "";
	    $resultLaras = $sparql->query( //query sparql
        "SELECT DISTINCT *
        { ?column rdf:type thk:LarasGamelan }");
        foreach ($resultLaras as $row) {  //perulangan option
          $array = explode("#",$row->column);
          $string = $array[1];
          $formLaras .= "<li><a href=\"./browsingList.php?action=viewlink&value=".$string."\">".$string."</a></li>";
          $liLaras .= "<li class=\"nav-item\">
                <a href=\"./browsingList.php?action=viewlink&value=".$string."\" class=\"nav-link\">
                  <i class=\"far fa-circle nav-icon\"></i>
                  <p>".$string."</p>
                </a>
              </li>";
        }

        $formTeknikPermainan = "";
        $liTeknikPermainan = "";
        $tempTeknikPermainan = "";
        $resultTeknikPermainan = $sparql->query( //query sparql
		"SELECT DISTINCT ?soundSource {
		    ?soundSource rdf:type thk:TeknikPermainan .
		  }  ORDER BY ?soundSource");
		foreach ($resultTeknikPermainan as $row) {  //perulangan option
			$array = explode("#",$row->soundSource);
			$string = $array[1];
			$tempTeknikPermainan = $string;
			$string = preg_replace('/(?<! )[A-Z]/', ' $0', $string);
			$formTeknikPermainan .= "<li><a href=\"./browsingList.php?action=viewlink&value=".$tempTeknikPermainan."\">".$string."</a></li>";
			$liTeknikPermainan .= "<li class=\"nav-item\">
                <a href=\"./browsingList.php?action=viewlink&value=".$tempTeknikPermainan."\" class=\"nav-link\">
                  <i class=\"far fa-circle nav-icon\"></i>
                  <p>".$string."</p>
                </a>
              </li>";
	    }
        $view = "<html>
					<head>
					<meta charset=\"utf-8\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Browsing Result | KMS Gamlean Lite</title>
    <meta content=\"width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no\" name=\"viewport\">

      <!-- Font Awesome -->
      <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.13.0/css/all.min.css\">
      <!-- Ionicons -->
      <link rel=\"stylesheet\" href=\"https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css\">
      <!-- overlayScrollbars -->
      <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.0.2/css/adminlte.min.css\" integrity=\"sha256-tDEOZyJ9BuKWB+BOSc6dE4cI0uNznodJMx11eWZ7jJ4=\" crossorigin=\"anonymous\" />
      <!-- Google Font: Source Sans Pro -->
      <link href=\"https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700\" rel=\"stylesheet\">";
					 
		$view .= "</head>
					<body class=\"hold-transition sidebar-mini\">

					<div class=\"wrapper\">
					<!-- Navbar -->
  <nav class=\"main-header navbar navbar-expand navbar-white navbar-light\">
    <!-- Left navbar links -->
    <ul class=\"navbar-nav\">
      <li class=\"nav-item\">
        <a class=\"nav-link\" data-widget=\"pushmenu\" href=\"#\"><i class=\"fas fa-bars\"></i></a>
      </li>
      <li class=\"nav-item d-none d-sm-inline-block\">
        <a href=\"./index.php\" class=\"nav-link\">Home</a>
      </li>
      <li class=\"nav-item d-none d-sm-inline-block\">
        <a href=\"./browsing.php\" class=\"nav-link\">Browsing</a>
      </li>
      <li class=\"nav-item d-none d-sm-inline-block\">
        <a href=\"./searching.php\" class=\"nav-link\">Searching</a>
      </li>
      <li class=\"nav-item d-none d-sm-inline-block\">
        <a href=\"https://docs.google.com/forms/d/e/1FAIpQLSc7pxT7Vj1mP__jhTPfAhTuGl2AdYZ1FSMBjftB7G-EQf_qXQ/viewform?usp=sf_link\" class=\"nav-link\">Questionnaire</a>
      </li>
      </ul>

    

    <!-- Right navbar links -->
    <ul class=\"navbar-nav ml-auto\">
      <!-- Messages Dropdown Menu -->
      <li class=\"nav-item dropdown\">
        <a class=\"nav-link\" data-toggle=\"dropdown\" href=\"#\">
          <i class=\"far fa-comments\"></i>
          <span class=\"badge badge-danger navbar-badge\">3</span>
        </a>
        <div class=\"dropdown-menu dropdown-menu-lg dropdown-menu-right\">
          <a href=\"#\" class=\"dropdown-item\">
            <!-- Message Start -->
            <div class=\"media\">
              <img src=\"https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.0.2/img/user1-128x128.jpg\" alt=\"User Avatar\" class=\"img-size-50 mr-3 img-circle\">
              <div class=\"media-body\">
                <h3 class=\"dropdown-item-title\">
                  Brad Diesel
                  <span class=\"float-right text-sm text-danger\"><i class=\"fas fa-star\"></i></span>
                </h3>
                <p class=\"text-sm\">Call me whenever you can...</p>
                <p class=\"text-sm text-muted\"><i class=\"far fa-clock mr-1\"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class=\"dropdown-divider\"></div>
          <a href=\"#\" class=\"dropdown-item\">
            <!-- Message Start -->
            <div class=\"media\">
              <img src=\"https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.0.2/img/user1-128x128.jpg\" alt=\"User Avatar\" class=\"img-size-50 img-circle mr-3\">
              <div class=\"media-body\">
                <h3 class=\"dropdown-item-title\">
                  John Pierce
                  <span class=\"float-right text-sm text-muted\"><i class=\"fas fa-star\"></i></span>
                </h3>
                <p class=\"text-sm\">I got your message bro</p>
                <p class=\"text-sm text-muted\"><i class=\"far fa-clock mr-1\"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class=\"dropdown-divider\"></div>
          <a href=\"#\" class=\"dropdown-item\">
            <!-- Message Start -->
            <div class=\"media\">
              <img src=\"https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.0.2/img/user1-128x128.jpg\" alt=\"User Avatar\" class=\"img-size-50 img-circle mr-3\">
              <div class=\"media-body\">
                <h3 class=\"dropdown-item-title\">
                  Nora Silvester
                  <span class=\"float-right text-sm text-warning\"><i class=\"fas fa-star\"></i></span>
                </h3>
                <p class=\"text-sm\">The subject goes here</p>
                <p class=\"text-sm text-muted\"><i class=\"far fa-clock mr-1\"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class=\"dropdown-divider\"></div>
          <a href=\"#\" class=\"dropdown-item dropdown-footer\">See All Messages</a>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class=\"nav-item dropdown\">
        <a class=\"nav-link\" data-toggle=\"dropdown\" href=\"#\">
          <i class=\"far fa-bell\"></i>
          <span class=\"badge badge-warning navbar-badge\">15</span>
        </a>
        <div class=\"dropdown-menu dropdown-menu-lg dropdown-menu-right\">
          <span class=\"dropdown-item dropdown-header\">15 Notifications</span>
          <div class=\"dropdown-divider\"></div>
          <a href=\"#\" class=\"dropdown-item\">
            <i class=\"fas fa-envelope mr-2\"></i> 4 new messages
            <span class=\"float-right text-muted text-sm\">3 mins</span>
          </a>
          <div class=\"dropdown-divider\"></div>
          <a href=\"#\" class=\"dropdown-item\">
            <i class=\"fas fa-users mr-2\"></i> 8 friend requests
            <span class=\"float-right text-muted text-sm\">12 hours</span>
          </a>
          <div class=\"dropdown-divider\"></div>
          <a href=\"#\" class=\"dropdown-item\">
            <i class=\"fas fa-file mr-2\"></i> 3 new reports
            <span class=\"float-right text-muted text-sm\">2 days</span>
          </a>
          <div class=\"dropdown-divider\"></div>
          <a href=\"#\" class=\"dropdown-item dropdown-footer\">See All Notifications</a>
        </div>
      </li>
      <li class=\"nav-item\">
        <a class=\"nav-link\" data-widget=\"control-sidebar\" data-slide=\"true\" href=\"#\">
          <i class=\"fas fa-th-large\"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
  <!-- Main Sidebar Container -->
  <aside class=\"main-sidebar sidebar-dark-primary elevation-4\">
    <!-- Brand Logo -->
    <a href=\"./index.php\" class=\"brand-link\">
      <img src=\"https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.0.2/img/AdminLTELogo.png\"
           alt=\"AdminLTE Logo\"
           class=\"brand-image img-circle elevation-3\"
           style=\"opacity: .8\">
      <span class=\"brand-text font-weight-light\">KMS Gamelan</span>
    </a>

    <!-- Sidebar -->
    <div class=\"sidebar\">
      <!-- Sidebar user (optional) -->
      <div class=\"user-panel mt-3 pb-3 mb-3 d-flex\">
        <div class=\"image\">
          <img src=\"https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.0.2/img/user2-160x160.jpg\" class=\"img-circle elevation-2\" alt=\"User Image\">
        </div>
        <div class=\"info\">
          <a href=\"#\" class=\"d-block\">Made Wardana</a>
        </div>
      </div>
            <!-- Sidebar Menu -->
      <nav class=\"mt-2\">
        <ul class=\"nav nav-pills nav-sidebar flex-column\" data-widget=\"treeview\" role=\"menu\" data-accordion=\"false\">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class=\"nav-header\">KELAS BARUNGAN</li>
          
          <li class=\"nav-item has-treeview\">
            <a href=\"#\" class=\"nav-link\">
              <i class=\"nav-icon fas fa-circle\"></i>
              <p>
                Aktivitas
                <i class=\"right fas fa-angle-left\"></i>
              </p>
            </a>
            <ul class=\"nav nav-treeview\">
              
              <li class=\"nav-item has-treeview\">
                <a href=\"#\" class=\"nav-link\">
                  <i class=\"far fa-circle nav-icon\"></i>
                  <p>
                    Upacara
                    <i class=\"right fas fa-angle-left\"></i>
                  </p>
                </a>
                <ul class=\"nav nav-treeview\">
                  <li class=\"nav-item\">
                    <a href=\"./browsingList.php?action=viewlink&value=Dewa_Yadnya\" class=\"nav-link\">
                      <i class=\"far fa-dot-circle nav-icon\"></i>
                      <p>Dewa Yadnya</p>
                    </a>
                  </li>
                  <li class=\"nav-item\">
                    <a href=\"./browsingList.php?action=viewlink&value=Pitra_Yadnya\" class=\"nav-link\">
                      <i class=\"far fa-dot-circle nav-icon\"></i>
                      <p>Pitra Yadnya</p>
                    </a>
                  </li>
                  <li class=\"nav-item\">
                    <a href=\"./browsingList.php?action=viewlink&value=Rsi_Yadnya\" class=\"nav-link\">
                      <i class=\"far fa-dot-circle nav-icon\"></i>
                      <p>Rsi Yadnya</p>
                    </a>
                  </li>
                  <li class=\"nav-item\">
                    <a href=\"./browsingList.php?action=viewlink&value=Manusa_Yadnya\" class=\"nav-link\">
                      <i class=\"far fa-dot-circle nav-icon\"></i>
                      <p>Manusa Yadnya</p>
                    </a>
                  </li>
                  <li class=\"nav-item\">
                    <a href=\"./browsingList.php?action=viewlink&value=Bhuta_Yadnya\" class=\"nav-link\">
                      <i class=\"far fa-dot-circle nav-icon\"></i>
                      <p>Bhuta Yadnya</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class=\"nav-item has-treeview\">
                <a href=\"#\" class=\"nav-link\">
                  <i class=\"far fa-circle nav-icon\"></i>
                  <p>
                    Pertunjukan Seni
                    <i class=\"right fas fa-angle-left\"></i>
                  </p>
                </a>
              
              <ul class=\"nav nav-treeview\">
                  <li class=\"nav-item\">
                    <a href=\"./browsingList.php?action=viewlink&value=PertunjukanSeniDramatari\" class=\"nav-link\">
                      <i class=\"far fa-dot-circle nav-icon\"></i>
                      <p>Seni Dramatari</p>
                    </a>
                  </li>
                  <li class=\"nav-item\">
                    <a href=\"./browsingList.php?action=viewlink&value=PertunjukanSeniMusik\" class=\"nav-link\">
                      <i class=\"far fa-dot-circle nav-icon\"></i>
                      <p>Seni Musik</p>
                    </a>
                  </li>
                  <li class=\"nav-item\">
                    <a href=\"./browsingList.php?action=viewlink&value=PertunjukanSeniTari\" class=\"nav-link\">
                      <i class=\"far fa-dot-circle nav-icon\"></i>
                      <p>Seni Tari</p>
                    </a>
                  </li>
                  <li class=\"nav-item\">
                    <a href=\"./browsingList.php?action=viewlink&value=PertunjukanSeniTeater\" class=\"nav-link\">
                      <i class=\"far fa-dot-circle nav-icon\"></i>
                      <p>Seni Teater</p>
                    </a>
                  </li>
                  <li class=\"nav-item\">
                    <a href=\"./browsingList.php?action=viewlink&value=PertunjukanSeniWayang\" class=\"nav-link\">
                      <i class=\"far fa-dot-circle nav-icon\"></i>
                      <p>Seni Wayang</p>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </li>

          <li class=\"nav-item has-treeview\">
            <a href=\"#\" class=\"nav-link\">
              <i class=\"nav-icon fas fa-circle\"></i>
              <p>
                Golongan
                <i class=\"right fas fa-angle-left\"></i>
              </p>
            </a>
            <ul class=\"nav nav-treeview\">
              ".$liGolongan."
            </ul>
          </li>

          <li class=\"nav-item has-treeview\">
            <a href=\"#\" class=\"nav-link\">
              <i class=\"nav-icon fas fa-circle\"></i>
              <p>
                Instrumen
                <i class=\"right fas fa-angle-left\"></i>
              </p>
            </a>
            <ul class=\"nav nav-treeview\">
              ".$liInstrumen."
            </ul>
          </li>

          <li class=\"nav-item has-treeview\">
            <a href=\"#\" class=\"nav-link\">
              <i class=\"nav-icon fas fa-circle\"></i>
              <p>
                Jumlah Nada
                <i class=\"right fas fa-angle-left\"></i>
              </p>
            </a>
            <ul class=\"nav nav-treeview\">
              ".$liJumlahNada."
            </ul>
          </li>

          <li class=\"nav-item has-treeview\">
            <a href=\"#\" class=\"nav-link\">
              <i class=\"nav-icon fas fa-circle\"></i>
              <p>
                Jumlah Pemain
                <i class=\"right fas fa-angle-left\"></i>
              </p>
            </a>
            <ul class=\"nav nav-treeview\">
              ".$liJumlahPemainGamelan."
            </ul>
          </li>

          <li class=\"nav-item has-treeview\">
            <a href=\"#\" class=\"nav-link\">
              <i class=\"nav-icon fas fa-circle\"></i>
              <p>
                Kategori
                <i class=\"right fas fa-angle-left\"></i>
              </p>
            </a>
            <ul class=\"nav nav-treeview\">
              ".$liKategori."
            </ul>
          </li>

          <li class=\"nav-item has-treeview\">
            <a href=\"#\" class=\"nav-link\">
              <i class=\"nav-icon fas fa-circle\"></i>
              <p>
                Laras
                <i class=\"right fas fa-angle-left\"></i>
              </p>
            </a>
            <ul class=\"nav nav-treeview\">
              ".$liLaras."
            </ul>
          </li>

          <li class=\"nav-item has-treeview\">
            <a href=\"#\" class=\"nav-link\">
              <i class=\"nav-icon fas fa-circle\"></i>
              <p>
                Teknik Permainan
                <i class=\"right fas fa-angle-left\"></i>
              </p>
            </a>
            <ul class=\"nav nav-treeview\">
              ".$liTeknikPermainan."
            </ul>
          </li>

          <li class=\"nav-header\">TAUTAN</li>
          <li class=\"nav-item\">
            <a href=\"https://ccbp.oss.web.id\" class=\"nav-link\">
              <i class=\"nav-icon far fa-circle text-danger\"></i>
              <p class=\"text\">CCBP</p>
            </a>
          </li>
          <li class=\"nav-item\">
            <a href=\"https://silsilah.oss.web.id\" class=\"nav-link\">
              <i class=\"nav-icon far fa-circle text-warning\"></i>
              <p class=\"text\">KMS Silsilah</p>
            </a>
          </li>
          <li class=\"nav-item\">
            <a href=\"https://tari.oss.web.id\" class=\"nav-link\">
              <i class=\"nav-icon far fa-circle text-info\"></i>
              <p class=\"text\">KMS Tari</p>
            </a>
          </li>
        </ul>
      </nav>
      
      </div>
    <!-- /.sidebar -->
  </aside>
      ";


		$view .= "<div class=\"content-wrapper\">
    
		    <section class=\"content-header\">
		      <div class=\"container-fluid\">
		        <div class=\"row mb-2\">
		          <div class=\"col-sm-6\">
		            <h1>Browsing Result</h1>
		          </div>
		          <div class=\"col-sm-6\">
		            <ol class=\"breadcrumb float-sm-right\">
		              <li class=\"breadcrumb-item\"><a href=\"#\">Home</a></li>
		              <li class=\"breadcrumb-item\"><a href=\"#\">Browsing</a></li>
		              <li class=\"breadcrumb-item active\">".$instances."</li>
		            </ol>
		          </div>
		        </div>
		      </div>
		    </section>

		    
		<section class=\"content\">
		      
		      
		<div class=\"container-fluid\">
        <div class=\"row\">   
          <!-- /.col -->
          <div class=\"col-md-12\">
            <!-- Widget: user widget style 1 -->
            <div class=\"card card-widget widget-user\">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class=\"widget-user-header text-white\" style=\"background: url('https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.0.4/img/photo2.png') center center;\">
                <h2><b>".$instances."</b></h2>
                <h5>Instances Gamelan</h5>
              </div>
            </div>
            <!-- /.widget-user -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
        
        <div class=\"row\">
          <div class=\"col-md-3\">
            <div class=\"card card-primary card-outline\">
              <div class=\"card-body box-profile\">


                <h3 class=\"profile-username text-center\">Instance's Attribute</h3>
                <br>


                <ul class=\"list-group list-group-unbordered mb-3\">

                  <li class=\"list-group-item\">
                    <b>Type</b> <a class=\"float-right\">Individual</a>
                  </li>
                  <li class=\"list-group-item\">
                    <b>Domain</b> <a class=\"float-right\">Gamelan</a>
                  </li>
                  <li class=\"list-group-item\">
                    <b>Prefix</b> <a class=\"float-right\">Tri Hita Karana</a>
                  </li>
                </ul>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <div class=\"card card-primary card-outline\">
              <div class=\"card-header\">
                <h3 class=\"card-title\">Gambar</h3>
              </div>
              <!-- /.card-header -->
              <div class=\"card-body\">
                <div id=\"carouselExampleIndicators\" class=\"carousel slide pointer-event\" data-ride=\"carousel\">
                  <ol class=\"carousel-indicators\">
                    <li data-target=\"#carouselExampleIndicators\" data-slide-to=\"0\" class=\"active\"></li>
                    <li data-target=\"#carouselExampleIndicators\" data-slide-to=\"1\" class=\"\"></li>
                    <li data-target=\"#carouselExampleIndicators\" data-slide-to=\"2\" class=\"\"></li>
                  </ol>
                  <div class=\"carousel-inner\">
                    <div class=\"carousel-item active\">
                      <img class=\"d-block w-100\" src=\"https://placehold.it/900x500/39CCCC/ffffff&amp;text=Gambar+tidak+ditemukan!\" alt=\"First slide\">
                    </div>
                    <div class=\"carousel-item\">
                      <img class=\"d-block w-100\" src=\"https://placehold.it/900x500/3c8dbc/ffffff&amp;text=Gambar+tidak+ditemukan!\" alt=\"Second slide\">
                    </div>
                    <div class=\"carousel-item\">
                      <img class=\"d-block w-100\" src=\"https://placehold.it/900x500/f39c12/ffffff&amp;text=Gambar+tidak+ditemukan!\" alt=\"Third slide\">
                    </div>
                  </div>
                  <a class=\"carousel-control-prev\" href=\"#carouselExampleIndicators\" role=\"button\" data-slide=\"prev\">
                    <span class=\"carousel-control-prev-icon\" aria-hidden=\"true\"></span>
                    <span class=\"sr-only\">Previous</span>
                  </a>
                  <a class=\"carousel-control-next\" href=\"#carouselExampleIndicators\" role=\"button\" data-slide=\"next\">
                    <span class=\"carousel-control-next-icon\" aria-hidden=\"true\"></span>
                    <span class=\"sr-only\">Next</span>
                  </a>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <div class=\"card card-primary card-outline\">
              <div class=\"card-header\">
                <h3 class=\"card-title\">Peta</h3>
              </div>
              <!-- /.card-header -->
              <div class=\"card-body\">
                <iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d252651.90699362624!2d115.20490557531869!3d-8.333960489431096!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd21e398e4623fd%3A0x3030bfbca7cbef0!2sKabupaten+Bangli%2C+Bali!5e0!3m2!1sid!2sid!4v1488352315556\" width=\"100%\" height=\"320\" frameborder=\"0\" style=\"border:0\" allowfullscreen></iframe>
              </div>
              <!-- /.card-body -->
            </div>
          </div>
          <!-- /.col-md-3 -->
          <div class=\"col-md-9\">
            <div class=\"card card-primary card-outline\">
              <div class=\"card-body box-profile\">
                
                <h3 class=\"profile-username text-center\">Tentang Instances</h3>
              
                ".$stringSpecificProperty."

                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col-md-3 -->
        </div>
        <!-- /.row -->
        
        </div>

      </div>
      <!-- /.container-fluid -->
		";
		$view .= "

    </section>
    <!-- /.content -->
    <a id=\"back-to-top\" href=\"#\" class=\"btn btn-primary back-to-top\" role=\"button\" aria-label=\"Scroll to top\">
      <i class=\"fas fa-chevron-up\"></i>
    </a>
  </div>
  <!-- /.content-wrapper -->

  <footer class=\"main-footer\">
    <div class=\"float-right d-none d-sm-block\">
      <b>Version</b> 1.0.2
    </div>
    <strong>Copyright © 2019-2020 <a href=\"http://gamelan.oss.web.id\">KMS Gamelan Lite</a>.</strong> All rights
    reserved.
  </footer>

  

  <!-- Control Sidebar -->
  <aside class=\"control-sidebar control-sidebar-dark\">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js\" integrity=\"sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=\" crossorigin=\"anonymous\"></script>
<!-- Bootstrap 4 -->
<script src=\"https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js\"></script>
<!-- AdminLTE App -->
<script src=\"https://cdn.bootcss.com/admin-lte/3.0.2/js/adminlte.min.js\"></script>
<!-- AdminLTE for demo purposes -->
<script src=\"https://cdn.jsdelivr.net/npm/admin-lte@3.0.2/dist/js/demo.js\"></script>
</body>
</html>
";
		return $view;
	}
?>

      

<script languange="javascript">
  $(function () {
    //Initialize Select2 Elements
    //$('.select2').select2()

    // mengambil referensi semua dropdown
	var vOutput = document.getElementById('cboutput');
	var vAktivitas = document.getElementById('cbinputaktivitas');
	var vAnsambel = document.getElementById('cbinputansambel');
	var vInstrumen = document.getElementById('cbinputinstrumen');
	var vFitur = document.getElementById('cbinputfitur');
	var vPeran = document.getElementById('cbinputperan');
	var vSumberSuara = document.getElementById('cbinputsumbersuara');
	var vLaras = document.getElementById('cbinputlaras');
	var wadahQuery = document.getElementById('wadahQuery');/**/
	var p = document.getElementById('wadahError');
	var wadahHasil = document.getElementById('wadahHasil');

	
    /*$error = 0;
    if(vOutput.value == ""){
      $error = 1;
    }

    // menampilkan data inputan semua dropdown
    document.getElementById('showVal').onclick = function () {
        wOutput.value = vOutput.value;  
        wAktivitas.value = vAktivitas.value;
        wAnsambel.value = vAnsambel.value;  
        wInstrumen.value = vInstrumen.value;
        wFitur.value = vFitur.value;
        wPeran.value = vPeran.value;
        wSumberSuara.value = vSumberSuara.value;
        wLaras.value = vLaras.value;
    }*/

    document.getElementById('resetPencarian').onclick = function () {
      vOutput.selectedIndex = 0;
      vAktivitas.selectedIndex = 0;
      vAnsambel.selectedIndex = 0;
      vInstrumen.selectedIndex = 0;
      vFitur.selectedIndex = 0;
      vPeran.selectedIndex = 0;
      vSumberSuara.selectedIndex = 0;
      vLaras.selectedIndex = 0;
    }

    document.getElementById('viewData').onclick = function () {
      
      if(vOutput.value == ""){ //Jika output belum dipilih maka muncul error
        wadahHasil.style.display = "none";
        p.style.display = "block";

      }
      	else{ //Tapi jika output telah dipilih maka jalankan fungsi query
			wadahHasil.style.display = "block";
			p.style.display = "none";


			//insert query
			wadahQuery.value = "SELECT DISTINCT (?"+vOutput.value+" as ?output) { "+vAktivitas.value+" "+vAnsambel.value+" "+vInstrumen.value+" "+vFitur.value+" "+vPeran.value+" "+vSumberSuara.value+" "+vLaras.value+" } ORDER BY ?output";
			
			//tampilkan hasil query

			/*if ($(wadahQuery).val() != 0) {
				$.post("searchingResult.php", {
					variable:wadahQuery
				}, 
				function(data) {
					if (data != "") {
						alert('We sent Jquery string to PHP : ' + data);
					}
				});
			}
			var xhr = new XMLHttpRequest();
			xhr.open("POST", yourUrl, true);
			xhr.setRequestHeader('Content-Type', 'application/json');
			xhr.send(JSON.stringify({
				value: wadahQuery
			}));

			var http1 = new XMLHttpRequest();
			http1.open("POST","searchingResult.php",true);
			
			// Set headers
			http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			http.setRequestHeader("Content-length", params.length);
			http.setRequestHeader("Connection", "close");

			http.onreadystatechange = function(){
				if(http.readyState == 4 && http.status == 200){
					document.getElementById("response").innerHTML = http.responseText;
				}
			}

			http.send(params);
			formsubmission.preventDefault();

			var http = new XMLHttpRequest();
			var url = 'searchingResult.php';
			var params = 'data=ipsum&wadahQuery='+wadahQuery.value;
			http.open('POST', url, true);
			alert('We sent Jquery string to PHP : ' + params);
			//Send the proper header information along with the request
			http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

			http.onreadystatechange = function() {//Call a function when the state changes.
				if(http.readyState == 4 && http.status == 200) {
					alert(http.responseText);
				}
			}
			http.send(params);*/

			

    	}
	  	
		
		
    }
})
</script>



<script languange="javascript">
					$(function(){
					  $("#header").load("header.html"); 
					  $("#footer").load("footer.html"); 
					});

					function viewDivOutput(){
						var t = document.getElementById('cboutput');
						var selectedText = t.options[t.selectedIndex].text;
						var v_output = $('#cboutput').val();
						$('#output').val(v_output);
						$('#output_view').val(selectedText);
					}
					function viewInputTempat(value1,value2){
						var y = document.getElementById('divtempat');
						y.style.display = 'block';
						$('#inpt_tempat').val(value1);
						$('#divtempat').html('<button type="button" title="".DELETE_INPUT_FILTER."" onclick="$(this).remove();" class="btn btn-labeled btn-primary btn-xs"><span class="btn-label"><i class="glyphicon glyphicon-remove"></i></span>".LOCATION." : '+value2+'</button>')
					}
					function viewInputActivity(value1,value2){
						var y = document.getElementById('divactivity');
						y.style.display = 'block';
						$('#inpt_activity').val(value1);
						$('#divactivity').html('<button type="button" title="".DELETE_INPUT_FILTER."" onclick="$(this).remove();" class="btn btn-labeled btn-primary btn-xs"><span class="btn-label"><i class="glyphicon glyphicon-remove"></i></span>".ACTIVITY." : '+value2+'</button>')
					}
					function viewInputUkuran(value1,value2){
						var y = document.getElementById('divukuran');
						y.style.display = 'block';
						$('#inpt_ukuran').val(value1);
						$('#divukuran').html('<button type="button" title="".DELETE_INPUT_FILTER."" onclick="$(this).remove();" class="btn btn-labeled btn-primary btn-xs"><span class="btn-label"><i class="glyphicon glyphicon-remove"></i></span>".DIMENSION." : '+value2+'</button>')
					}
					function viewInputJumlah(value){
						var y = document.getElementById('divjumlah');
						y.style.display = 'block';
						$('#inpt_jumlah').val(value);
						$('#divjumlah').html('<button type="button" title="".DELETE_INPUT_FILTER."" onclick="$(this).remove();" class="btn btn-labeled btn-primary btn-xs"><span class="btn-label"><i class="glyphicon glyphicon-remove"></i></span>".NUMBER_KULKUL." : '+value+'</button>')
					}
					function viewInputPengangge(value1,value2){
						var y = document.getElementById('divpengangge');
						y.style.display = 'block';
						$('#inpt_pengangge').val(value1);
						$('#divpengangge').html('<button type="button" title="".DELETE_INPUT_FILTER."" onclick="$(this).remove();" class="btn btn-labeled btn-primary btn-xs"><span class="btn-label"><i class="glyphicon glyphicon-remove"></i></span>".PENGANGGE." : '+value2+'</button>')
					}
					function viewInputArahKulkul(value1,value2){
						var y = document.getElementById('divarah');
						y.style.display = 'block';
						$('#inpt_arah').val(value1);
						$('#divarah').html('<button type="button" title="".DELETE_INPUT_FILTER."" onclick="$(this).remove();" class="btn btn-labeled btn-primary btn-xs"><span class="btn-label"><i class="glyphicon glyphicon-remove"></i></span>".ARAH_KULKUL." : '+value2+'</button>')
					}
					function viewInputBahanKulkul(value1,value2){
						var y = document.getElementById('divbahanbaku');
						y.style.display = 'block';
						$('#inpt_bahanbaku').val(value1);
						$('#divbahanbaku').html('<button type="button" title="".DELETE_INPUT_FILTER."" onclick="$(this).remove();" class="btn btn-labeled btn-primary btn-xs"><span class="btn-label"><i class="glyphicon glyphicon-remove"></i></span>".BAHAN_BAKU_KULKUL." : '+value2+'</button>')
					}
					function viewInputSuaraKulkul(value1,value2){
						var y = document.getElementById('divsuara');
						y.style.display = 'block';
						$('#inpt_suara').val(value1);
						$('#divsuara').html('<button type="button" title="".DELETE_INPUT_FILTER."" onclick="$(this).remove();" class="btn btn-labeled btn-primary btn-xs"><span class="btn-label"><i class="glyphicon glyphicon-remove"></i></span>".SUARA_KULKUL." : '+value2+'</button>')
					}
					function viewInputSoundType(value1,value2){
						var y = document.getElementById('divtipesuara');
						y.style.display = 'block';
						$('#inpt_tipesuara').val(value1);
						$('#divtipesuara').html('<button type="button" title="".DELETE_INPUT_FILTER."" onclick="$(this).remove();" class="btn btn-labeled btn-primary btn-xs"><span class="btn-label"><i class="glyphicon glyphicon-remove"></i></span>".SOUND_TYPE." : '+value2+'</button>')
					}
					
					function resetPencarian() {
						var vOutput = document.getElementById('cboutput');
						var vAktivitas = document.getElementById('cbinputaktivitas');
						var vAnsambel = document.getElementById('cbinputansambel');
						var vInstrumen = document.getElementById('cbinputinstrumen');
						var vFitur = document.getElementById('cbinputfitur');
						var vPeran = document.getElementById('cbinputperan');
						var vSumberSuara = document.getElementById('cbinputsumbersuara');
						var vLaras = document.getElementById('cbinputlaras');
						vOutput.selectedIndex = 0;
					    vAktivitas.selectedIndex = 0;
					    vAnsambel.selectedIndex = 0;
					    vInstrumen.selectedIndex = 0;
					    vFitur.selectedIndex = 0;
					    vPeran.selectedIndex = 0;
					    vSumberSuara.selectedIndex = 0;
					    vLaras.selectedIndex = 0;
					}
					function viewData(){
						// mengambil referensi semua dropdown
						var vOutput = document.getElementById('cboutput');
						var vAktivitas = document.getElementById('cbinputaktivitas');
						var vAnsambel = document.getElementById('cbinputansambel');
						var vInstrumen = document.getElementById('cbinputinstrumen');
						var vFitur = document.getElementById('cbinputfitur');
						var vPeran = document.getElementById('cbinputperan');
						var vSumberSuara = document.getElementById('cbinputsumbersuara');
						var vLaras = document.getElementById('cbinputlaras');
						var wadahQuery = document.getElementById('wadahQuery');/**/
						var p = document.getElementById('wadahError');
						var wadahHasil = document.getElementById('wadahHasil');
						
						wadahHasil.style.display = "none";
						p.style.display = "none";

						if(vOutput.value == ""){ //Jika output belum dipilih maka muncul error
						wadahHasil.style.display = "none";
						p.style.display = "block";

						}
						else{ //Tapi jika output telah dipilih maka jalankan fungsi query
							wadahHasil.style.display = "block";
							p.style.display = "none";
							var urltarget = '?action=viewdata';
							var query = $('#formSearch').serialize();

							//alert(' : ' + query);
							$.ajax({
								type: 'POST',
								url: urltarget,
								data: query,
								success: function(response){
									response = response.replace(/^s+|s+$/g,'');
									$('#wadahHasil').html(response);
									//alert('Success! ' + response);
								}
							});
							
						}
					}
					
				</script>
	<?php
	if (!stripos($_SERVER["PHP_SELF"],"modules")){
		
		if(!isset($_GET['action']) && !isset($_GET['value'])){
			//echo headerHTML(createHeader($_REQUEST),$_REQUEST);
			echo viewTemplate($_REQUEST);
			//echo footerHTML($_REQUEST);
			//echo "Action is empty";
		}else{
			$action=$_GET['action'];
			$value=$_GET['value'];
			if ($action=="viewlink"){
				echo viewFormSearch($_REQUEST);
				//echo "Action is not empty";
			}
		}

	}else{
		echo "<script type=\"text/javascript\">location.href = './index.php';</script>";
	}
	?>

