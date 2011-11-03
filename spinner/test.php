<?php 

//les parametres :
			$texte = "bonjour papa";
			$pass="	2ggSjnt6a1Ui0ktnWSf3Xx1eldrWjpnk7z WX/UW5Q0=";
			
			$body = "inputstring=".$texte."dictionary=both&interfLang=fr&lang=fr&language=fr&passPhrase=".$pass;
           
	


           //l'url
           $c = curl_init ("http://www.reverso.net/orthographe/correcteur-francais/SpellerRequests.aspx");
           
           //options de cURL. pas touche
               curl_setopt ($c, CURLOPT_POST, true);
               curl_setopt ($c, CURLOPT_POSTFIELDS, $body);
               curl_setopt ($c, CURLOPT_RETURNTRANSFER, true);

               
               //le résultat est stocké dans $page
               $page = curl_exec ($c);
               
               // fermeture de la session cURL
               curl_close ($c);
               
               
//                header('Content-type: text/html; charset=UTF-8', true);
               //restitution
               echo stripslashes($page);
?>
