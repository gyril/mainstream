<?php
	$selection = strtolower($_GET['select']);
	$texte = $_POST['texte'];
	
	if(!empty($selection)) {
		$url = "http://www.crisco.unicaen.fr/des/synonymes/".$selection;

		$file = file_get_contents($url);
    	header('Content-type: text/xml');
    	echo $file;
    	exit();
    }
    
    if(!empty($texte)) {
    	$body = "content=".$texte."&repetition=10&submit=Générer";
    	$c = curl_init ("http://www.infowebmaster.fr/outils/content-spinning.php");
		curl_setopt ($c, CURLOPT_POST, true);
		curl_setopt ($c, CURLOPT_POSTFIELDS, $body);
		curl_setopt ($c, CURLOPT_RETURNTRANSFER, true);
		$page = curl_exec ($c);
		curl_close ($c);
//		header('Content-type: text/html; charset=UTF-8', true);
		echo stripslashes($page);
    }
?>