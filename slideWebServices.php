<?php 
include 'connect.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");


// -------------UPLOAD IMAGE FUNCTION---------------

function uploadImage ($file) {
	@$uploads_dir = "rm";
	@$tmp_name = $file["tmp_name"];
	@$name = $file["name"];

	$randnumber1=rand(20000,32000);
	$randnumber2=rand(20000,32000);
	$randnumber3=rand(20000,32000);
	$randnumber4=rand(20000,32000);

	$randname=$randnumber1.$randnumber2.$randnumber3.$randnumber4;

	$photofile = substr($uploads_dir, 3)."/".$randname;
	@move_uploaded_file($tmp_name, "$uploads_dir/$randname");
	$image = "http://localhost/Slide%20Web%20Site/php/rm".$photofile;
	return $image;
}
//----------------- INSERT PRESENTATION IMAGES-------------------

if (isset($_GET['presentation']) && $_GET['presentation'] == 'insert' && mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['presentation'])))) {
       
	$subject = mysqli_real_escape_string($conn, htmlspecialchars(trim($_POST['subject'])));
	$id = $_POST['id'];
	
	if ($_FILES['image'] !== null && trim($subject !== '')) {

		$image = uploadImage($_FILES['image']);

		if ($id == '') {
			$db =  "INSERT INTO `presentations`(`subject`, `image`) VALUES ('{$subject}','{$image}')";
	  		 $sql=mysqli_query($conn, $db);
		} else if( $id !== ''){
			$db="UPDATE `presentations` SET `subject`='{$subject}',`image`= '{$image}' WHERE ID = $id";
			$sql=mysqli_query($conn, $db);
		}
	   
		
	}
		
}

// -----------------------INSERT SLAYD IMAGES-------------------------

if (isset($_GET['slayd']) && $_GET['slayd'] == 'insert' && mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['slayd'])))) {
       

	if ($_FILES['image'] !== null) {
		$id = $_POST['id'];

		$image = uploadImage($_FILES['image']);
		
		if ($id == '') {
			$db =  "INSERT INTO `slider`(`image`) VALUES ('{$image}')";
	   		$sql=mysqli_query($conn, $db);
		}else if($id !== '') {
			$db = "UPDATE `slider` SET `image`= '{$image}' WHERE ID = $id";
			$sql=mysqli_query($conn, $db);
		}
	  
		
	}
		
}

// -------------------SELECT PRESENTATION IMAGES-----------------------

if (isset($_GET['presentation']) && $_GET['presentation'] == 'select' && mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['presentation'])))) {

	$presentations = [];

	$db = "SELECT * FROM `presentations` ORDER by ID DESC";
	if ($result = mysqli_query($conn, $db)) {
		$cr = 0;
		while ($row = mysqli_fetch_assoc($result)) {
			$presentations[$cr]['id'] = $row['ID'];
			$presentations[$cr]['subject'] = $row['subject'];
			$presentations[$cr]['image'] = $row['image'];

			$cr++;
		}

		echo json_encode($presentations);
	}
	else {
		http_response_code(404);
	}
}

// ------------------------SELECT SLIDE IMAGES---------------------

if (isset($_GET['slide']) && $_GET['slide'] == 'select' && mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['slide'])))) {

	$slide = [];

	$db = "SELECT * FROM `slider` ORDER by ID DESC";
	if ($result = mysqli_query($conn, $db)) {
		$cr = 0;
		while ($row = mysqli_fetch_assoc($result)) {
			$slide[$cr]['id'] = $row['ID'];
			$slide[$cr]['image'] = $row['image'];

			$cr++;
		}

		echo json_encode($slide);
	}
	else {
		http_response_code(404);
	}
}
// --------------------------------------------------------------------
// ---------------------CONTACT LINKS SERVICES ------------------------
// --------------------------------------------------------------------

// ------------------------SELECT LINKS -----------------------------

if (isset($_GET['links']) && $_GET['links'] == 'select' && mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['links'])))) {

	$links = [];

	$db = "SELECT * FROM `links`";
	if ($result = mysqli_query($conn, $db)) {
		$cr = 0;
		while ($row = mysqli_fetch_assoc($result)) {
			$links[$cr]['id'] = $row['ID'];
			$links[$cr]['facebook'] = $row['facebook'];
			$links[$cr]['instagram'] = $row['instagram'];
			$links[$cr]['email'] = $row['email'];
			$links[$cr]['whatsapp'] = $row['whatsapp'];

			$cr++;
		}

		echo json_encode($links);
	}
	else {
		http_response_code(404);
	}
}

// ------------------------INSERT LINKS -----------------------------

if (isset($_GET['links']) && $_GET['links'] == 'insert' && mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['links'])))) {


	$postdata = file_get_contents("php://input");
	if (isset($postdata) && !empty($postdata)) {
	 	$request = json_decode($postdata); 

	 	$id = mysqli_real_escape_string($conn, htmlspecialchars(trim($request->id)));
	 	$instagram = mysqli_real_escape_string($conn, htmlspecialchars(trim($request->instagram)));
	 	$facebook = mysqli_real_escape_string($conn, htmlspecialchars(trim($request->facebook)));
	 	$email = mysqli_real_escape_string($conn, htmlspecialchars(trim($request->email)));
	 	$whatsapp = mysqli_real_escape_string($conn, htmlspecialchars(trim($request->whatsapp)));
	 	
	 	

		if ($id == '') {
			if(!empty($instagram) || !empty($facebook) || !empty($email) || !empty($whatsapp) ){
		      	$db = "INSERT INTO `links`(`facebook`, `instagram`, `email`, `whatsapp`) 
		      			VALUES ('{$facebook}', '{$instagram}', '{$email}', '{$whatsapp}')";
		      	$query = mysqli_query($conn, $db);
		    }
		}
		else if ($id !== '') {
			if ($instagram !== '') {
				$db = "UPDATE `links` SET `instagram`= '{$instagram}' WHERE ID = $id ";
      			$query = mysqli_query($conn, $db);		
      		} else if ($facebook !== '') {
				$db = "UPDATE `links` SET `facebook`= '{$facebook}' WHERE ID = $id ";
      			$query = mysqli_query($conn, $db);	
      		}else if ($email !== '') {
				$db = "UPDATE `links` SET `email`= '{$email}' WHERE ID = $id ";
      			$query = mysqli_query($conn, $db);		
      		}else if ($whatsapp !== '') {
				$db = "UPDATE `links` SET `whatsapp`= '{$whatsapp}' WHERE ID = $id ";
      			$query = mysqli_query($conn, $db);		
      		}
		}
	}
}

// ------------------------------------------------------------------------
// ------------------------------ONLINE ORDER SERVICES---------------------
// ------------------------------------------------------------------------

// ----------------------------------------------
// ------------------ INSERT NEW ORDERS ------------
// ----------------------------------------------

if (isset($_GET['onlineOrder']) && $_GET['onlineOrder'] == 'insert' && mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['onlineOrder'])))) {
		$postdata = file_get_contents("php://input");
	if (isset($postdata) && !empty($postdata)) {
	 	$request = json_decode($postdata); 
	 	
	 	$topic = mysqli_real_escape_string($conn, htmlspecialchars(trim($request->topic)));
	 	$page_number = mysqli_real_escape_string($conn, htmlspecialchars(trim($request->page)));
	 	$number = mysqli_real_escape_string($conn, htmlspecialchars(trim($request->number)));


      if(!empty($topic)){
      	$db = "INSERT INTO `orders`(`topic`, `pageNumber`, `number`) 
      			VALUES ('{$topic}', '{$page_number}', '{$number}')";
      	$query = mysqli_query($conn, $db);
      	
      }
	 	
	}
}
// ----------------------------------------------
// ------------------ SELECT ALL  ORDERS FUNCTIONS ------------
// ----------------------------------------------
function selectOrders ($conn, $db) {
	$orders = [];
	if ($result = mysqli_query($conn, $db)) {
		$cr = 0;
		while ($row = mysqli_fetch_assoc($result)) {
			$orders[$cr]['id'] = $row['ID'];
			$orders[$cr]['topic'] = $row['topic'];
			$orders[$cr]['pageNumber'] = $row['pageNumber'];
			$orders[$cr]['number'] = $row['number'];
			$orders[$cr]['readyDate'] = $row['readyDate'];

			$cr++;
		}

		echo json_encode($orders);
	}
	else {
		http_response_code(404);
	}
}


// ----------------------------------------------
// ------------------ NEW ORDERS ------------
// ----------------------------------------------

function fetchNewOrders($conn)
{
	$db = "SELECT * FROM `orders` WHERE new = 1 ORDER by ID DESC";
	selectOrders($conn, $db);
}

if (isset($_GET['newOrders']) && $_GET['newOrders'] == 'select' && mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['newOrders'])))) {
	fetchNewOrders($conn);
}

// --------accept new order-------
if (isset($_GET['newOrder']) && $_GET['newOrder'] == 'accept' && mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['newOrder'])))) {
	 	
	$id = mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['id'])));

	if(!empty($id)){
	  	$db = "UPDATE `orders` SET `new`= 0,`accept`= 1 WHERE ID = $id";
	  	$query = mysqli_query($conn, $db);
	  	fetchNewOrders($conn);
	}
}
// --------remove new order-------

if (isset($_GET['newOrder']) && $_GET['newOrder'] == 'remove' && mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['newOrder'])))) {
	 	
	$id = mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['id'])));

	if(!empty($id)){
      	$db = "UPDATE `orders` SET `new`= 0,`remove`= 1 WHERE ID = $id";
      	$query = mysqli_query($conn, $db);
      	fetchNewOrders($conn);
	}
}

// ----------------------------------------------
// ------------------ WAITING ORDERS ------------
// ----------------------------------------------

function readyWaitingOrders ($conn) {

	$db = "SELECT * FROM `orders` WHERE accept = 1 ORDER by ID DESC";
	selectOrders($conn, $db);
}

if (isset($_GET['newOrders']) && $_GET['newOrders'] == 'wait' && mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['newOrders'])))) {
	readyWaitingOrders($conn);
}


if (isset($_GET['newOrder']) && $_GET['newOrder'] == 'ready' && mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['newOrder'])))) {
	 	
 	$id = mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['id'])));
 	$time=time();
	$currentDate = date("Y-m-d",$time);

    if(!empty($id)){
      	$db = "UPDATE `orders` SET `accept`= 0,`ready`= 1, `readyDate` = '{$currentDate}' WHERE ID = $id";
      	$query = mysqli_query($conn, $db);
      	readyWaitingOrders($conn);
      	
    }

}

// ----------------------------------------------
// ------------------ PREPARED ORDERS ------------
// ----------------------------------------------

if (isset($_GET['newOrder']) && $_GET['newOrder'] == 'prepared' && mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['newOrder'])))) {

	$db = "SELECT * FROM `orders` WHERE ready = 1 ORDER by ID DESC";
	selectOrders($conn, $db);
}

// ----------------------------------------------
// ------------------ REMOVED ORDERS ------------
// ----------------------------------------------

if (isset($_GET['newOrders']) && $_GET['newOrders'] == 'remove' && mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['newOrders'])))) {

	$db = "SELECT * FROM `orders` WHERE remove = 1 ORDER by ID DESC";
	selectOrders($conn, $db);
	
}

// -------------------------------------------------------
// --------------- GET COUNTS ----------------------------
// -------------------------------------------------------

function getTotalOrders ($conn, $db){
	$total = [];
	if ($result = mysqli_query($conn, $db)) {
		$cr = 0;
		while ($row = mysqli_fetch_assoc($result)) {
			$orders[$cr]['total'] = $row['total'];

			$cr++;
		}

		echo json_encode($orders);
	}
	else {
		http_response_code(404);
	}
}

// counts of new orders

if (isset($_GET['newOrders']) && $_GET['newOrders'] == 'total' && mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['newOrders'])))) {
	
	$db = "SELECT COUNT(ID) as total FROM `orders` WHERE new = 1";
	getTotalOrders($conn, $db);
	
}

// counts of waiting orders

if (isset($_GET['waitingOrders']) && $_GET['waitingOrders'] == 'total' && mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['waitingOrders'])))) {

	$db = "SELECT COUNT(ID) as total FROM `orders` WHERE accept = 1";
	getTotalOrders($conn, $db);
	
}

// counts of prepared orders

if (isset($_GET['preparedOrders']) && $_GET['preparedOrders'] == 'total' && mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['preparedOrders'])))) {

	$db = "SELECT COUNT(ID) as total FROM `orders` WHERE ready = 1";
	getTotalOrders($conn, $db);
	
}

// counts of removing orders

if (isset($_GET['removingOrders']) && $_GET['removingOrders'] == 'total' && mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['removingOrders'])))) {

	$db = "SELECT COUNT(ID) as total FROM `orders` WHERE remove = 1";
	getTotalOrders($conn, $db);
	
}
// -----------------------------------------------------------------------------------
// --------------------ADD NEW USER --------------------------------------------------
// -----------------------------------------------------------------------------------

if (isset($_GET['newUser']) && $_GET['newUser'] == 'insert' && mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['newUser'])))) {
	
	$postdata = file_get_contents("php://input");
	if (isset($postdata) && !empty($postdata)) {
	 	$request = json_decode($postdata); 

	 	$id = mysqli_real_escape_string($conn, htmlspecialchars(trim($request->id)));
	 	$fullname = mysqli_real_escape_string($conn, htmlspecialchars(trim($request->fullname)));
	 	$username = mysqli_real_escape_string($conn, htmlspecialchars(trim($request->username)));
	 	$password = mysqli_real_escape_string($conn, htmlspecialchars(trim(md5($request->password))));
	 	$duty = mysqli_real_escape_string($conn, htmlspecialchars(trim($request->duty)));

	 	if($id == '') {
	 		$db = "INSERT INTO `users`(`fullname`, `username`, `password`, `duty`) 
	 			VALUES ('{$fullname}', '{$username}', '{$password}', '{$duty}')";
	 		$query= mysqli_query($conn, $db);
	 	}
	 	else if($id !== '' AND $request->password !== ''){
	 		$db = "UPDATE `users` SET `fullname`= '{$fullname}', `username`= '{$username}', 
	 						`password`= '{$password}',`duty`= '{$duty}' WHERE ID = $id";
	 		$query= mysqli_query($conn, $db);
	 	}else if ($id !== '' AND $request->password == '') {
	 		$db = "UPDATE `users` SET `fullname`= '{$fullname}', `username`= '{$username}', 
	 						`duty`= '{$duty}' WHERE ID = $id";
	 		$query= mysqli_query($conn, $db);
	 	}
	}
}

// --------------------SELECT USERS FUNCTION---------------

function getUsers ($conn) {

	$users = [];
	$db = "SELECT * FROM `users`";
	if ($result = mysqli_query($conn, $db)) {
		$cr = 0;
		while ($row = mysqli_fetch_assoc($result)) {
			$users[$cr]['id'] = $row['ID'];
			$users[$cr]['fullname'] = $row['fullname'];
			$users[$cr]['username'] = $row['username'];
			$users[$cr]['password'] = $row['password'];
			$users[$cr]['duty'] = $row['duty'];

			$cr++;
		}

		echo json_encode($users);
	}
	else {
		http_response_code(404);
	}

}

// --------------------SELECT USERS ------------------------------

if (isset($_GET['newUser']) && $_GET['newUser'] == 'select' && mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['newUser'])))) {

	getUsers($conn);

}

// --------------------DELETE USERS ------------------------------

if (isset($_GET['newUser']) && $_GET['newUser'] == 'delete' && mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['newUser'])))) {

	$id = $_GET['id'];

	if ($id !== '') {
		$db = "DELETE FROM `users` WHERE ID = $id";
		$query = mysqli_query($conn, $db);
		getUsers($conn);
	}
}

// -----------------------------------------------------------------
// --------------------LOGIN AUTHENTICATION-------------------------
// -----------------------------------------------------------------

if (isset($_GET['login']) && $_GET['login'] == 'ok' && mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['login'])))) {

	$postdata = file_get_contents("php://input");

	if (isset($postdata) && !empty($postdata)) {
	 	$request = json_decode($postdata); 
	 	
	 	$username = $request->username;
	 	$password = md5($request->password);

	 	if ($username !=='' AND $request->password !=='') {
	 		
			$sql = " SELECT * FROM `users` WHERE username = '{$username}' AND password = '{$password}'";
		    $query= mysqli_query($conn, $sql);
		    $row = mysqli_num_rows($query);
		   

		    if ($row > 0) {
		    	 $rowId = mysqli_fetch_assoc($query);
		    	 $duty = md5($rowId['duty']);
		    	 $correct = array("status" => 'ok', "duty" => $duty);
		    	 echo json_encode($correct);

		    }else{
		    	$incorrect = array("status" => 'incorrect');
		    	echo json_encode($incorrect);
		    }
	 	}else{
	 		$incorrect = array("status" => 'empty');
		    echo json_encode($incorrect);
	 	}

	}
	
}
// --------------------------------------------------------------------------------------------------------
// ------------------------------------------END OF THE CODES----------------------------------------------
// --------------------------------------------------------------------------------------------------------

?>
