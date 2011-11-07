<?php
 	$url = $_GET['select'];
	if(filter_var($url, FILTER_VALIDATE_URL) === FALSE)
		{
		echo "Ce n'est pas une URL valide, do it again";
		}
		else
		{
		$ftexte = fopen('lien', 'a+');	
		fputs($ftexte,$url."\r\n"); 
		fclose($ftexte);	
		echo "l'url a été ajoutée";
		}
?>
