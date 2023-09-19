<?php
include('config.php');
include('function.php');
if(isset($_POST["Kayit"])){ 
	if($_POST["Kayit"] == "Add"){
		$image = '';
		if($_FILES["image_user"]["name"] != ''){
			$image = upload_image();
		}
		$sql = $db->prepare("INSERT INTO users (name, surname, image)VALUES(:name, :surname, :image)");
		$result = $sql->execute(
			array(
				':name'		=>	$_POST["name"],
				':surname'	=>	$_POST["surname"],
				':image'	=>	$image
			)
		);
		if(!empty($result))		{
			$msg = array(
				'type' => "success",
				'description' => "Kayıt İşlemi Başarılı",
			); 		
			header('Content-type: application/json');
			echo json_encode($msg);	
		}
	}
	if($_POST["Kayit"] == "Edit"){
		$image = '';
		if($_FILES["image_user"]["name"] != ''){
			$image = upload_image();
			if($image != ''){
				unlink("../upload/" . $_POST["hidden_user_image"]);
			}
		}else{
			$image = $_POST["hidden_user_image"];
 		}
 
		$sql = $db->prepare("UPDATE users SET name = :name, surname = :surname, image = :image WHERE id = :id");
 		$result = $sql->execute(array(
				':name'		=>	$_POST["name"],
				':surname'	=>	$_POST["surname"],
				':image'	=>	$image,
				':id'		=>	$_POST["user_id"]
			)
		);
		if(!empty($result)){
			$msg = array(
				'type' => "success",
				'description' => "Düzenleme İşlemi Başarılı",
			); 		
			header('Content-type: application/json');
			echo json_encode($msg);			
		}
	} 
}elseif(isset($_POST["del_id"])){
	$image = get_imagem_nome($_POST["del_id"]);
	if($image != ''){
		unlink("../upload/" . $image);
	}
	$sql = $db->prepare("DELETE FROM users WHERE id = :id");
	$result = $sql->execute(
		array(
			':id'	=>	$_POST["del_id"]
		)
	);
	if(!empty($result)){ 
		$msg = array(
			'type' => "success",
			'description' => "Silme İşlemi Başarılı",
		); 		
		header('Content-type: application/json');
		echo json_encode($msg);	
	}
}elseif(isset($_POST["edit_data"])){	
	$output = array();
	$sql = $db->prepare( "SELECT * FROM users WHERE id = '".$_POST["edit_data"]."' LIMIT 1" );
	$sql->execute();
	$result = $sql->fetchAll();
	foreach($result as $row){
		$output["name"] = $row["name"];
		$output["surname"] = $row["surname"];
		if($row["image"] != ''){
			$output['image'] = '<img src="upload/'.$row["image"].'" class="img-thumbnail" width="50" height="35" /><input type="hidden" name="hidden_user_image" value="'.$row["image"].'" />';
		}else{
			$output['image'] = '<input type="hidden" name="hidden_user_image" value="" />';
		}
	}
	echo json_encode($output);
	
}else{// Listele
	$query = '';
	$output = array();
	$query .= "SELECT * FROM users ";
	if(isset($_POST["search"]["value"])){
		$query .= 'WHERE name LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR surname LIKE "%'.$_POST["search"]["value"].'%" ';
	}
	if(isset($_POST["order"])){
		$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
	}else{
	$query .= 'ORDER BY id DESC ';
	}
	if($_POST["length"] != -1){
		$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
	}	
	$sql = $db->prepare($query);
	$sql->execute();	
	$result = $sql->fetchAll();
	$data = array();
	$contar_rows = $sql->rowCount();
	foreach($result as $row){
		$image = '';
		if($row["image"] != ''){
			$image = '<img src="upload/'.$row["image"].'" class="img-thumbnail " width="50" height="35" />';
		}else{
			$image = '';
		}
		$sub_array = array();
		$sub_array[] = $row["id"];
		$sub_array[] = $image;
		$sub_array[] = $row["name"];
 		$sub_array[] = $row["surname"];
		$sub_array[] = $row["tarih"];
		$sub_array[] = '<button type="button" name="update" id="'.$row["id"].'" class="btn btn-warning btn-xs update" >Update</button>';
		$sub_array[] = '<button type="button" name="delete" id="'.$row["id"].'" class="btn btn-danger btn-xs delete">Delete</button>';
		$data[] = $sub_array;
	}
	$output = array(
	 	"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"		=> 	$contar_rows,
		"recordsFiltered"	=>	get_total_registros(),
		"data"				=>	$data
	);
	echo json_encode($output);
}

 

?>