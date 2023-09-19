<?php
function upload_image(){
	if(isset($_FILES["image_user"])){
		$extensao = explode('.', $_FILES['image_user']['name']);
		$novo_name = rand() . '.' . $extensao[1];
		$destino = '../upload/' . $novo_name;
		move_uploaded_file($_FILES['image_user']['tmp_name'], $destino);
		return $novo_name;
	}
}
function get_imagem_nome($del_id){
	include('config.php');
	$sql = $db->prepare("SELECT image FROM users WHERE id = '$del_id'");
	$sql->execute();
	$result = $sql->fetchAll();
	foreach($result as $row){
		return $row["image"];
	}
}
function get_total_registros(){
	include('config.php');
	$sql = $db->prepare("SELECT * FROM users");
	$sql->execute();
	$result = $sql->fetchAll();
	return $sql->rowCount();
}

?>