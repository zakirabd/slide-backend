	<?php

	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: *");


	include_once('dbConnect.php');
	

	class MainSlideClass {
		public $link;

		function __construct(){
			$db_connection = new dbConnection();
			$this->link = $db_connection->connect();
			return $this->link;
		}
// ------------------------IPLOAD IMAGES------------------
		function uploadImage ($file) {
			try{
				@$uploads_dir = "rm";
				@$tmp_name = $file["tmp_name"];
				@$name = $file["name"];

				$randnumber1=rand(20000,32000);
				$randnumber2=rand(20000,32000);
				$randnumber3=rand(20000,32000);
				$randnumber4=rand(20000,32000);

				$randname=$randnumber1.$randnumber2.$randnumber3.$randnumber4.".jpg";

				$photofile = substr($uploads_dir, 3)."/".$randname;
				move_uploaded_file($tmp_name, "$uploads_dir/$randname");

				return $photofile;
			}catch(PDOException $e){
				return $e->getMessage();
			}
			
        }
// ---------------------insert presentation image---------------------

        function InsertPresentationImage($id, $subject, $image){
        	try{

        		if ($image !== null && trim($subject !== '')) {

	        		$images = $this->uploadImage($image);
					if (intval($id) > 0 ) {
						$query = $this->link->query("UPDATE `presentations` SET `subject`='{$subject}',`image`= '{$images}' WHERE id = $id");
				  		

					} else{
						$query = $this->link->prepare("INSERT INTO `presentations`(`subject`, `image`) VALUES (?,?)");
						$values = array($subject, $images);
						$query->execute($values);
						$counts = $query->rowCount();
						return $counts;
						
					}
		   
				}

        	}catch(PDOException $e){
				return $e->getMessage();
			}

        }

// --------------------insert slide images----------------------------

         function InsertSlideImage($id, $image){

         	try{

         		if ($image !== null) {

	        		$images = $this->uploadImage($image);

					if ($id == '') {

				  		$query = $this->link->prepare("INSERT INTO `slider`(`image`) VALUES (?)");
						$values = array($images);
						$query->execute($values);

						$counts = $query->rowCount();

						return $counts;

					} else if( $id !== ''){

						$query = $this->link->query("UPDATE `slider` SET `image`= '{$images}' WHERE ID = $id");

					}
		   
				}

         	}catch(PDOException $e){
				return $e->getMessage();
			}

        }
// ----------------- select presentation------------------------------
		function SelectPresentation() {
			try{
				$presentations = [];

				$query = $this->link->query("SELECT * FROM `presentations` ORDER by id DESC ");
				$rowcount = $query->rowCount();
				$result = $query->fetchAll();
				
				for ($i = 0; $i < $rowcount; $i++){
					array_push($presentations, [
						'id' 	  => $result[$i]['ID'],
						'subject' => $result[$i]['subject'],
						'image'   => $result[$i]['image']
					]);
				}

				return $presentations;

			}catch(PDOException $e){
				return $e->getMessage();
			}
			
		}
// ----------------------select slides------------------------------
		function SelectSlides(){
			try{
				$slides = [];

				$query = $this->link->query("SELECT * FROM `slider` ORDER by ID DESC");
				$rowcount = $query->rowCount();
				$result = $query->fetchAll();

				for ($i = 0; $i < $rowcount; $i++){
					array_push($slides, [
						'id' 	  => $result[$i]['ID'],
						'image'   => $result[$i]['image']
					]);
				}

				return $slides;
			}catch(PDOException $e){
				return $e->getMessage();
			}
			
		}
// -------------------------insert links----------------------------------
		function InsertLinks($postdata, $conn){
			
			try{
				if (isset($postdata) && !empty($postdata)) {

				 	$request = json_decode($postdata); 


				 	$id = mysqli_real_escape_string($conn, htmlspecialchars(trim($request->id)));
				 	$instagram = mysqli_real_escape_string($conn, htmlspecialchars(trim($request->instagram)));
				 	$facebook = mysqli_real_escape_string($conn, htmlspecialchars(trim($request->facebook)));
				 	$email = mysqli_real_escape_string($conn, htmlspecialchars(trim($request->email)));
				 	$whatsapp = mysqli_real_escape_string($conn, htmlspecialchars(trim($request->whatsapp)));

				 	if ($id == '') {
						if(!empty($instagram) || !empty($facebook) || !empty($email) || !empty($whatsapp) ){
					      	$query = $this->link-prepare("INSERT INTO `links`(`facebook`, `instagram`, `email`, `whatsapp`) VALUES (?,?,?,?)");
					      	$values = array($facebook, $instagram, $email, $whatsapp);
					      	$query->execute($values);
					      	$counts = $query->rowCount();
							return $counts;
					    }
					}else if ($id !== '') {
						if ($instagram !== '') {

							$query = $this->link->query("UPDATE `links` SET `instagram`= '$instagram' WHERE ID = $id");

			      		} else if ($facebook !== '') {

			      			$query = $this->link->query("UPDATE `links` SET `facebook`= '$facebook' WHERE ID = $id");

			      		}else if ($email !== '') {

			      			$query = $this->link->query("UPDATE `links` SET `email`= '$email' WHERE ID = $id");

			      		}else if ($whatsapp !== '') {

			      			$query = $this->link->query("UPDATE `links` SET `whatsapp`= '$whatsapp' WHERE ID = $id");	
			      		}
					}
				 }


			}catch(PDOException $e){
				return $e->getMessage();
			}

		}
// ------------------------select links ----------------------------------
		function SelectLinks(){
			try{
				$links = [];

				$query = $this->link->query("SELECT * FROM `links`");
				$rowcount = $query->rowCount();
				$result = $query->fetchAll();

				for ($i = 0; $i < $rowcount; $i++){
					array_push($links, [
						'id' 	    => $result[$i]['ID'],
						'facebook'  => $result[$i]['facebook'],
						'instagram' => $result[$i]['instagram'],
						'email'     => $result[$i]['email'],
						'whatsapp'  => $result[$i]['whatsapp']
					]);
				}

				return $links;

			}catch(PDOException $e){
				return $e->getMessage();
			}
		}
// ---------------------insert online orders--------------------------------------------
		function insertOnlineOrder($postdata, $conn){
			try{

				if (isset($postdata) && !empty($postdata)) {
				 	$request = json_decode($postdata); 
				 	
				 	$topic = mysqli_real_escape_string($conn, htmlspecialchars(trim($request->topic)));
				 	$page_number = mysqli_real_escape_string($conn, htmlspecialchars(trim($request->page)));
				 	$number = mysqli_real_escape_string($conn, htmlspecialchars(trim($request->number)));


			      if(!empty($topic)){

			      	$db = "INSERT INTO `orders`(`topic`, `pageNumber`, `number`) 
			      			VALUES ('{$topic}', '{$page_number}', '{$number}')";

			      	$query = $this->link->prepare("INSERT INTO `orders`(`topic`, `pageNumber`, `number`) 
			      			VALUES (?,?,?)");
			      	$values = array($topic, $page_number, $number);
			      	$query->execute($values);
			      	
			      }
				 	
				}


			}catch(PDOException $e){
				return $e->getMessage();
			}
		}
// --------------------------------------------------------------------------------------
// ----------------SELECT ALL ORDERS FUNCTION -------------------------------------------
		function SelectAllOrders ($rowcount, $result){
			try{

				$orders = [];

				for ($i = 0; $i < $rowcount; $i++){
					array_push($orders, [
						'id' 	     => $result[$i]['ID'],
						'topic'      => $result[$i]['topic'],
						'pageNumber' => $result[$i]['pageNumber'],
						'number'     => $result[$i]['number'],
						'readyDate'  => $result[$i]['readyDate']
					]);
				}

				return $orders;

			}catch(PDOException $e){
				return $e->getMessage();
			}
		}
//-----------------------select new orders--------------------
		function SelectNewOrders(){
			try{

				$query = $this->link->query("SELECT * FROM `orders` WHERE new = 1 ORDER by ID DESC");
				$rowcount = $query->rowCount();
				$result = $query->fetchAll();

				return $this->SelectAllOrders($rowcount, $result);

			}catch(PDOException $e){
				return $e->getMessage();
			}
		}
// -------------------------accept new orders ---------------------------
		function AcceptNewOrders($id){
			try{

				if(!empty($id)){
					$update = $this->link->query("UPDATE `orders` SET `new`= 0,`accept`= 1 WHERE ID = $id");

					return $this->SelectNewOrders();
				}


			}catch(PDOException $e){
				return $e->getMessage();
			}
		}
// ------------------------remove new orders------------------------------
		function RemoveNewOrders($id){
			try{

				if(!empty($id)){
					$update = $this->link->query("UPDATE `orders` SET `new`= 0,`remove`= 1 WHERE ID = $id");

					return $this->SelectNewOrders();
				}

			}catch(PDOException $e){
				return $e->getMessage();
			}
		}
// ----------------------- waiting orders------------------------------
		function WaitingOrders(){
			try{
				$query = $this->link->query("SELECT * FROM `orders` WHERE accept = 1 ORDER by ID DESC");
				$rowcount = $query->rowCount();
				$result = $query->fetchAll();

			  	return $this->SelectAllOrders($rowcount, $result);
			}catch(PDOException $e){
				return $e->getMessage();
			}

		}
// ---------------------------prepared orders--------------------------
		function PreparedOrders(){
			try{
					$query = $this->link->query("SELECT * FROM `orders` WHERE ready = 1 ORDER by ID DESC");
					$rowcount = $query->rowCount();
					$result = $query->fetchAll();

				  	return $this->SelectAllOrders($rowcount, $result);

			}catch(PDOException $e){
				return $e->getMessage();
			}
		}


		function ReadyOrders($id, $currentDate){
			try{
				if (!empty($id)) {

					$update = $this->link->query("UPDATE `orders` SET `accept`= 0,`ready`= 1, `readyDate` = '$currentDate' WHERE ID = $id");

					return $this->PreparedOrders();
					
				}

			}catch(PDOException $e){
				return $e->getMessage();
			}
		}
// ----------------------removed orders-----------------------------
		function RemovedOrders(){
			try{

				$query = $this->link->query("SELECT * FROM `orders` WHERE remove = 1 ORDER by ID DESC");
				$rowcount = $query->rowCount();
				$result = $query->fetchAll();

			  	return $this->SelectAllOrders($rowcount, $result);

			}catch(PDOException $e){
				return $e->getMessage();
			}
		}
// ---------------------TOTAL ORDERS--------------------

		function getAllTotals ($rowcount, $result){
			try{

				$total = [];

				for ($i = 0; $i < $rowcount; $i++){
					array_push($total, [
						'total' => $result[$i]['total']
					]);
				}

				return $total;

			}catch(PDOException $e){
				return $e->getMessage();
			}
		}

// --------------------total new orders---------------------
		function TotalNewOrders(){
			try{

				$query = $this->link->query("SELECT COUNT(ID) as total FROM `orders` WHERE new = 1");
				$rowcount = $query->rowCount();
				$result = $query->fetchAll();

			  	return $this->getAllTotals($rowcount, $result);

			}catch(PDOException $e){
				return $e->getMessage();
			}
		}
// --------------------total waiting orders---------------------
		function TotalWaitingOrders(){
			try{

				$query = $this->link->query("SELECT COUNT(ID) as total FROM `orders` WHERE accept = 1");
				$rowcount = $query->rowCount();
				$result = $query->fetchAll();

			  	return $this->getAllTotals($rowcount, $result);

			}catch(PDOException $e){
				return $e->getMessage();
			}
		}

	
// --------------------total prepared orders---------------------
		function TotalPreparedOrders(){
			try{

				$query = $this->link->query("SELECT COUNT(ID) as total FROM `orders` WHERE ready = 1");
				$rowcount = $query->rowCount();
				$result = $query->fetchAll();

			  	return $this->getAllTotals($rowcount, $result);

			}catch(PDOException $e){
				return $e->getMessage();
			}
		}
// -----------------total removed orders-------------------
		function TotalRemovedOrders(){
			try{

				$query = $this->link->query("SELECT COUNT(ID) as total FROM `orders` WHERE remove = 1");
				$rowcount = $query->rowCount();
				$result = $query->fetchAll();

			  	return $this->getAllTotals($rowcount, $result);

			}catch(PDOException $e){
				return $e->getMessage();
			}
		}
// ----------------------------------------------------------------------------
// _----------------------------INSERT NEW USERS----------------------------
// -----------------------------------------------------------------------------
		function insertNewUser($postdata, $conn){
			try{
				if (isset($postdata) && !empty($postdata)) {
				 	$request = json_decode($postdata); 

				 	$id = mysqli_real_escape_string($conn, htmlspecialchars(trim($request->id)));
				 	$fullname = mysqli_real_escape_string($conn, htmlspecialchars(trim($request->fullname)));
				 	$username = mysqli_real_escape_string($conn, htmlspecialchars(trim($request->username)));
				 	$password = mysqli_real_escape_string($conn, htmlspecialchars(trim(md5($request->password))));
				 	$duty = mysqli_real_escape_string($conn, htmlspecialchars(trim($request->duty)));

				 	if($id == '') {

				 		$query = $this->link->prepare("INSERT INTO `users`(`fullname`, `username`, `password`, `duty`) 
				 				VALUES (?, ?, ?, ?)");
				 		$values = array($fullname, $username, $password, $duty);
				 		$query->execute($values);
				 		
				 	}
				 	else if($id !== '' AND $request->password !== ''){

				 		$query = $this->link->query("UPDATE `users` SET `fullname`= '$fullname', `username`= '$username', 
				 				`password`= '$password',`duty`= '$duty' WHERE ID = $id");

				 	}else if ($id !== '' AND $request->password == '') {
				 		$query = $this->link->query("UPDATE `users` SET `fullname`= '$fullname', `username`= '$username', 
				 				`duty`= '$duty' WHERE ID = $id");				 	}
				}

			}catch(PDOException $e){
				return $e->getMessage();
			}
		}
// --------------------------------select users---------------------------------

		function fetchAllUsers(){
			try{

				$query = $this->link->query("SELECT * FROM `users`");
				$rowcount = $query->rowCount();
				$result = $query->fetchAll();
			  	
				$sers = [];

				for ($i = 0; $i < $rowcount; $i++){
					array_push($sers, [
						'id' => $result[$i]['ID'],
						'fullname' => $result[$i]['fullname'],
						'username' => $result[$i]['username'],
						'password' => $result[$i]['password'],
						'duty' => $result[$i]['duty'],
					]);
				}

				return $sers;

			}catch(PDOException $e){
				return $e->getMessage();
			}
		}
// ------------------------delete users--------------------------
		function deleteUser($id){
			try{
				if($id !== ''){
					$query = $this->link->query("DELETE FROM `users` WHERE ID = $id");

					return $this->fetchAllUsers();
				}

			}catch(PDOException $e){
				return $e->getMessage();
			}
		}
// ---------------------LOGIN AUTHENTICATION--------------------------
		function login($postdata, $conn){
			try{

				if (isset($postdata) && !empty($postdata)) {
				 	$request = json_decode($postdata); 
				 	
				 	$username = $request->username;
				 	$password = md5($request->password);

				 	if ($username !=='' AND $request->password !=='') {

					    $query = $this->link->query("SELECT * FROM `users` WHERE username = '$username' 
					    		AND password = '$password'");
					    $rowcount = $query->rowCount();
					    $result = $query->fetchAll();
					   

					    if ($rowcount > 0) {

					    	 $duty = md5($result[0]['duty']);
					    	 return $correct = array("status" => 'ok', "duty" => $duty);
					    	 

					    }else{

					    	return $incorrect = array("status" => 'incorrect');
					    	
					    }
				 	}else{
				 		return $incorrect = array("status" => 'empty');
				 	}

				}

			}catch(PDOException $e){
				return $e->getMessage();
			}

		}

	}



	
?>