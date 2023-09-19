<?php
	try{
	$db	=	new PDO("mysql:host=localhost;dbname=crud;charset=UTF8", "root", "");
	}catch(PDOException $Hata){
		echo "Bağlantı Hatası<br />" . $Hata->GetMessage();
		die();
	}
?>