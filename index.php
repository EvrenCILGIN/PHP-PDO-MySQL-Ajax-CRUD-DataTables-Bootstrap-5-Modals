<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/dataTables.bootstrap5.min.css">
    <title>PHP PDO MySQL Ajax CRUD DataTables Bootstrap 5 Modals İşlemleri</title>
</head>
<body>
    <div class="container">
        <div class="row">
			<div class="col-md-12">
				<h1 align="center">PHP PDO MySQL Ajax CRUD DataTables Bootstrap 5 Modals İşlemleri</h1>
				<div class="statusMsg"></div>
				<div align="right">
					<button type="button" id="add_button" data-bs-toggle="modal" data-bs-target="#userModal" aria-hidden="true" class="btn btn-success">Yeni Veri</button>
				</div><br>
 				<table id="user_data" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th width="5%">#id</th>
							<th width="5%">Foto</th>
							<th width="20%">Ad</th>
							<th width="20%">Soyad</th>
							<th width="20%">Tarih</th>
 							<th width="10%">Düzenle</th>
							<th width="10%">Sil</th>
						</tr>
					</thead>
				</table>
          </div>
        </div>
      </div>
    <script src="assets/js/bootstrap.bundle.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/jquery-3.7.0.js"></script>
    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.bootstrap5.min.js"></script>	  
</body>
</html>

<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form method="post" id="user_form" enctype="multipart/form-data">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Kayıt</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<label>Ad</label>
					<input type="text" name="name" id="name" class="form-control" />
					<br />
					<label>Soyad</label>
					<input type="text" name="surname" id="surname" class="form-control" />
					<br />
					<label>Resim</label>
					<br />
					<input type="file" name="image_user" id="image_user" />
					<span id="user_uploaded_image"></span>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="submit" name="action" id="action" value="" class="btn btn-success">Kaydet</button>
					<input type="hidden" name="user_id" id="user_id" />
					<input type="hidden" name="Kayit" id="Kayit" />	
				</div>
			</div>
		</form>
	</div>
</div>


	<script type="text/javascript" language="javascript" >
		$(document).ready(function(){	
			$('#add_button').click(function(){
				$('#user_form')[0].reset();
				$('.modal-title').text("Yeni Veri Ekle");
				$('#action').val("Add");
				$('#Kayit').val("Add");
				$('#user_uploaded_image').html('');
			});
			var dataTable = $('#user_data').DataTable({
				"processing":true,
				"serverSide":true,
				"order":[],
				"ajax":{
					url:"system/ajax.php",
					type:"POST"
				},
				"columnDefs":[
					{
						"targets":[1, 5, 6 ],
						"orderable":false,
					},
				],
				"language": {
				url: '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Turkish.json'
				}		

			});
			$(document).on('submit', '#user_form', function(e){
				e.preventDefault();
				var name = $('#name').val();
				var surname = $('#surname').val();
				var etx = $('#image_user').val().split('.').pop().toLowerCase();
				if(etx != ''){
					if(jQuery.inArray(etx, ['gif','png','jpg','jpeg']) == -1){
						alert("Sadece Görsel Resim Eklenebilir.");
							$('#image_user').val('');
							return false;
					}
				}	
				if(name != '' && surname != ''){
					$.ajax({
						url:"system/ajax.php",
						method:'POST',
						data:new FormData(this),
						contentType:false,
						processData:false,
						success:function(data){
							$('#user_form')[0].reset();
							$('#userModal').modal('hide');		
							// İşlem Sonrasında Modal Kaparmıyor?????????????
							alert(data.description);
							dataTable.ajax.reload();  
							// $('.statusMsg').html("<div id='alert' class='alert alert-success'> "+data.description+" </div>");							
 						}
					});
				}else{
					alert("Ad Soyad Alanları boş Bırakılamaz");
				}
			});
			$(document).on('click', '.update', function(){
				var edit_data = $(this).attr("id");
					$.ajax({
						url:"system/ajax.php",
						method:"POST",
						data:{edit_data:edit_data},
						dataType:"json",
						success:function(data){			
 							$('#userModal').modal('show');
							$('.modal-title').text("Düzenle");		
 							$('#user_id').val(edit_data);							
							$('#name').val(data.name);
							$('#surname').val(data.surname);
							$('#user_uploaded_image').html(data.image);
							$('#action').val("Edit");
							$('#Kayit').val("Edit");
						}
					})				
			});				
			$(document).on('click', '.delete', function(){
				var del_id = $(this).attr("id");
				if(confirm("Veriler Kalıcı olarak Silinecek Onaylıyormusunuz")){
					$.ajax({
						url:"system/ajax.php",
						method:"POST",
						data:{del_id:del_id},
						success:function(data){
							alert(data.description);
							dataTable.ajax.reload();
						}
					});
				}else{
					return false;	
				}
			});
		});

 
	</script>	
	
